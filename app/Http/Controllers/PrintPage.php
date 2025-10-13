<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HospitalUnit;
use App\Models\SimpleDiet;
use App\Models\HospitalUnitDietAmount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PrintPage extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            // Get date from request or use today's date
            $date = $request->input('date', Carbon::today()->toDateString());
            
            // Load all units with their diet amounts for the specified date
            $units = HospitalUnit::where('active', true)
                ->orderBy('name')
                ->get();
                
            // Load all active diets
            $diets = SimpleDiet::where('active', true)
                ->orderBy('list_order')
                ->get();
            
            // Create test data if no units or diets exist
            if ($units->isEmpty() || $diets->isEmpty()) {
                return $this->renderWithTestData();
            }
                
            // Load diet amounts for the specified date
            $dietAmounts = HospitalUnitDietAmount::with(['hospitalUnit', 'simpleDiet'])
                ->where('date', $date)
                ->whereNull('patient_id') // Ward cumulative only
                ->get();
            
            // Convert to a more usable format with proper fallbacks
            $dietAmountsMap = [];
            foreach ($dietAmounts as $amount) {
                $unitId = $amount->hospital_unit_id;
                $dietId = $amount->simple_diet_id;
                
                if (!isset($dietAmountsMap[$unitId])) {
                    $dietAmountsMap[$unitId] = [];
                }
                
                if (!isset($dietAmountsMap[$unitId][$dietId])) {
                    $dietAmountsMap[$unitId][$dietId] = $amount;
                }
            }
                
            // Prepare the data structure for display
            $dietData = [];
            $dietTotals = [];
            $grandTotal = 0;
            
            foreach ($units as $unit) {
                $unitData = [
                    'unit' => $unit,
                    'diets' => []
                ];
                
                $unitTotal = 0;
                
                foreach ($diets as $diet) {
                    // Safely get amount with fallback to 0
                    $amount = 0;
                    if (isset($dietAmountsMap[$unit->id][$diet->id])) {
                        $amount = $dietAmountsMap[$unit->id][$diet->id]->amount ?? 0;
                    }
                    
                    $unitData['diets'][$diet->id] = [
                        'diet' => $diet,
                        'amount' => $amount
                    ];
                    
                    $unitTotal += $amount;
                    
                    // Add to totals
                    if (!isset($dietTotals[$diet->id])) {
                        $dietTotals[$diet->id] = [
                            'diet' => $diet,
                            'total' => 0
                        ];
                    }
                    $dietTotals[$diet->id]['total'] += $amount;
                }
                
                $unitData['total'] = $unitTotal;
                $grandTotal += $unitTotal;
                $dietData[] = $unitData;
            }
            
            $data = [
                'date' => $date,
                'units' => $units,
                'diets' => $diets,
                'diet_data' => $dietData,
                'diet_totals' => $dietTotals,
                'grand_total' => $grandTotal,
                'generated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];
            
            return view('pageexport', ['data' => $data]);
        } catch (\Exception $e) {
            Log::error("Diet export error: " . $e->getMessage());
            return $this->renderWithTestData($e->getMessage());
        }
    }
    
    /**
     * Fallback to test data when real data isn't available or there's an error
     */
    private function renderWithTestData($errorMessage = null)
    {
        // Create test units
        $units = [
            (object)['id' => 'unit1', 'name' => 'Medical Ward', 'alias' => 'MED-01'],
            (object)['id' => 'unit2', 'name' => 'Surgical Ward', 'alias' => 'SUR-02'],
            (object)['id' => 'unit3', 'name' => 'Pediatric Ward', 'alias' => 'PED-03'],
            (object)['id' => 'unit4', 'name' => 'Maternity', 'alias' => 'MAT-04'],
        ];
        
        // Create test diets
        $diets = [
            (object)['id' => 'diet1', 'DietName_en' => 'Regular Diet', 'primary_amount_unit' => 'servings'],
            (object)['id' => 'diet2', 'DietName_en' => 'Soft Diet', 'primary_amount_unit' => 'servings'],
            (object)['id' => 'diet3', 'DietName_en' => 'Liquid Diet', 'primary_amount_unit' => 'ml'],
            (object)['id' => 'diet4', 'DietName_en' => 'Diabetic Diet', 'primary_amount_unit' => 'servings'],
        ];
        
        // Create test data structure
        $dietData = [];
        $dietTotals = [];
        $grandTotal = 0;
        
        foreach ($units as $unit) {
            $unitData = [
                'unit' => $unit,
                'diets' => []
            ];
            
            $unitTotal = 0;
            
            foreach ($diets as $diet) {
                // Generate random amount between 0 and 20
                $amount = rand(0, 20);
                
                $unitData['diets'][$diet->id] = [
                    'diet' => $diet,
                    'amount' => $amount
                ];
                
                $unitTotal += $amount;
                
                // Add to totals
                if (!isset($dietTotals[$diet->id])) {
                    $dietTotals[$diet->id] = [
                        'diet' => $diet,
                        'total' => 0
                    ];
                }
                $dietTotals[$diet->id]['total'] += $amount;
            }
            
            $unitData['total'] = $unitTotal;
            $grandTotal += $unitTotal;
            $dietData[] = $unitData;
        }
        
        $data = [
            'date' => Carbon::today()->toDateString(),
            'units' => collect($units),
            'diets' => collect($diets),
            'diet_data' => $dietData,
            'diet_totals' => $dietTotals,
            'grand_total' => $grandTotal,
            'generated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_test_data' => true,
            'error' => $errorMessage
        ];
        
        return view('pageexport', ['data' => $data]);
    }
    
}
