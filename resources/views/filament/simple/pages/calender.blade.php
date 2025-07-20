<x-filament-panels::page>
    <div class="p-6">
        @php
            $dates = collect($uniqueDates)->sort();
            $firstDate = $dates->first();
            $lastDate = $dates->last();
            $currentMonth = request('month') ? \Carbon\Carbon::parse(request('month') . '-01') : ($firstDate ? \Carbon\Carbon::parse($firstDate)->startOfMonth() : \Carbon\Carbon::now()->startOfMonth());
            $start = $currentMonth->copy()->startOfMonth();
            $end = $currentMonth->copy()->endOfMonth();
            $calendar = [];
            $uniqueDateSet = $dates->flip();
            $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
            $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
        @endphp
        <div class="flex items-center justify-between mb-4">
            <a href="?month={{ $prevMonth }}" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"></a>
            <span class="text-xl font-bold">{{ $currentMonth->format('F Y') }}</span>
            <a href="?month={{ $nextMonth }}" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300"></a>
        </div>
        <br />
        <div class="grid grid-cols-7 gap-2 mb-4">
            <div class="font-semibold text-center">Sun</div>
            <div class="font-semibold text-center">Mon</div>
            <div class="font-semibold text-center">Tue</div>
            <div class="font-semibold text-center">Wed</div>
            <div class="font-semibold text-center">Thu</div>
            <div class="font-semibold text-center">Fri</div>
            <div class="font-semibold text-center">Sat</div>
        </div>
        <div class="grid grid-cols-7 gap-2">
            @php
                $current = $start->copy();
                while ($current <= $end) {
                    $calendar[] = $current->copy();
                    $current->addDay();
                }
            @endphp
            @for ($i = 0; $i < $start->dayOfWeek; $i++)
                <div></div>
            @endfor
            @foreach ($calendar as $day)
                @php
                    $dateStr = $day->format('Y-m-d');
                    $hasData = isset($hospitalUnitDietAmounts[$dateStr]);
                    $isFutureDate = $day->isFuture();
                @endphp
                <div class="text-center p-1 {{ $hasData ? 'bg-success-500 rounded text-white' : 'bg-primary-500 rounded text-white' }}">
                    @if ($isFutureDate)
                        <span class="text-gray-500">{{ $day->day }}</span>
                    @else
                        <a href="{{ url('/simple/unit?date=' . $dateStr) }}" class="hover:underline">{{ $day->day }}</a>
                    @endif
                </div>
            @endforeach
        </div>
        <br />
         <div class="flex items-center justify-between mb-4">
            <a href="?month={{ $prevMonth }}" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">&larr; Prev</a>
            <span class="text-xl font-bold"></span>
            <a href="?month={{ $nextMonth }}" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">Next &rarr;</a>
        </div>
        
        <!-- Diet Analysis Quick Links -->
        <div class="bg-gray-200 dark:bg-gray-900 p-6 sm:p-10 md:p-16 mt-20 rounded-xl">
            <div class="container mx-auto m-4 p-4">
                <!-- Add link to Diet Analysis for selected month -->
                <div class="mb-4">
                    <a href="{{ url('/simple/diet-analysis') . '?month=' . urlencode(request('month')) }}"
                        class="fi-btn fi-btn-primary h-full rounded-md p-3 sm:rounded-xl sm:p-4">
                        Go to Total Diet Analysis of National Hospital of Sri Lanka for {{ request('month') ?? 'No Month Selected' }}
                    </a>
                </div>
                <!-- Add link to Diet Analysis for selected year -->
                <div>
                    <a href="{{ url('/simple/diet-analysis') . '?year=' . urlencode(request('year')) }}"
                        class="bg-primary-500 text-white h-full rounded-md border border-primary-500 p-3 hover:bg-primary-600 hover:border-primary-600 sm:rounded-xl sm:p-4">
                        Go to Total Diet Analysis of National Hospital of Sri Lanka for {{ request('year') ?? 'No Year Selected' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
