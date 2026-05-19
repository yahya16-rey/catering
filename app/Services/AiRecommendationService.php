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
}
