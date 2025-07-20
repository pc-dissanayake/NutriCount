<x-filament-panels::page>
    <!-- Breadcrumb System -->
    <nav class="text-sm  bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <a href="{{ url('/simple') }}" class="text-blue-500 hover:underline">Home</a>
        <span class="mx-2">&gt;</span>
        <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="text-blue-500 hover:underline">{{ $date ?? 'No Date Selected' }}</a>
        <span class="mx-2">&gt;</span>
        <span class="text-gray-500 dark:text-gray-400">{{ $units->firstWhere('id', request('unit_id'))->name ?? 'Unknown Unit' }}</span>
    </nav>

    <!-- Existing Content -->
    @if ($date && request('unit_id'))
        <div class="mt-4 bg-gray-100 dark:bg-gray-800 p-3 rounded-xl" >
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Selected Date and Unit</h2>
            <p class="text-gray-700 dark:text-gray-500"><strong>Date:</strong> {{ $date }}</p>
            <p class="text-gray-700 dark:text-gray-500"><strong>Unit:</strong> {{ $units->firstWhere('id', request('unit_id'))->name ?? 'Unknown Unit' }}</p><p>&nbsp;</p>
        
            <button 
                type="button" 
                id="show-form" 
                class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 mt-8 px-4 py-2 rounded-xl"
            >
                Edit
            </button>
        </div>
        <div id="form-container" class="hidden">
    @endif

    <!-- Existing Form and Table -->
    <form method="GET" action="{{ url('/simple/unit-diet-entry') }}" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-xl shadow">
        <div class="flex items-center gap-4 ">
            <div class="flex flex-col">
                <label for="date" class="font-semibold mb-1 text-gray-900 dark:text-gray-100">Date:</label>
                <input type="date" id="date" name="date" value="{{ $date ?? '' }}" class="border rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" required>
            </div>

            <div class="flex flex-col">
                <label for="unit_id" class="font-semibold mb-1 text-gray-900 dark:text-gray-100">Unit:</label>
                <select id="unit_id" name="unit_id" class="border rounded-xl px-3 py-2 w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" required>
                    <option value="" disabled selected>Select a unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 px-4 py-2 m-2 rounded-xl">Submit</button>
        </div>
    </form>

    @if ($date && request('unit_id'))
        </div>
    @endif

    @if (!empty($simpleDiets))
        <div class="mt-8 bg-gray-100 dark:bg-gray-900 p-3 rounded-xl">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Simple Diets</h2>
            <form method="POST" action="{{ url('/simple/unit-diet-entry/save') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">Diet Name</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">Amount</th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100"></th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $unitSteps = [
                                // Mass units
                                'mcg' => '0.001', 'mg' => '0.001', 'g' => '0.1', 'kg' => '0.001', 'oz' => '0.01', 'lb' => '0.01', 'st' => '0.01',
                                // Fluid/volume units
                                'ml' => '0.1', 'l' => '0.001', 'tsp' => '0.25', 'tbsp' => '0.25', 'fl oz' => '0.01', 'cup' => '0.01', 'pt' => '0.01', 'qt' => '0.01', 'gal' => '0.01',
                                // Other common units
                                'piece' => '1', 'serving' => '1', 'slice' => '1', 'portion' => '1', 'drop' => '1', 'pinch' => '1', 'sheet' => '1', 'package' => '1', 'container' => '1', 'can' => '1', 'bottle' => '1', 'jar' => '1', 'bag' => '1', 'box' => '1', 'bar' => '1', 'packet' => '1', 'tube' => '1', 'unit' => '1',
                            ];
                            $singularPluralUnits = [
                                'piece' => 'pieces', 'serving' => 'servings', 'slice' => 'slices', 'portion' => 'portions', 'drop' => 'drops', 'pinch' => 'pinches', 'sheet' => 'sheets', 'package' => 'packages', 'container' => 'containers', 'can' => 'cans', 'bottle' => 'bottles', 'jar' => 'jars', 'bag' => 'bags', 'box' => 'boxes', 'bar' => 'bars', 'packet' => 'packets', 'tube' => 'tubes', 'unit' => 'units',
                            ];
                        @endphp
                        @foreach ($simpleDiets as $diet)
                            <tr>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $diet->DietName_en }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                    <input type="number" step="{{ $unitSteps[$diet->primary_amount_unit] ?? '0.001' }}" name="dietAmounts[{{ $diet->id }}]" class="border rounded-xl px-3 py-2 w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" value="{{ $diet->saved_amount ?? '' }}">
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">{{ $diet->primary_amount_value }}</td>
                                
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                    @if (array_key_exists($diet->primary_amount_unit, $singularPluralUnits))
                                        {{ $diet->primary_amount_value == 1 ? $diet->primary_amount_unit : $singularPluralUnits[$diet->primary_amount_unit] }}
                                    @else
                                        {{ $diet->primary_amount_unit }}
                                    @endif
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex items-center gap-2">
                    <button type="submit" class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 px-4 py-2">Save Diet Amounts</button>
                </div>
            </form>
        </div>
    @endif

    <script>
        document.getElementById('show-form').addEventListener('click', function () {
            document.getElementById('form-container').classList.remove('hidden');
            document.querySelector('.mt-8').classList.add('hidden');
        });
    </script>
</x-filament-panels::page>
