<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HospitalUnitDietAmount;

class DietAmountController extends Controller
{
    public function save(Request $request)
    {
        foreach ($request->input('amounts', []) as $unitId => $diets) {
            foreach ($diets as $dietId => $amount) {
                HospitalUnitDietAmount::updateOrCreate(
                    [
                        'hospital_unit_id' => $unitId,
                        'simple_diet_id' => $dietId,
                        'date' => $request->input('date'),
                    ],
                    ['amount' => $amount]
                );
            }
        }

        return redirect()->back()->with('success', 'Diet amounts saved successfully!');
    }
}
