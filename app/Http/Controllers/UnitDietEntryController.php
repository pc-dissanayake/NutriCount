<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HospitalUnitDietAmount;

class UnitDietEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Save the diet amounts for a specific unit and date.
     */
    public function saveDietAmounts(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:hospital_units,id',
            'date' => 'required|date',
            'dietAmounts' => 'required|array',
            'dietAmounts.*' => 'nullable|numeric|min:0',
        ]);

        $dietAmounts = $request->input('dietAmounts', []);
        $date = $request->input('date');
        $unitId = $request->input('unit_id');

        foreach ($dietAmounts as $dietId => $amount) {
            HospitalUnitDietAmount::updateOrCreate(
                [
                    'hospital_unit_id' => $unitId,
                    'simple_diet_id' => $dietId,
                    'date' => $date,
                ],
                ['amount' => $amount]
            );
        }

        return redirect()->back()->with('success', 'Diet amounts saved successfully.');
    }
}
