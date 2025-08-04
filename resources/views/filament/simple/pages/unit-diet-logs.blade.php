<x-filament-panels::page>
    <nav class="flex items-center justify-between text-sm bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <div class="flex items-center">
            <a href="{{ url('/simple/calender') }}" class="text-blue-500 hover:underline">Home</a>
            <span class="mx-2">&gt;</span>
            <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="text-blue-500 hover:underline">{{ $date ?? 'No Date Selected' }}</a>
            <span class="mx-2">&gt;</span>
            <a href="{{ url('/simple/unit-diet-entry') . '?date=' . urlencode($date) . '&unit_id=' . urlencode(request('unit_id')) }}" class="text-blue-500 hover:underline">
                {{ $units->firstWhere('id', request('unit_id'))->name ?? 'Unknown Unit' }}
            </a>
            <span class="mx-2">&gt;</span>
            <span class="text-gray-500 dark:text-gray-400">
                @if(request('unit_id'))
                    Activity Logs
                @else
                    Activity Logs
                @endif
            </span>
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

    <form method="GET" action="{{ url('/simple/unit-diet-logs') }}" class="space-y-4 bg-gray-100 dark:bg-gray-800 p-4 rounded-xl shadow mt-4">
        <div class="flex items-center gap-4">
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
            <button type="submit" class="filament-button filament-button-primary bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 px-4 py-2 m-2 rounded-xl">
                @if(request('Language') === 'Sin')
                    ලොග් බලන්න
                @elseif(request('Language') === 'Tam')
                    பதிவுகளை பார்க்கவும்
                @else
                    View Logs
                @endif
            </button>
        </div>
    </form>

    @if ($date && request('unit_id'))
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        
        <div class="mt-4 bg-gray-100 dark:bg-gray-800 p-3 rounded-xl">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                @if(request('Language') === 'Sin')
                    ක්‍රියාකාරකම් ලොග්
                @elseif(request('Language') === 'Tam')
                    செயல்பாட்டு பதிவுகள்
                @else
                    Activity Logs
                @endif
            </h2>
            <p class="text-gray-700 dark:text-gray-300 mb-4">
                <strong>
                    @if(request('Language') === 'Sin')
                        දිනය
                    @elseif(request('Language') === 'Tam')
                        தேதி
                    @else
                        Date
                    @endif
                :</strong> {{ $date }}
                <span class="ml-4">
                    <strong>
                        @if(request('Language') === 'Sin')
                            ඒකකය
                        @elseif(request('Language') === 'Tam')
                            பிரிவு
                        @else
                            Unit
                        @endif
                    :</strong> {{ $units->firstWhere('id', request('unit_id'))->name ?? 'Unknown Unit' }}
                </span>
            </p>

            @if(count($logs) > 0)
                <div class="overflow-x-auto">
                    <table id="activityLogTable" class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                    @if(request('Language') === 'Sin')
                                        කාලය
                                    @elseif(request('Language') === 'Tam')
                                        நேரம்
                                    @else
                                        Time
                                    @endif
                                </th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                    @if(request('Language') === 'Sin')
                                        ක්‍රියාව
                                    @elseif(request('Language') === 'Tam')
                                        செயல்
                                    @else
                                        Action
                                    @endif
                                </th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
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
                                        වෙනස්කම්
                                    @elseif(request('Language') === 'Tam')
                                        மாற்றங்கள்
                                    @else
                                        Changes
                                    @endif
                                </th>
                                <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                    @if(request('Language') === 'Sin')
                                        පරිශීලකයා
                                    @elseif(request('Language') === 'Tam')
                                        பயனர்
                                    @else
                                        User
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                            style="
                                                @if($log->description === 'Diet amount created') background-color: #d4edda; color: #155724;
                                                @elseif($log->description === 'Diet amount updated') background-color: #cce5ff; color: #004085;
                                                @elseif($log->description === 'Diet amount deleted') background-color: #f8d7da; color: #721c24;
                                                @else background-color: #e2e3e5; color: #383d41;
                                                @endif">
                                            @if(request('Language') === 'Sin')
                                                ආහාර ප්‍රමාණය {{ $log->description }}
                                            @elseif(request('Language') === 'Tam')
                                                உணவு அளவு {{ $log->description }}
                                            @else
                                                Diet amount {{ $log->description }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                        @if($log->subject && $log->subject->simpleDiet)
                                            @if(request('Language') === 'Sin')
                                                {{ $log->subject->simpleDiet->DietName_si ?? $log->subject->simpleDiet->DietName_en }}
                                            @elseif(request('Language') === 'Tam')
                                                {{ $log->subject->simpleDiet->DietName_tm ?? $log->subject->simpleDiet->DietName_en }}
                                            @else
                                                {{ $log->subject->simpleDiet->DietName_en }}
                                            @endif
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                        @if($log->properties && isset($log->properties['attributes']) && isset($log->properties['old']))
                                            @php
                                                $attributes = $log->properties['attributes'];
                                                $old = $log->properties['old'];
                                            @endphp
                                            @if(isset($attributes['amount']) && isset($old['amount']))
                                                <div class="text-sm">
                                                    <span class="text-red-600 dark:text-red-400">{{ $old['amount'] ?? 'N/A' }}</span>
                                                    <span class="mx-2">→</span>
                                                    <span class="text-green-600 dark:text-green-400">{{ $attributes['amount'] }}</span>
                                                </div>
                                            @elseif(isset($attributes['amount']))
                                                <span class="text-green-600 dark:text-green-400">{{ $attributes['amount'] }}</span>
                                            @endif
                                        @elseif($log->description === 'Diet amount created' && $log->properties && isset($log->properties['attributes']))
                                            <span class="text-green-600 dark:text-green-400">
                                                @if(request('Language') === 'Sin')
                                                    නව ප්‍රමාණය: {{ $log->properties['attributes']['amount'] ?? 'N/A' }}
                                                @elseif(request('Language') === 'Tam')
                                                    புதிய அளவு: {{ $log->properties['attributes']['amount'] ?? 'N/A' }}
                                                @else
                                                    New amount: {{ $log->properties['attributes']['amount'] ?? 'N/A' }}
                                                @endif
                                            </span>
                                        @elseif($log->description === 'Diet amount updated' && $log->properties && isset($log->properties['attributes']['amount']) && isset($log->properties['old']['amount']))
                                            <div class="text-sm">
                                                <span class="text-red-600 dark:text-red-400">{{ $log->properties['old']['amount'] }}</span>
                                                <span class="mx-2">→</span>
                                                <span class="text-green-600 dark:text-green-400">{{ $log->properties['attributes']['amount'] }}</span>
                                            </div>
                                        @elseif($log->description === 'Diet amount deleted' && $log->properties && isset($log->properties['old']['amount']))
                                            <span class="text-red-600 dark:text-red-400">
                                                @if(request('Language') === 'Sin')
                                                    ඉවත් කළ ප්‍රමාණය: {{ $log->properties['old']['amount'] }}
                                                @elseif(request('Language') === 'Tam')
                                                    நீக்கப்பட்ட அளவு: {{ $log->properties['old']['amount'] }}
                                                @else
                                                    Deleted amount: {{ $log->properties['old']['amount'] }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-gray-900 dark:text-gray-100">
                                        {{ $log->causer->name ?? 'System' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    @if(request('Language') === 'Sin')
                        මේ දිනය සහ ඒකකය සඳහා ක්‍රියාකාරකම් ලොග් නොමැත
                    @elseif(request('Language') === 'Tam')
                        இந்த தேதி மற்றும் பிரிவுக்கு செயல்பாட்டு பதிவுகள் இல்லை
                    @else
                        No activity logs found for this date and unit
                    @endif
                </div>
            @endif

            <div class="mt-4 flex gap-2">
                <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="filament-button filament-button-primary bg-gray-500 text-white hover:bg-gray-600 focus:ring-gray-500 px-4 py-2 rounded-xl">
                    @if(request('Language') === 'Sin')
                        ආපසු
                    @elseif(request('Language') === 'Tam')
                        பின்செல்
                    @else
                        Back to Unit
                    @endif
                </a>
                <a href="{{ url('/simple/unit-diet-entry') . '?date=' . urlencode($date) . '&unit_id=' . urlencode(request('unit_id')) }}" class="filament-button filament-button-primary bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500 px-4 py-2 rounded-xl">
                    @if(request('Language') === 'Sin')
                        ආහාර දත්ත සංස්කරණය
                    @elseif(request('Language') === 'Tam')
                        உணவு தரவு திருத்து
                    @else
                        Edit Diet Data
                    @endif
                </a>
            </div>
        </div>
    @endif
</x-filament-panels::page>

<script src="{{ asset('js/dataTables/jquery-3.7.1.js') }}"></script>
<script src="{{ asset('js/dataTables/dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = $('#activityLogTable').DataTable({
            "paging": false, // Disable pagination
            "searching": false, // Disable global search
            "info": false, // Disable table information
            "columnDefs": [
                { "orderable": false, "targets": [3] }, // Disable sorting on the "Changes" column
                { "searchable": true, "targets": [4, 2] }, // Enable filtering for User and Diet Name columns
                { "searchable": false, "targets": "_all" } // Disable filtering for all other columns
            ]
        });

        // Add column-specific filters for User and Diet Name
        table.columns([4, 2]).every(function(colIdx) {
            var column = this;
            var select = $('<select><option value="">Filter</option></select>')
                .appendTo($(column.footer()).empty())
                .on('change', function() {
                    column
                        .search($(this).val())
                        .draw();
                });

            column
                .cache('search')
                .sort()
                .unique()
                .each(function(d) {
                    select.append($('<option value="' + d + '">' + d + '</option>'));
                });
        });
    });
</script>