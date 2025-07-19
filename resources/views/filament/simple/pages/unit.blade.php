<x-filament-panels::page>

    <!-- Breadcrumb System -->
    <nav class="text-sm  bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <a href="{{ url('/simple') }}" class="text-blue-500 hover:underline">Home</a>
        <span class="mx-2">&gt;</span>
        <span class="text-gray-500 dark:text-gray-400">{{ urlencode(request('date')) ?? 'No Date Selected' }}</span>
    </nav>
    <div class="bg-gray-200 dark:bg-gray-900 p-6 sm:p-10 md:p-16 mt-20 rounded-xl">
        <div class="container mx-auto">
            <!-- Add link to Diet Analysis -->
            <div class="mb-4">
                <a href="{{ url('/simple/diet-analysis') . '?date=' . urlencode(request('date')) }}" 
                    class="bg-primary-500 text-white h-full rounded-md border border-primary-500 p-3 hover:bg-primary-600 hover:border-primary-600 sm:rounded-xl sm:p-4">
                    Go to Total Diet Analysis of National Hospital of Sri Lanka on {{ urlencode(request('date')) ?? 'No Date Selected' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Unit Cards Section -->
    <section class="bg-gray-200 dark:bg-gray-800 p-6 sm:p-10 md:p-8 rounded-xl">
        <div class="container mx-auto">
            <div class="grid grid-cols-4 gap-4">
                @foreach (collect($uniqueUnitIds)->sort() as $unitId => $unitName)
                    <a href="{{ url('/simple/unit-diet-entry') . '?date=' . urlencode(request('date')) . '&unit_id=' . urlencode($unitId) }}"
                        class="h-full rounded-xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-900 p-3 hover:border-gray-400 dark:hover:border-gray-500 sm:rounded-lg sm:p-4">
                        <span class="text-sm mb-1 font-semibold text-gray-900 dark:text-gray-100 hover:text-black dark:hover:text-gray-300 sm:mb-2 sm:text-md">
                            {{ $unitName }}
                        </span>
                        {{-- <span class="text-xs leading-normal text-gray-400 dark:text-gray-500 sm:block">
                            Go to Diet Entry for the Unit
                        </span> --}}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-filament-panels::page>
