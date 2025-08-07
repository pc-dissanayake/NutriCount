<x-filament-panels::page>

@php
    
$page_height =  1414;$page_width =   1000 ;

@endphp
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

    /* .table-frame {
        max-width: 100%;
        overflow-x: auto;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        background: #ffffff;
        padding: 8px;
        margin: 16px 0;
    }

    .dark .table-frame {
        border-color: #374151;
        background: #1f2937;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
    }

    .table-frame::-webkit-scrollbar {
        height: 8px;
    }

    .table-frame::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .dark .table-frame::-webkit-scrollbar-track {
        background: #334155;
    }

    .table-frame::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .table-frame::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .dark .table-frame::-webkit-scrollbar-thumb {
        background: #64748b;
    }

    .dark .table-frame::-webkit-scrollbar-thumb:hover {
        background: #475569;
    } */

        /* Print styles for different paper sizes - customize as needed */
        @media print {
            @page {
                size: B0 portrait; /* Change to A1, A2, A3, A4 as needed */
                margin: 0.2in;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            body {
                font-size: 12px !important; /* Adjust for paper size */
                margin: 0 !important;
                padding: 0 !important;
            }
            
            button, nav {
                display: none !important;
            }
            
            #print-area {
                padding: 0 !important;
                background: white !important;
                margin: 0 !important;
                width: 100% !important;
                height: auto !important;
            }
            
            /* .table-frame {
                border: none !important;
                box-shadow: none !important;
                overflow: visible !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: none !important;
                width: 100% !important;
                background: transparent !important;
                display: block !important;
            } */
            
            table {
                width: 100% !important;
                min-width: none !important;
                max-width: 100% !important;
                border-collapse: collapse !important;
                table-layout: fixed !important; /* Changed to fixed for better control */
                page-break-inside: avoid;
                margin: 0 !important;
                font-size: inherit !important;
            }
            
            th, td {
                border: 1px solid black !important;
                padding: 3px !important; /* Adjust padding for paper size */
                white-space: nowrap !important;
                font-size: 10px !important; /* Adjust font size for paper size */
                overflow: visible !important;
                word-wrap: break-word !important;
                vertical-align: top !important;
            }
            
            /* Ensure rotated headers print correctly */
            th.rotate {
                height: auto !important;
                white-space: normal !important;
                width: auto !important;
            }
            
            th.rotate > div {
                transform: none !important;
                width: auto !important;
                writing-mode: vertical-lr !important;
                text-orientation: mixed !important;
            }
            
            th.rotate > div > span {
                writing-mode: vertical-lr !important;
                text-orientation: mixed !important;
                padding: 2px !important;
                display: block !important;
            }
            
            h2 {
                font-size: 16px !important; /* Adjust for paper size */
                margin: 5px 0 !important;
                text-align: center !important;
            }
            
            /* Custom sizes - uncomment as needed */
            /*
            A1: @page { size: A1 landscape; } body { font-size: 14px !important; } th, td { font-size: 12px !important; }
            A2: @page { size: A2 landscape; } body { font-size: 13px !important; } th, td { font-size: 11px !important; }
            A3: @page { size: A3 landscape; } body { font-size: 11px !important; } th, td { font-size: 9px !important; }
            A4: @page { size: A4 landscape; } body { font-size: 9px !important; } th, td { font-size: 7px !important; }
            */
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
        <button onclick="downloadPDF('print-area')" style="padding: 8px 16px; background-color: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 8px;">Generate PDF</button>
        <button onclick="downloadImage('print-area')" style="padding: 8px 16px; background-color: #328035ff; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Image</button>
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
    <div class="table-frame">
        <table style="border-collapse: collapse; width: 100%; min-width: 800px;" class="p-4">
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
    </div>
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

        // PDF generation using jsPDF and html2canvas
        function downloadPDF(id) {
            // Dynamically load jsPDF if not loaded
            if (typeof jspdf === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
                script.onload = function() {
                    loadHtml2CanvasForPDF(id);
                };
                document.body.appendChild(script);
            } else {
                loadHtml2CanvasForPDF(id);
            }
        }

        function loadHtml2CanvasForPDF(id) {
            // Dynamically load html2canvas if not loaded
            if (typeof html2canvas === 'undefined') {
                const script = document.createElement('script');
                script.src = '{{  asset('js/html2canvas/html2canvas.min.js') }}';
                script.onload = function() {
                    generatePDF(id);
                };
                document.body.appendChild(script);
            } else {
                generatePDF(id);
            }
        }

        function generatePDF(id) {
            const node = document.getElementById(id);
            const { jsPDF } = window.jspdf;

            // Use custom page dimensions from PHP variables - ALWAYS PORTRAIT
            const pageWidth = {{ $page_width }};
            const pageHeight = {{ $page_height }};

            html2canvas(node, {
                backgroundColor: '#fff',
                useCORS: true,
                scale: 2,
                width: node.scrollWidth,
                height: node.scrollHeight
            }).then(function(canvas) {
                const imgData = canvas.toDataURL('image/png');
                
                // Create PDF with custom dimensions in PORTRAIT orientation
                const pdf = new jsPDF('p', 'mm', [pageWidth, pageHeight]);
                
                // Calculate dimensions
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();
                const imgWidth = canvas.width;
                const imgHeight = canvas.height;
                
                // Calculate scaling to fit the page - ensure table fits within margins
                const margin = 10; // 10mm margin on all sides
                const availableWidth = pdfWidth - (margin * 2);
                const availableHeight = pdfHeight - (margin * 2);
                
                const ratioWidth = availableWidth / imgWidth;
                const ratioHeight = availableHeight / imgHeight;
                const ratio = Math.min(ratioWidth, ratioHeight);
                
                const scaledWidth = imgWidth * ratio;
                const scaledHeight = imgHeight * ratio;
                
                // Center the image within available space
                const x = margin + (availableWidth - scaledWidth) / 2;
                const y = margin + (availableHeight - scaledHeight) / 2;
                
                pdf.addImage(imgData, 'PNG', x, y, scaledWidth, scaledHeight);
                
                // Open PDF in new tab instead of downloading
                const pdfBlob = pdf.output('blob');
                const pdfUrl = URL.createObjectURL(pdfBlob);
                
                // Open in new tab
                const newTab = window.open(pdfUrl, '_blank');
                if (!newTab) {
                    alert('Please allow pop-ups for this website to view the PDF.');
                }
                
                // Clean up the object URL after a delay to prevent memory leaks
                setTimeout(() => {
                    URL.revokeObjectURL(pdfUrl);
                }, 10000);
                
            }).catch(function(error) {
                console.error('Error generating PDF:', error);
                alert('Sorry, an error occurred while generating the PDF.');
            });
        }

        // dom2canvas image export using html2canvas
        function downloadImage(id) {
            // Dynamically load html2canvas if not loaded
            if (typeof html2canvas === 'undefined') {
                const script = document.createElement('script');
                script.src = '{{  asset('js/html2canvas/html2canvas.min.js') }}';
                script.onload = function() {
                    captureAndOpen(id);
                };
                document.body.appendChild(script);
            } else {
                captureAndOpen(id);
            }
        }

        function captureAndOpen(id) {
            const win = window.open('', '_blank');
            if (!win) {
                alert('Please allow pop-ups for this website to print the image.');
                return;
            }
            win.document.write('<p>Generating image, please wait...</p>');
            win.document.write('<p>This will take couple of seconds...</p>');

            const node = document.getElementById(id);
            html2canvas(node, {
                backgroundColor: getComputedStyle(document.body).backgroundColor || '#fff',
                useCORS: true,
                scale: 1 // Highest resolution
            }).then(function(canvas) {
                const dataUrl = canvas.toDataURL('image/png');
                win.document.head.innerHTML = '<title>Diet Analysis Image</title>';
                win.document.body.innerHTML = `<img id="diet-img" src="${dataUrl}" style="max-width:100%;height:auto;display:block;margin:10px;background:${document.documentElement.classList.contains('dark') ? '#fff' : '#fff'};"/>`;
                
                const img = win.document.getElementById('diet-img');
                img.onload = function() {
                    win.focus();
                    setTimeout(function() {
                        win.print();
                        // win.close(); // Optional: close the window after printing
                    }, 250); // Increased timeout for stability
                };
                // If the image is already cached and loaded
                if (img.complete) {
                    img.onload();
                }
            }).catch(function(error) {
                console.error('Error generating canvas:', error);
                win.document.body.innerHTML = '<p>Sorry, an error occurred while generating the image.</p>';
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
