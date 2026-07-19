<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class AiRecommendationService
{
    protected $flaskUrl;

    public function __construct()
    {
        $this->flaskUrl = env('FLASK_API_URL', 'http://127.0.0.1:5000');
    }

    /**
     * Get recommendations from Python Flask API
     */
    public function getRecommendations($menuName, $diet = null, $budget = null, $allergies = null)
    {
        try {
            $response = Http::timeout(5)->get($this->flaskUrl . '/recommend', [
                'menu' => $menuName,
                'diet' => $diet,
                'budget' => $budget,
                'allergies' => $allergies
            ]);

            if ($response->successful()) {
                $recommendedNames = $response->json();
                
                // Fetch products from database that match the names returned by Flask
                if (is_array($recommendedNames) && count($recommendedNames) > 0) {
                    return Product::whereIn('nama_menu', $recommendedNames)->get();
                }
            }
        } catch (\Exception $e) {
            Log::error('AI Recommendation Service failed: ' . $e->getMessage());
        }

        // Fallback: Return some popular items from database if API fails
        return Product::where('is_available', true)
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();
    }

    /**
     * Record that a user viewed a menu (Virtual Memory)
     */
    public function recordHistory($userId, $menuId)
    {
        try {
            Http::timeout(3)->post($this->flaskUrl . '/view_menu', [
                'user_id' => $userId,
                'menu_id' => $menuId
            ]);
        } catch (\Exception $e) {
            Log::error('AI Service - Failed to record history: ' . $e->getMessage());
        }
    }

    /**
     * Get history-based weekly recommendations from KNN model
     */
    public function getWeeklyRecommendations($userId, $budget = 25000)
    {
        try {
            $response = Http::timeout(5)->get($this->flaskUrl . '/recommend_history', [
                'user_id' => $userId,
                'budget' => $budget
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['recommendations']) && is_array($data['recommendations'])) {
                    // Extract menu IDs from the recommendations
                    $menuIds = collect($data['recommendations'])->pluck('menu_id')->toArray();
                    
                    if (count($menuIds) > 0) {
                        // Fetch products maintaining the sorted order from Python
                        $idsOrdered = implode(',', $menuIds);
                        return Product::whereIn('id', $menuIds)
                            ->orderByRaw("FIELD(id, $idsOrdered)")
                            ->get();
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('AI Service - Failed to get weekly recommendations: ' . $e->getMessage());
        }

        // Fallback: Return some popular items from database if API fails
        return Product::where('is_available', true)
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();
    }
}
