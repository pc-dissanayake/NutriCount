<x-filament-panels::page>
    <form method="GET" action="{{ url('/simple/unit-diet-analysis') }}" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-xl shadow mt-4">
        <div class="flex items-center gap-4">
            <div class="flex flex-col">
                <label for="unit_id" class="font-semibold mb-1 text-gray-900 dark:text-gray-100">Unit</label>
                <select id="unit_id" name="unit_id" class="border rounded-xl px-3 py-2 w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" required>
                    <option value="" disabled selected>Select a unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label for="start_date" class="font-semibold mb-1 text-gray-900 dark:text-gray-100">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate ?? '' }}" class="border rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" required>
            </div>

            <div class="flex flex-col">
                <label for="end_date" class="font-semibold mb-1 text-gray-900 dark:text-gray-100">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate ?? '' }}" class="border rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" required>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 px-4 py-2 m-2 rounded-xl">View Analysis</button>
        </div>
    </form>

    @if ($dietData->isNotEmpty())
        <div class="mt-4 bg-gray-100 dark:bg-gray-800 p-3 rounded-xl">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Diet Analysis</h2>

            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">Date</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">Diet Type</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dietData as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $data->date }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $data->diet_type }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $data->amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="dietChart" class="mt-6"></div>
        </div>

        <div class="mt-4 bg-gray-100 dark:bg-gray-800 p-3 rounded-xl">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Unit Diet Analysis</h2>

            <div class="overflow-x-auto">
                <table style="border-collapse: collapse; width: 100%;" class="p-4">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black; padding: 8px;">Unit</th>
                            @foreach ($dietTypes as $dietType)
                                <th style="border: 1px solid black; padding: 8px;" class="rotate">
                                    <div><span>{{ $dietType->DietName_en }} ({{ $dietType->primary_amount_unit }})</span></div>
                                </th>
                            @endforeach
                            <th style="border: 1px solid black; padding: 8px; display: none;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $unitSteps = [
                            'mcg' => 0.001, 'mg' => 0.001, 'g' => 0.1, 'kg' => 0.001, 'oz' => 0.01, 'lb' => 0.01, 'st' => 0.01,
                            'ml' => 0.1, 'l' => 0.001, 'tsp' => 0.25, 'tbsp' => 0.25, 'fl oz' => 0.01, 'cup' => 0.01, 'pt' => 0.01, 'qt' => 0.01, 'gal' => 0.01,
                            'piece' => 1, 'serving' => 1, 'slice' => 1, 'portion' => 1, 'drop' => 1, 'pinch' => 1, 'sheet' => 1, 'package' => 1, 'container' => 1, 'can' => 1, 'bottle' => 1, 'jar' => 1, 'bag' => 1, 'box' => 1, 'bar' => 1, 'packet' => 1, 'tube' => 1, 'unit' => 1,
                        ];
                        @endphp
                        @foreach ($units as $unit)
                            <tr>
                                <td style="border: 1px solid black; padding: 8px;">{{ $unit->name }}</td>
                                @php
                                    $unitTotal = 0;
                                @endphp
                                @foreach ($dietTypes as $dietType)
                                    @php
                                        $amount = $dietData->where('hospital_unit_id', $unit->id)->where('simple_diet_id', $dietType->id)->sum('amount');
                                        if ($dietType->multiply_values) {
                                            $amount *= $dietType->primary_amount_value;
                                        }
                                        $step = $unitSteps[$dietType->primary_amount_unit] ?? 0.001;
                                        $decimals = strpos($step, '.') !== false ? strlen(explode('.', (string)$step)[1]) : 0;
                                        $amount = round($amount, $decimals);
                                        $unitTotal += $amount;
                                    @endphp
                                    <td style="border: 1px solid black; padding: 8px;" dietType="{{ $dietType->id }}">
                                        {{ $amount }}
                                    </td>
                                @endforeach
                                <td style="border: 1px solid black; padding: 8px; display: none;">{{ $unitTotal }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="border: 1px solid black; padding: 8px; font-weight: bold;">Total</td>
                            @foreach ($dietTypes as $dietType)
                                @php
                                    $columnTotal = $dietData->where('simple_diet_id', $dietType->id)->sum('amount');
                                    if ($dietType->multiply_values) {
                                        $columnTotal *= $dietType->primary_amount_value;
                                    }
                                    $step = $unitSteps[$dietType->primary_amount_unit] ?? 0.001;
                                    $decimals = strpos($step, '.') !== false ? strlen(explode('.', (string)$step)[1]) : 0;
                                    $columnTotal = round($columnTotal, $decimals);
                                @endphp
                                <td style="border: 1px solid black; padding: 8px; font-weight: bold;" dietType="{{ $dietType->id }}" dietType-active="{{ $dietType->active ? 'true' : 'false' }}" class="total-row">
                                    {{ $columnTotal }}
                                </td>
                            @endforeach
                            <td style="border: 1px solid black; padding: 8px; font-weight: bold; display: none;">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">No data found for the selected unit and date range.</div>
    @endif
</x-filament-panels::page>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dietChart').getContext('2d');
        const chartData = @json($dietData->groupBy('diet_type')->map(fn($group) => $group->sum('amount')));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(chartData),
                datasets: [{
                    label: 'Diet Amount',
                    data: Object.values(chartData),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
