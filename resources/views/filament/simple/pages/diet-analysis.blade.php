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
        <a href="{{ url('/simple/calender') }}" class="text-blue-500 hover:underline">Home</a>
        <span class="mx-2">&gt;</span>
        @php
            $isDisabled = false;
            $hasDate = request()->has('date');
            $hasMonth = request()->has('month');
            $hasYear = request()->has('year');
            $canExport = false;
            $canView = false;
            
            // Check permissions based on what data is being viewed
            if ($hasDate) {
                $canExport = Auth::user() && userHasPermission(Auth::user(), 'export.daily_diet_analysis_calender_simple-panel');
                $canView = Auth::user() && userHasPermission(Auth::user(), 'view.daily_diet_analysis_calender_simple-panel');
            } elseif ($hasMonth) {
                $canExport = Auth::user() && userHasPermission(Auth::user(), 'export.monthly_diet_analysis_calender_simple-panel');
                $canView = Auth::user() && userHasPermission(Auth::user(), 'view.monthly_diet_analysis_calender_simple-panel');
            } elseif ($hasYear) {
                $canExport = Auth::user() && userHasPermission(Auth::user(), 'export.yearly_diet_analysis_calender_simple-panel');
                $canView = Auth::user() && userHasPermission(Auth::user(), 'view.yearly_diet_analysis_calender_simple-panel');
            }
            
            if ($hasYear || $hasMonth) {
                $isDisabled = true;
            }
        @endphp
        @if ($isDisabled)
            <span class="text-gray-500 dark:text-gray-400 cursor-not-allowed">{{ $date ?? 'No Date Selected' }}</span>
        @else
            <a href="{{ url('/simple/unit') . '?date=' . urlencode($date) }}" class="text-blue-500 hover:underline">{{ $date ?? 'No Date Selected' }}</a>
        @endif
        <span class="mx-2">&gt;</span>
        <span class="text-gray-500 dark:text-gray-400">NHSL Total Diet</span>
    </nav>

    @if($canView && $canExport)
    <div style="text-align: right; margin-bottom: 10px;">
        <!-- <button onclick="downloadImage('print-area')" style="padding: 8px 16px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">Print</button> -->
        <button onclick="downloadImage('print-area')" style="padding: 8px 16px; background-color: #328035ff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 8px;">Print </button>
    </div>
    @endif

    <div id="print-area" style="background: #fff; padding: 2%;">
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
                    <td style="border: 1px solid black; padding: 8px;"
                    dietType="{{ $dietType->id }}"
                    >
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
                <td style="border: 1px solid black; padding: 8px; font-weight: bold;"
                dietType="{{ $dietType->id }}" dietType-active="{{ $dietType->active ? 'true' : 'false' }}" class="total-row"
                >
                    {{ $columnTotal }}
                </td>
            @endforeach
            <td style="border: 1px solid black; padding: 8px; font-weight: bold; display: none;">-</td>
        </tr>
        </tbody>
    </table>
 <br /> 
    <div class="text-right text-gray-700 dark:text-gray-300 text-sm mt-2">
        Generated at: {{ now('Asia/Colombo')->format('Y-m-d H:i:s') }}. © National Hospital of Sri Lanka
    </div>
        <br />    <br />   

    </div>

    @if($canView && $canExport)
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
                scale: 4 // Highest resolution
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

        // Remove inactive diet columns with zero totals after page load
        function removeInactiveDietColumns() {
            const totalRows = document.querySelectorAll('.total-row');
            const columnsToRemove = [];

            totalRows.forEach((cell, index) => {
                const isInactive = cell.getAttribute('diettype-active') === 'false';
                const totalValue = parseFloat(cell.textContent.trim()) || 0;
                
                if (isInactive && totalValue === 0) {
                    columnsToRemove.push(index + 1); // +1 because first column is "Unit"
                }
            });

            // Remove columns from all rows (headers and data)
            columnsToRemove.reverse().forEach(columnIndex => {
                const table = document.querySelector('table');
                if (table) {
                    // Remove header cell
                    const headerRow = table.querySelector('thead tr');
                    if (headerRow && headerRow.children[columnIndex]) {
                        headerRow.children[columnIndex].remove();
                    }
                    
                    // Remove data cells from all body rows
                    const bodyRows = table.querySelectorAll('tbody tr');
                    bodyRows.forEach(row => {
                        if (row.children[columnIndex]) {
                            row.children[columnIndex].remove();
                        }
                    });
                }
            });
        }

        // Execute after page load
        document.addEventListener('DOMContentLoaded', function() {
            removeInactiveDietColumns();
        });
    </script>
    @endif

    @if(!$canView)
    <style>
        body {
            pointer-events: none !important;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }
        * {
            pointer-events: none !important;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }
    </style>
    <script>
        // Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });

        // Disable text selection
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
            return false;
        });

        // Disable drag and drop
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
            return false;
        });

        // Disable keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S, Ctrl+A, Ctrl+P, Ctrl+C
            if (e.keyCode === 123 || 
                (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) ||
                (e.ctrlKey && (e.keyCode === 85 || e.keyCode === 83 || e.keyCode === 65 || e.keyCode === 80 || e.keyCode === 67))) {
                e.preventDefault();
                return false;
            }
        });

        // Disable all click events
        document.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, true);

        // Show access denied message
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.createElement('div');
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.8);
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 24px;
                z-index: 9999;
                pointer-events: all !important;
            `;
            overlay.innerHTML = '<div>Access Denied - You do not have permission to view this content</div>';
            document.body.appendChild(overlay);
        });
    </script>
    @endif


</x-filament-panels::page>
