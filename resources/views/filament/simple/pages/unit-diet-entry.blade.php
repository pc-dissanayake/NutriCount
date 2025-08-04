<x-filament-panels::page>
    <!-- Breadcrumb System -->
    <nav class="flex items-center justify-between text-sm bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <div class="flex items-center">
            <a href="{{ url('/simple/calender') }}" class="text-blue-500 hover:underline">Home</a>
            <span class="mx-2">&gt;</span>
            <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="text-blue-500 hover:underline">{{ $date ?? 'No Date Selected' }}</a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-500 dark:text-gray-400">{{ $units->firstWhere('id', request('unit_id'))->name ?? 'Unknown Unit' }}</span>
        </div>
        <div class="flex gap-2">
            @if(request('Language') !== 'Eng' && request('Language') !== null)
                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('Language'), ['Language' => 'Eng'])) }}" class="filament-button filament-button-primary px-3 py-1 rounded-xl" style="background-color: #8D153A; color: #fff; border-color: #8D153A;">English</a>
            @endif
            @if(request('Language') !== 'Sin')
                <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('Language'), ['Language' => 'Sin'])) }}" class="filament-button filament-button-primary px-3 py-1 rounded-xl" style="background-color: #FFBE29; color: #000; border-color: #FFBE29;">සිංහල</a>
            @endif
            @if(request('Language') !== 'Tam')
            <a href="{{ url()->current() . '?' . http_build_query(array_merge(request()->except('Language'), ['Language' => 'Tam'])) }}" class="filament-button filament-button-primary px-3 py-1 rounded-xl" style="background-color: #00534E; color: #fff; border-color: #00534E;">தமிழ்</a>
            @endif
        </div>
    </nav>

    @if(Auth::user() && userHasPermission(Auth::user(), 'add_individual_diet_data.Patient'))
   <a href="{{ url('/simple/paient-individual-diet') . '?date=' . urlencode($date) . '&unit_id=' . urlencode(request('unit_id')) }}" 
    class="filament-button filament-button-primary text-white hover:bg-primary-600 focus:ring-primary-500 mt-8 px-4 py-2 rounded-xl"
    style="background-color: #0d6efd; border-color: #0d6efd;"
>
    @if(request('Language') === 'Sin')
        තනි රෝගියාගේ ආහාර දත්ත ඇතුලත් කිරීම
    @elseif(request('Language') === 'Tam')
        தனிப்பட்ட நோயாளி உணவு தரவு உள்ளீடு
    @else
        Individual Patient Diet Data Entry
    @endif
</a>
@endif
    <!-- Existing Content -->
    @if ($date && request('unit_id'))
        <div class="mt-4 bg-gray-100 dark:bg-gray-800 p-3 rounded-xl" >
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                @if(request('Language') === 'Sin')
                    තෝරාගත් දිනය සහ ඒකකය
                @elseif(request('Language') === 'Tam')
                    தேர்ந்தெடுக்கப்பட்ட தேதி மற்றும் பிரிவு
                @else
                    Selected Date and Unit
                @endif
            </h2>
            <p class="text-gray-700 dark:text-gray-500">
                <strong>
                    @if(request('Language') === 'Sin')
                        දිනය
                    @elseif(request('Language') === 'Tam')
                        தேதி
                    @else
                        Date
                    @endif
                :</strong> {{ $date }}
            </p>
            <p class="text-gray-700 dark:text-gray-500">
                <strong>
                    @if(request('Language') === 'Sin')
                        ඒකකය
                    @elseif(request('Language') === 'Tam')
                        பிரிவு
                    @else
                        Unit
                    @endif
                :</strong> {{ $units->firstWhere('id', request('unit_id'))->name ?? 'Unknown Unit' }}
            </p>
            <p>&nbsp;</p>
            <button 
                type="button" 
                id="show-form" 
                class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 mt-8 px-4 py-2 rounded-xl">
                @if(request('Language') === 'Sin')
                    සංස්කරණය
                @elseif(request('Language') === 'Tam')
                    திருத்து
                @else
                    Edit
                @endif
            </button>
            
        </div>
        <div id="form-container" class="hidden">
    @endif

    <!-- Existing Form and Table -->
    <form method="GET" action="{{ url('/simple/unit-diet-entry') }}" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-xl shadow">
        <div class="flex items-center gap-4 ">
            <div class="flex flex-col">
                <label for="date" class="font-semibold mb-1 text-gray-900 dark:text-gray-100">
                    @if(request('Language') === 'Sin')
                        දිනය
                    @elseif(request('Language') === 'Tam')
                        தேதி
                    @else
                        Date
                    @endif
                </label>
                <input type="date" id="date" name="date" value="{{ $date ?? '' }}" class="border rounded-xl px-3 py-2 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" required>
            </div>

            <div class="flex flex-col">
                <label for="unit_id" class="font-semibold mb-1 text-gray-900 dark:text-gray-100">
                    @if(request('Language') === 'Sin')
                        ඒකකය
                    @elseif(request('Language') === 'Tam')
                        பிரிவு
                    @else
                        Unit
                    @endif
                </label>
                <select id="unit_id" name="unit_id" class="border rounded-xl px-3 py-2 w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-gray-100" required>
                    <option value="" disabled selected>
                        @if(request('Language') === 'Sin')
                            ඒකය තෝරන්න
                        @elseif(request('Language') === 'Tam')
                            பிரிவை தேர்ந்தெடுக்கவும்
                        @else
                            Select a unit
                        @endif
                    </option>
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
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-red-900 dark:text-red-100">
                                @if(request('Language') === 'Sin')
                                    ආහාර නාමය
                                @elseif(request('Language') === 'Tam')
                                    உணவு பெயர்
                                @else
                                    Diet Name
                                @endif
                            </th>
                            <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                @if(request('Language') === 'Sin')
                                    ප්‍රමාණය
                                @elseif(request('Language') === 'Tam')
                                    அளவு
                                @else
                                    Amount
                                @endif
                            </th>
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
                                <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                    @if(request('Language') === 'Sin')
                                        {{ $diet->DietName_si }}
                                    @elseif(request('Language') === 'Tam')
                                        {{ $diet->DietName_tm }}
                                    @else
                                        {{ $diet->DietName_en }}
                                    @endif
                                </td>
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
                    <button type="submit" class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 px-4 py-2">
                        @if(request('Language') === 'Sin')
                            සුරකින්න
                        @elseif(request('Language') === 'Tam')
                            சேமிக்கவும்
                        @else
                            Save Diet Amounts
                        @endif
                    </button>
                    <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="filament-button filament-button-primary bg-gray-500 text-white hover:bg-gray-600 focus:ring-gray-500 px-4 py-2" style="background-color: #6b7280; border-color: #6b7280; color: #fff;">
                        @if(request('Language') === 'Sin')
                            ආපසු
                        @elseif(request('Language') === 'Tam')
                            பின்செல்
                        @else
                            Back
                        @endif
                    </a>
                    @if(Auth::user() && userHasPermission(Auth::user(), 'log_view.unit-simple_panel'))
                        <a href="{{ url('/simple/unit-diet-logs') . '?date=' . urlencode($date) . '&unit_id=' . urlencode(request('unit_id')) }}" class="filament-button filament-button-primary bg-pink-600 text-white hover:bg-pink-700 focus:ring-pink-500 px-4 py-2 " style="background-color: #EC4899; border-color: #EC4899; color: #fff;">
                            @if(request('Language') === 'Sin')
                                ක්‍රියාකාරකම් ලොග්
                            @elseif(request('Language') === 'Tam')
                                செயல்பாட்டு பதிவுகள்
                            @else
                                View Activity Logs
                            @endif
                        </a>
                    @endif
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
