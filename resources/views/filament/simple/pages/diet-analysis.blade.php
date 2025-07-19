<x-filament-panels::page>
<style>

    th.rotate {
  /* Something you can count on */
  height: 240px;
  white-space: nowrap;
}

th.rotate > div {
  transform: 
    /* Magic Numbers */
    translate(5px, 100px)
    /* 45 is really 360 - 45 */
    rotate(270deg);
  width: 30px;
}
th.rotate > div > span {
  padding: 1px 5px;
}


</style>
    <!-- Breadcrumb System -->
    <nav class="text-sm  bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <a href="{{ url('/simple') }}" class="text-blue-500 hover:underline">Home</a>
        <span class="mx-2">&gt;</span>
        <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="text-blue-500 hover:underline">{{ $date ?? 'No Date Selected' }}</a>
        <span class="mx-2">&gt;</span>
        <span class="text-gray-500 dark:text-gray-400">NHSL Total Diet</span>
    </nav>

    <div style="text-align: right; margin-bottom: 10px;">
        <button onclick="printContent('print-area')" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Print</button>
    </div>

    <div id="print-area">

    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Analysis of Diets of the Government Hospital at National Hospital of Sri Lanka on {{ $date ?? 'No Date Selected' }}</h2>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="border: 1px solid black; padding: 8px;">Unit</th>
                @foreach ($dietTypes as $dietType)
                    <th style="border: 1px solid black; padding: 8px;" class="rotate">
                        <div><span>{{ $dietType->DietName_en }} ({{ $dietType->primary_amount_unit }})</span></div></th>
                @endforeach
                <th style="border: 1px solid black; padding: 8px; display: none;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($units as $unit)
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">{{ $unit->name }}</td>
                    @php
                        $unitTotal = 0;
                    @endphp
                    @foreach ($dietTypes as $dietType)
                        @php
                            $amount = $dietData->where('hospital_unit_id', $unit->id)->where('simple_diet_id', $dietType->id)->first()->amount ?? 0;
                            $unitTotal += $amount;
                        @endphp
                        <td style="border: 1px solid black; padding: 8px;">
                            {{ $amount }}
                        </td>
                    @endforeach
                    <td style="border: 1px solid black; padding: 8px; display: none;">{{ $unitTotal }}</td>
                </tr>
            @endforeach
            <tr>
                <td style="border: 1px solid black; padding: 8px; font-weight: bold;">Total</td>
                @foreach ($dietTypes as $dietType)
                    @php
                        $columnTotal = $dietData->where('simple_diet_id', $dietType->id)->sum('amount');
                    @endphp
                    <td style="border: 1px solid black; padding: 8px; font-weight: bold;">
                        {{ $columnTotal }}
                    </td>
                @endforeach
                <td style="border: 1px solid black; padding: 8px; font-weight: bold; display: none;">-</td>
            </tr>
        </tbody>
    </table>
    </div>

    <script>
        function printContent(id) {
            const printArea = document.getElementById(id).innerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = printArea;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>

    <style>
        @media print {
            button {
                display: none;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
            }
        }
    </style>
</x-filament-panels::page>
