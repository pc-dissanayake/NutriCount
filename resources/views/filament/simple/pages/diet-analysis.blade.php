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
    </style>    <!-- Breadcrumb System -->
    <nav class="text-sm  bg-gray-100 dark:bg-gray-800 p-3 rounded-full">
        <a href="{{ url('/simple') }}" class="text-blue-500 hover:underline">Home</a>
        <span class="mx-2">&gt;</span>
        <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="text-blue-500 hover:underline">{{ $date ?? 'No Date Selected' }}</a>
        <span class="mx-2">&gt;</span>
        <span class="text-gray-500 dark:text-gray-400">NHSL Total Diet</span>
    </nav>

    <div style="text-align: right; margin-bottom: 10px;">
        <button onclick="printContent('print-area')" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Print</button>
        <button onclick="downloadImage('print-area')" style="padding: 8px 16px; background-color: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 8px;">Print as Image</button>
    </div>

    <div id="print-area">
    <br />    <br />   
    <h2 class="text-xl font-bold m-4 text-gray-900 dark:text-gray-100 text-center">
        @php
            $periodLabel = 'No Date Selected';
            if (isset($date)) {
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                    $periodLabel = 'on ' . $date;
                } elseif (preg_match('/^\d{4}-\d{2}$/', $date)) {
                    $periodLabel = 'for ' . date('F Y', strtotime($date . '-01'));
                } elseif (preg_match('/^\d{4}$/', $date)) {
                    $periodLabel = 'for the year ' . $date;
                }
            }
        @endphp
        Analysis of Diets of the Government Hospital at National Hospital of Sri Lanka {{ $periodLabel }}
    </h2>
    <br />    <br />   
    <table style="border-collapse: collapse; width: 100%;" class="p-4">
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
            @php
            $unitSteps = [
                // Mass units
                'mcg' => 0.001, 'mg' => 0.001, 'g' => 0.1, 'kg' => 0.001, 'oz' => 0.01, 'lb' => 0.01, 'st' => 0.01,
                // Fluid/volume units
                'ml' => 0.1, 'l' => 0.001, 'tsp' => 0.25, 'tbsp' => 0.25, 'fl oz' => 0.01, 'cup' => 0.01, 'pt' => 0.01, 'qt' => 0.01, 'gal' => 0.01,
                // Other common units
                'piece' => 1, 'serving' => 1, 'slice' => 1, 'portion' => 1, 'drop' => 1, 'pinch' => 1, 'sheet' => 1, 'package' => 1, 'container' => 1, 'can' => 1, 'bottle' => 1, 'jar' => 1, 'bag' => 1, 'box' => 1, 'bar' => 1, 'packet' => 1, 'tube' => 1, 'unit' => 1,
            ];
        @endphp
        @foreach ($units as $unit)
            <tr>
                <td style="border: 1px solid black; padding: 8px;">{{ $unit->name }}</td>
                @php
                    $unitTotal = 0;
                @endphp
                @foreach ($dietTypes as $dietType)
                    @php
                        // Sum all amounts for this unit and dietType
                        $amount = $dietData->where('hospital_unit_id', $unit->id)->where('simple_diet_id', $dietType->id)->sum('amount');
                        if ($dietType->multiply_values) {
                            $amount *= $dietType->primary_amount_value;
                        }
                        $step = $unitSteps[$dietType->primary_amount_unit] ?? 0.001;
                        $decimals = strpos($step, '.') !== false ? strlen(explode('.', (string)$step)[1]) : 0;
                        $amount = round($amount, $decimals);
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
                    if ($dietType->multiply_values) {
                        $columnTotal *= $dietType->primary_amount_value;
                    }
                    $step = $unitSteps[$dietType->primary_amount_unit] ?? 0.001;
                    $decimals = strpos($step, '.') !== false ? strlen(explode('.', (string)$step)[1]) : 0;
                    $columnTotal = round($columnTotal, $decimals);
                @endphp
                <td style="border: 1px solid black; padding: 8px; font-weight: bold;">
                    {{ $columnTotal }}
                </td>
            @endforeach
            <td style="border: 1px solid black; padding: 8px; font-weight: bold; display: none;">-</td>
        </tr>
        </tbody>
    </table>
 <br /> 
    <div class="text-right text-gray-700 dark:text-gray-300 text-sm mt-2">
        Printed on: {{ now('Asia/Colombo')->format('Y-m-d H:i:s') }}. © National Hospital of Sri Lanka
    </div>
        <br />    <br />   

    </div>

    <script>
        function printContent(id) {
            const printArea = document.getElementById(id).innerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = printArea;
            window.print();
            document.body.innerHTML = originalContent;
        }
        // dom2canvas image export using html2canvas
        function downloadImage(id) {
            // Dynamically load html2canvas if not loaded
            if (typeof html2canvas === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js';
                script.onload = function() {
                    captureAndOpen(id);
                };
                document.body.appendChild(script);
            } else {
                captureAndOpen(id);
            }
        }

        function captureAndOpen(id) {
            const node = document.getElementById(id);
            html2canvas(node, {
                backgroundColor: getComputedStyle(document.body).backgroundColor || '#fff',
                useCORS: true,
                scale: window.devicePixelRatio
            }).then(function(canvas) {
                const dataUrl = canvas.toDataURL('image/png');
                const win = window.open();
                // Write a script that prints after the image loads
                win.document.write(`
                    <title>Diet Analysis Image</title>
                    <img id="diet-img" src="${dataUrl}" style="max-width:100%;height:auto;display:block;margin:10px;background:${document.documentElement.classList.contains('dark') ? '#fff' : '#fff'};"/>
                    <script>
                        const img = document.getElementById('diet-img');
                        if (img) {
                            img.onload = function() {
                                window.focus();
                                setTimeout(function() { window.print(); }, 100);
                            };
                        } else {
                            window.print();
                        }
                    <\/script>
                `);
                win.document.close();
            });
        }
    </script>


</x-filament-panels::page>
