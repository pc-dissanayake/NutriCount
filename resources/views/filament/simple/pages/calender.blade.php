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
            <a href="?month={{ $prevMonth }}" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">&larr; Prev</a>
            <span class="text-xl font-bold">{{ $currentMonth->format('F Y') }}</span>
            <a href="?month={{ $nextMonth }}" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300">Next &rarr;</a>
        </div>
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
        {{-- <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2">All Unique Dates</h2>
            <ul>
                @foreach ($uniqueDates as $date)
                    <li>
                        <a href="{{ url('/simple/unit?date=' . $date) }}" class="text-blue-700 hover:underline">{{ $date }}</a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
    </div>
</x-filament-panels::page>
