<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HospitalUnitDietAmount;

class UnitDietEntryController extends Controller{

    /**
     * Save the diet amounts for a specific patient, unit, and date.
     */
    public function saveIndividualDietAmounts(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:hospital_units,id',
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'dietAmounts' => 'required|array',
            'dietAmounts.*' => 'nullable|numeric|min:0|max:5000000',
        ]);

        $dietAmounts = $request->input('dietAmounts', []);
        $date = $request->input('date');
        $unitId = $request->input('unit_id');
        $patientId = $request->input('patient_id');

        $updatedCount = 0;
        $createdCount = 0;

        foreach ($dietAmounts as $dietId => $amount) {
            // Skip if amount is null, empty, or zero
            if ($amount === null || $amount === '' || $amount === 0) {
                continue;
            }

            // Find existing record
            $existingRecord = HospitalUnitDietAmount::where([
                'hospital_unit_id' => $unitId,
                'simple_diet_id' => $dietId,
                'patient_id' => $patientId,
                'date' => $date,
            ])->first();

            // Check if this is a new record or amount has changed
            if (!$existingRecord) {
                // Create new record
                HospitalUnitDietAmount::create([
                    'hospital_unit_id' => $unitId,
                    'simple_diet_id' => $dietId,
                    'patient_id' => $patientId,
                    'date' => $date,
                    'amount' => $amount,
                    'created_by_userid' => Auth::id(),
                ]);
                $createdCount++;
            } elseif ($existingRecord->amount != $amount) {
                // Update only if amount has changed
                $existingRecord->update([
                    'amount' => $amount,
                    'created_by_userid' => Auth::id(),
                ]);
                $updatedCount++;
            }
            // If amount is the same, skip (no update needed)
        }

        $message = "Patient diet amounts saved successfully.";
        if ($createdCount > 0 || $updatedCount > 0) {
            $message = "Patient diet amounts saved: {$createdCount} created, {$updatedCount} updated.";
        } else {
            $message = "No changes detected in patient diet amounts.";
        }

        return redirect()->back()->with('success', $message);
    }

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
            'dietAmounts.*' => 'nullable|numeric|min:0|max:5000000',
        ]);

        $dietAmounts = $request->input('dietAmounts', []);
        $date = $request->input('date');
        $unitId = $request->input('unit_id');

        $updatedCount = 0;
        $createdCount = 0;

        foreach ($dietAmounts as $dietId => $amount) {
            // Skip if amount is null, empty, or zero
            if ($amount === null || $amount === '' || $amount === 0) {
                continue;
            }

            // Find existing record
            $existingRecord = HospitalUnitDietAmount::where([
                'hospital_unit_id' => $unitId,
                'simple_diet_id' => $dietId,
                'date' => $date,
            ])->first();

            // Check if this is a new record or amount has changed
            if (!$existingRecord) {
                // Create new record
                HospitalUnitDietAmount::create([
                    'hospital_unit_id' => $unitId,
                    'simple_diet_id' => $dietId,
                    'date' => $date,
                    'amount' => $amount,
                    'created_by_userid' => Auth::id(),
                ]);
                $createdCount++;
            } elseif ($existingRecord->amount != $amount) {
                // Update only if amount has changed
                $existingRecord->update([
                    'amount' => $amount,
                    'created_by_userid' => Auth::id(),
                ]);
                $updatedCount++;
            }
            // If amount is the same, skip (no update needed)
        }

        $message = "Diet amounts saved successfully.";
        if ($createdCount > 0 || $updatedCount > 0) {
            $message = "Diet amounts saved: {$createdCount} created, {$updatedCount} updated.";
        } else {
            $message = "No changes detected in diet amounts.";
        }

        return redirect()->back()->with('success', $message);
    }
}
