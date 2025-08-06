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
            <a href="?month={{ $prevMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700">&larr; Previous</a>
            <span class="text-xl font-bold">{{ $currentMonth->format('F Y') }}</span>
            <a href="?month={{ $nextMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700">Next &rarr;</a>
        </div>
        {{-- <div class="mb-2 text-sm text-gray-700 dark:text-gray-300">
            Current Month: <span class="font-semibold">{{ $currentMonthName }}</span> |
            Month Number: <span class="font-semibold">{{ $currentMonthNumber }}</span> |
            Year: <span class="font-semibold">{{ $currentYear }}</span> |
            Current Date: <span class="font-semibold">{{ $currentDate }}</span>
        </div> --}}
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
                    $hasData = in_array($dateStr, $hospitalUnitDietAmounts);
                    $isToday = $day->isToday();
                    $isFutureDate = $day->isFuture();
                    $bgColor = '';
                    if ($isToday) {
                        // Today (with or without data)
                        $bgColor = 'background-color: #FF3C00; color: #000; border: 3px solid #000;'; // cyan, black text, teal border
                    } elseif ($isFutureDate) {
                        // Future (with or without data)
                            $bgColor = 'background-color: #DDDDD9; color: #fff;'; // dark orange, white text
                    } elseif ($hasData) {
                        // Past with data
                        $bgColor = 'background-color: #20E680; color: #000;'; // light green, dark text
                    } else {
                        // Past without data
                        $bgColor = 'background-color: #E6E31E; color: #000;'; // light gray, gray text
                    }
                @endphp
                <div class="text-center p-1 rounded"
                     style="{{ $bgColor }}">
                    @if ($isFutureDate)
                        <span class="text-gray-500">{{ $day->day }}</span>
                    @else
                        <a href="{{ url('/simple/unit?date=' . $dateStr) }}" class="hover:underline" style="color:inherit;">{{ $day->day }}</a>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Calendar Legend -->
        <div class="mt-6 p-4 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">Calendar Legend</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #FF3C00; border: 2px solid #000;"></div>
                    <span class="text-gray-700 dark:text-gray-300">Today</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #20E680;"></div>
                    <span class="text-gray-700 dark:text-gray-300">Past - Has Data</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #E6E31E;"></div>
                    <span class="text-gray-700 dark:text-gray-300">Past - No Data available</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded" style="background-color: #DDDDD9;"></div>
                    <span class="text-gray-700 dark:text-gray-300">Future Dates</span>
                </div>
            </div>
        </div>
        <br />
         <div class="flex items-center justify-between mb-4">
            <a href="?month={{ $prevMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700">&larr; </a>
            <span class="text-xl font-bold"></span>
            <a href="?month={{ $nextMonth }}" class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded hover:bg-gray-300 dark:hover:bg-gray-700"> &rarr;</a>
        </div>
        
        <!-- Diet Analysis Quick Links -->
        @if( userHasPermission(Auth::user(), 'view.monthly_diet_analysis_calender_simple-panel') || userHasPermission(Auth::user(), 'view.yearly_diet_analysis_calender_simple-panel'))
        <div class="bg-gray-200 dark:bg-gray-900 p-6 sm:p-10 md:p-16 mt-20 rounded-xl">
            <div class="container mx-auto m-4 p-4">
            <!-- Add link to Diet Analysis for selected month -->
            <div class="mb-4">
                @if(Auth::user() && userHasPermission(Auth::user(), 'view.monthly_diet_analysis_calender_simple-panel'))
                <a href="{{ url('/simple/diet-analysis') . '?month=' . urlencode($currentMonth->format('Y-m')) }}"
                class="fi-btn fi-btn-primary h-full rounded-md p-3 sm:rounded-xl sm:p-4"
                style="background-color: #f44336; color: white; border: 1px solid #af0c00;">
                Go to Total Diet Analysis of {{ config('app.hospital_name') }} for the month {{ $currentMonth->format('F Y') ?? 'No Month Selected' }}
                </a>
                @endif
            </div>
            <br />
            <!-- Add link to Diet Analysis for selected year -->
            <div>
                @if(Auth::user() && userHasPermission(Auth::user(), 'view.yearly_diet_analysis_calender_simple-panel'))<a href="{{ url('/simple/diet-analysis') . '?year=' . urlencode($currentMonth->format('Y')) }}"
                class="fi-btn fi-btn-primary h-full rounded-md p-3 sm:rounded-xl sm:p-4"
                style="background-color: #f44336; color: white; border: 1px solid #af0c00;">
                Go to Total Diet Analysis of {{ config('app.hospital_name') }} for the year {{ $currentMonth->format('Y') ?? 'No Year Selected' }}
                </a>@endif
            </div>
        </div>
        </div>
        @endif
    </div>
</x-filament-panels::page>
