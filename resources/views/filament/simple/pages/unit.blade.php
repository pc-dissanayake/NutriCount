<x-filament-panels::page>

    <!-- Breadcrumb System -->
    <nav class="flex items-center justify-between text-sm bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <div class="flex items-center">
        <a href="{{ url('/simple/calender') }}" class="text-blue-500 hover:underline">Home</a>
        <span class="mx-2">&gt;</span>
        <span class="text-gray-500 dark:text-gray-400">{{ urlencode(request('date')) ?? 'No Date Selected' }}</span>
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
                @foreach (collect($unitData)->sortBy('name') as $unit)
                    <a href="{{ url('/simple/unit-diet-entry') . '?date=' . urlencode(request('date')) . '&unit_id=' . urlencode($unit['id']) }}"
                        class="h-full rounded-xl border p-3 sm:rounded-lg sm:p-4 border-gray-200 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500"
                        style="{{ $unit['dataavailable'] ? (
                            'background-color: #d1fae5; border-color: #4ade80;' . (request()->has('theme') && request('theme') === 'dark' ? 'background-color: #064e3b; border-color: #22c55e;' : '')
                        ) : (request()->has('theme') && request('theme') === 'dark' ? 'background-color: #111827;' : 'background-color: #fff;') }}">
                        <span class="text-sm mb-1 font-semibold text-gray-900 dark:text-gray-100 hover:text-black dark:hover:text-gray-300 sm:mb-2 sm:text-md">
                            {{ $unit['name'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</x-filament-panels::page>
