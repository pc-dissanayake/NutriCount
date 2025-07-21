<?php

namespace App\Http\Controllers;

use App\Models\HospitalUnit;
use App\Models\HospitalUnitDietAmount;
use App\Models\SimpleDiet;
use App\Models\NutritionOrder;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with nutrition data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get statistics data
        $stats = $this->getStats();
        
        // Get features data
        $features = $this->getFeatures();
        
        // Return view with data
        return view('welcome', compact('stats', 'features'));
    }
    
    /**
     * Get statistics for the welcome page.
     *
     * @return array
     */
    private function getStats()
    {
        // In a real application, these would be dynamic queries
        // Here we'll use static data or basic counts from models

        return [
            'dailyDiets' => number_format(HospitalUnitDietAmount::count() ?: 3000),
            'hospitalUnits' => number_format(HospitalUnit::count() ?: 50),
            //'nutritionalPlans' => number_format(SimpleDiet::count() ?: 25),
        ];
    }
    
    /**
     * Get feature details for the welcome page.
     *
     * @return array
     */
    private function getFeatures()
    {
        return [
            [
                'title' => 'Patient Diet Management',
                'description' => 'Track and manage individualized patient dietary requirements and restrictions.',
                'icon' => 'M13 10V3L4 14h7v7l9-11h-7z',
            ],
            [
                'title' => 'Hospital Unit Diet Planning',
                'description' => 'Efficiently allocate and plan nutrition resources across all hospital units.',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            ],
            [
                'title' => 'Analytics & Reporting',
                'description' => 'Generate comprehensive reports on nutritional metrics and dietary requirements.',
                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
            ],
            [
                'title' => 'FHIR Compatibility',
                'description' => 'Seamlessly integrate and exchange nutrition and patient data using HL7 FHIR standards.',
                'icon' => 'M12 2a10 10 0 100 20 10 10 0 000-20zm1 14.5v-5h2l-3-4-3 4h2v5h2z',
            ],
            [
                'title' => 'Interoperable',
                'description' => 'Easily connect with other healthcare systems for secure and standardized data sharing.',
                'icon' => 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1',
            ],
            [
                'title' => 'Usability',
                'description' => 'Designed for intuitive use by clinicians and staff, with a modern, accessible interface.',
                'icon' => 'M12 4a8 8 0 100 16 8 8 0 000-16zm0 4a2 2 0 110 4 2 2 0 010-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z',
            ],
        ];
    }
}
