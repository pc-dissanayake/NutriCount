<x-filament-panels::page>
    <div class="bg-gray-100 p-6 sm:p-10 md:p-16 mt-20">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach (collect($uniqueUnitIds)->sort() as $unitId => $unitName)
                    <a href="{{ url('/simple/unit-diet-entry') . '?date=' . urlencode(request('date')) . '&unit_id=' . urlencode($unitId) }}"
                        class="relative flex h-full flex-col rounded-md border border-gray-200 bg-white p-3 hover:border-gray-400 sm:rounded-lg sm:p-4">
                        <span class="text-sm mb-1 font-semibold text-gray-900 hover:text-black sm:mb-2 sm:text-md">
                            {{ $unitName }}
                        </span>
                        <span class="text-xs leading-normal text-gray-400 sm:block">
                            Go to Diet Entry for the Unit
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
