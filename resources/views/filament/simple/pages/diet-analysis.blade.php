<x-filament-panels::page>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 8px;">Unit/Simple Diet</th>
                @foreach ($dietTypes as $dietType)
                    <th style="border: 1px solid black; padding: 8px;">{{ $dietType->DietName_en }} ({{ $dietType->primary_amount_unit }})</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($units as $unit)
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">{{ $unit->name }}</td>
                    @foreach ($dietTypes as $dietType)
                        <td style="border: 1px solid black; padding: 8px;">
                            {{ $dietData->where('hospital_unit_id', $unit->id)->where('simple_diet_id', $dietType->id)->first()->amount ?? '' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament-panels::page>
