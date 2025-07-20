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
            $currentMonthName = $currentMonth->format('F');
            $currentMonthNumber = $currentMonth->format('m');
            $currentYear = $currentMonth->format('Y');
            $currentDate = $currentMonth->format('Y-m-d');
        @endphp
        <div class="flex items-center justify-between mb-4">
            <a href="?month={{ $prevMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700"></a>
            <span class="text-xl font-bold">{{ $currentMonth->format('F Y') }}</span>
            <a href="?month={{ $nextMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700"></a>
        </div>
        <div class="mb-2 text-sm text-gray-700 dark:text-gray-300">
            Current Month: <span class="font-semibold">{{ $currentMonthName }}</span> |
            Month Number: <span class="font-semibold">{{ $currentMonthNumber }}</span> |
            Year: <span class="font-semibold">{{ $currentYear }}</span> |
            Current Date: <span class="font-semibold">{{ $currentDate }}</span>
        </div>
        <br />
        <div class="grid grid-cols-7 gap-2 mb-4">
            <div class="font-semibold text-center">Mon</div>
            <div class="font-semibold text-center">Tue</div>
            <div class="font-semibold text-center">Wed</div>
            <div class="font-semibold text-center">Thu</div>
            <div class="font-semibold text-center">Fri</div>
            <div class="font-semibold text-center">Sat</div>
            <div class="font-semibold text-center">Sun</div>
        </div>
        <div class="grid grid-cols-7 gap-2">
            @php
                // Adjust start to Monday
                $startDayOfWeek = $start->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
                $current = $start->copy();
                while ($current <= $end) {
                    $calendar[] = $current->copy();
                    $current->addDay();
                }
            @endphp
            @for ($i = 1; $i < $startDayOfWeek; $i++)
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
            <a href="?month={{ $prevMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700">&larr; Prev</a>
            <span class="text-xl font-bold"></span>
            <a href="?month={{ $nextMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700">Next &rarr;</a>
        </div>
        
        <!-- Diet Analysis Quick Links -->
        <div class="bg-gray-200 dark:bg-gray-900 p-6 sm:p-10 md:p-16 mt-20 rounded-xl">
            <div class="container mx-auto m-4 p-4">
                <!-- Add link to Diet Analysis for selected month -->
                <div class="mb-4">
                    <a href="{{ url('/simple/diet-analysis') . '?month=' . urlencode($currentMonth->format('Y-m')) }}"
                        class="fi-btn fi-btn-primary h-full rounded-md p-3 sm:rounded-xl sm:p-4"
                        style="background-color: #14b8a6; color: white; border: 1px solid #0d9488;">
                        Go to Total Diet Analysis of National Hospital of Sri Lanka for month {{ $currentMonth->format('M Y') ?? 'No Month Selected' }}
                    </a>
                </div>
                <br />
                <!-- Add link to Diet Analysis for selected year -->
                <div>
                    <a href="{{ url('/simple/diet-analysis') . '?year=' . urlencode($currentMonth->format('Y')) }}"
                        class="fi-btn fi-btn-primary h-full rounded-md p-3 sm:rounded-xl sm:p-4"
                        style="background-color: #14b8a6; color: white; border: 1px solid #0d9488;">
                        Go to Total Diet Analysis of National Hospital of Sri Lanka for year {{ $currentMonth->format('Y') ?? 'No Year Selected' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
