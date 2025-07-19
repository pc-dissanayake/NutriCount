<x-filament-panels::page>


    @if ($date && request('unit_id'))
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Selected Date and Unit</h2>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Unit:</strong> {{ $units->firstWhere('id', request('unit_id'))->name ?? 'Unknown Unit' }}</p>
            <button 
                type="button" 
                type="button" 
                id="show-form" 
                class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 mt-4 px-4 py-2 rounded"
            >
                Edit
            </button>
        </div>
        <div id="form-container" class="hidden">
    @endif

    <form method="GET" action="{{ url('/simple/unit-diet-entry') }}" class="space-y-4">
        <div class="flex items-center gap-4">
            <div class="flex flex-col">
                <label for="date" class="font-semibold mb-1">Date:</label>
                <input type="date" id="date" name="date" value="{{ $date ?? '' }}" class="border rounded px-3 py-2"
                    required>
            </div>
            

            <div class="flex flex-col">
                <label for="unit_id" class="font-semibold mb-1">Unit:</label>
                <select id="unit_id" name="unit_id" class="border rounded px-3 py-2 w-full"  required>
                    <option value="" disabled selected>Select a unit</option>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}"
                            {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>
          
        </div>
        <div class="flex items-center gap-2">
            <button type="submit"
                class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 px-4 py-2 m-2">Submit</button>
        </div>
    </form>

    @if ($date && request('unit_id'))
        </div>
    @endif

    @if (!empty($simpleDiets))
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Simple Diets</h2>
            <form method="POST" action="{{ url('/simple/unit-diet-entry/save') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Diet Name</th>
                            <th class="border border-gray-300 px-4 py-2">Amount</th>
                            <th class="border border-gray-300 px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($simpleDiets as $diet)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $diet->DietName_en }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <input type="number" name="dietAmounts[{{ $diet->id }}]" class="border rounded px-3 py-2 w-full" value="{{ $diet->saved_amount ?? '' }}">
                                </td>
                                <td class="border border-gray-300 px-4 py-2">{{ $diet->primary_amount_unit }}</td>
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
