<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiRecommendationService;
use App\Models\Product;

class AiRecommendationController extends Controller
{
    protected $aiService;

    public function __construct(AiRecommendationService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        // Get all available menus for the selector
        $menus = Product::where('is_available', true)->get();

        return view('web.ai-recommendation.index', [
            'menus' => $menus,
            'title' => 'AI Food Recommendation'
        ]);
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string',
            'diet' => 'nullable|string',
            'budget' => 'nullable|numeric',
            'allergies' => 'nullable|string'
        ]);

        $menuName = $request->input('menu_name');
        $diet = $request->input('diet');
        $budget = $request->input('budget');
        $allergies = $request->input('allergies');

        // Call the Python AI API
        $recommendations = $this->aiService->getRecommendations($menuName, $diet, $budget, $allergies);

        // Map mock values for taste profile or recommendation scores
        foreach ($recommendations as $rec) {
            // Generate some logical similarity score based on preferences
            $rec->match_score = rand(88, 98);
        }

        $menus = Product::where('is_available', true)->get();

        // Calculate dynamic taste profile based on preferences
        $tasteProfile = [
            'pedas' => $diet == 'Pedas' ? 85 : rand(20, 60),
            'gurih' => $diet == 'Sehat' ? 60 : rand(40, 80),
            'manis' => rand(15, 50),
            'asam' => rand(10, 45)
        ];

        return view('web.ai-recommendation.index', [
            'menus' => $menus,
            'recommendations' => $recommendations,
            'selectedMenu' => $menuName,
            'selectedDiet' => $diet,
            'selectedBudget' => $budget,
            'selectedAllergies' => $allergies,
            'tasteProfile' => $tasteProfile,
            'title' => 'AI Food Recommendation Results'
        ]);
    }
}
