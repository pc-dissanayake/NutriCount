<x-filament-panels::page>

@php
    
// A3 page dimensions in millimeters: 297 × 420 mm
$page_width = 297;
$page_height = 420;

@endphp

<!-- Breadcrumb System -->
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
        <button onclick="exportToExcel('print-area')" style="padding: 8px 16px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Export to Excel</button>
    </div>
    @endif


<div id="print-area" class="overflow-x-auto" 
    @class([
        'dark:bg-gray-900',
        'bg-white',
        'p-4','rounded-xl','shadow','transition-colors'
    ])
>
    <style>
        /* PDF optimization styles */
        @media print, screen {
            #print-area table {
                font-size: 11px !important;
                border-collapse: collapse !important;
            }
            #print-area th, #print-area td {
                padding: 4px 6px !important;
                font-size: 10px !important;
                border: 1px solid #000 !important;
            }
            #print-area th {
                font-weight: bold !important;
                background-color: #f5f5f5 !important;
            }
            #print-area .rotate div span {
                font-size: 9px !important;
                white-space: nowrap !important;
            }
            #print-area h2 {
                font-size: 14px !important;
                margin: 8px 0 !important;
                line-height: 1.2 !important;
            }
        }
    </style>
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
    <div class="table-frame overflow-x-auto">
        <table class="p-4 w-full min-w-[1080px] border-collapse">
        <thead>
            <tr>
                <th class="border border-black p-2">Unit</th>
                @foreach ($dietTypes as $dietType)
                    <th class="border border-black p-2 rotate">
                        <div><span>{{ $dietType->DietName_en }} ({{ $dietType->primary_amount_unit }})</span></div>
                    </th>
                @endforeach
                <th class="hidden">Total</th>
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
                <td class="border border-black p-2">{{ $unit->name }}</td>
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
                    <td class="border border-black p-2" dietType="{{ $dietType->id }}">
                        {{ $amount }}
                    </td>
                @endforeach
                <td class="hidden">{{ $unitTotal }}</td>
            </tr>
        @endforeach
        <tr>
            <td class="border border-black p-2 font-bold">Total</td>
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
                <td class="border border-black p-2 font-bold total-row" dietType="{{ $dietType->id }}" dietType-active="{{ $dietType->active ? 'true' : 'false' }}">
                    {{ $columnTotal }}
                </td>
            @endforeach
            <td class="hidden">-</td>
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
        if (typeof window.jspdf === 'undefined') {
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
        
        // Check if jsPDF is available
        if (!window.jspdf) {
            alert('PDF library not loaded. Please try again.');
            return;
        }
        
        const { jsPDF } = window.jspdf;

        // Show loading message
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'pdf-loading';
        loadingDiv.style.cssText = `
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8); color: white; padding: 20px;
            border-radius: 8px; z-index: 10000; font-size: 16px;
        `;
        loadingDiv.innerHTML = 'Generating PDF... Please wait.';
        document.body.appendChild(loadingDiv);

        // Temporarily adjust styles for better PDF rendering
        const originalOverflow = node.style.overflow;
        const table = node.querySelector('table');
        const originalTableStyles = {};
        
        // Store and apply temporary styles
        if (table) {
            originalTableStyles.width = table.style.width;
            originalTableStyles.fontSize = table.style.fontSize;
            originalTableStyles.minWidth = table.style.minWidth;
            
            table.style.width = '100%';
            table.style.fontSize = '8px';
            table.style.minWidth = 'auto';
        }
        
        // Apply temporary styles to cells
        const cells = node.querySelectorAll('th, td');
        const originalCellStyles = [];
        cells.forEach((cell, index) => {
            originalCellStyles[index] = {
                padding: cell.style.padding,
                fontSize: cell.style.fontSize
            };
            cell.style.padding = '3px';
            cell.style.fontSize = '8px';
        });

        node.style.overflow = 'visible';

        html2canvas(node, {
            backgroundColor: '#ffffff',
            useCORS: true,
            scale: 2,
            width: node.scrollWidth,
            height: node.scrollHeight,
            allowTaint: true,
            logging: true,
            scrollX: 0,
            scrollY: 0,
            windowWidth: document.documentElement.offsetWidth,
            windowHeight: document.documentElement.offsetHeight,
            onclone: function(clonedDoc) {
                const clonedNode = clonedDoc.getElementById(id);
                if (clonedNode) {
                    // Make sure table is fully visible in cloned document
                    const table = clonedNode.querySelector('table');
                    if (table) {
                        table.style.width = table.scrollWidth + 'px';
                        table.style.height = 'auto';
                        table.style.overflow = 'visible';
                        table.style.display = 'table';
                        table.style.tableLayout = 'fixed';
                    }
                    
                    // Make all columns and rows visible
                    const tableFrame = clonedNode.querySelector('.table-frame');
                    if (tableFrame) {
                        tableFrame.style.overflow = 'visible';
                        tableFrame.style.width = 'auto';
                        tableFrame.style.maxWidth = 'none';
                    }

                    // Ensure all cells are properly sized
                    const cells = clonedNode.querySelectorAll('th, td');
                    cells.forEach(cell => {
                        cell.style.whiteSpace = 'normal';
                        cell.style.overflow = 'visible';
                    });
                }
            }
        }).then(function(canvas) {
            try {
                const imgData = canvas.toDataURL('image/png', 0.9);
                
                // Create PDF with A3 landscape orientation
                const pdf = new jsPDF('l', 'mm', 'a3');
                
                // A3 landscape dimensions: 420mm x 297mm
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();
                const imgWidth = canvas.width;
                const imgHeight = canvas.height;
                
                // Calculate scaling with minimal margins
                const margin = 8;
                const availableWidth = pdfWidth - (margin * 2);
                const availableHeight = pdfHeight - (margin * 2);
                
                // If the table is very wide, use multiple pages
                const tableWidth = node.querySelector('table')?.scrollWidth || imgWidth;
                const isTableWider = tableWidth > 1600; // If table is very wide
                
                // Adjust ratio calculation based on content
                let ratio, scaledWidth, scaledHeight;
                
                if (isTableWider) {
                    // Use landscape orientation and fit to width
                    ratio = availableWidth / imgWidth;
                    // Allow a bit more vertical space for very wide tables
                    scaledWidth = imgWidth * ratio;
                    scaledHeight = imgHeight * ratio;
                    
                    // If height is too large, reduce ratio slightly
                    if (scaledHeight > availableHeight * 1.5) {
                        ratio = (availableHeight * 1.5) / imgHeight;
                        scaledWidth = imgWidth * ratio;
                        scaledHeight = imgHeight * ratio;
                    }
                } else {
                    // Normal calculation for standard content
                    const ratioWidth = availableWidth / imgWidth;
                    const ratioHeight = availableHeight / imgHeight;
                    ratio = Math.min(ratioWidth, ratioHeight);
                    
                    scaledWidth = imgWidth * ratio;
                    scaledHeight = imgHeight * ratio;
                }
                
                // Center the content
                const x = margin + (availableWidth - scaledWidth) / 2;
                const y = margin + (availableHeight - scaledHeight) / 2;
                
                pdf.addImage(imgData, 'PNG', x, y, scaledWidth, scaledHeight, '', 'FAST');
                
                // Open PDF in new tab
                const pdfBlob = pdf.output('blob');
                const pdfUrl = URL.createObjectURL(pdfBlob);
                
                const newTab = window.open(pdfUrl, '_blank');
                if (!newTab) {
                    alert('Please allow pop-ups for this website to view the PDF.');
                }
                
                // Clean up
                setTimeout(() => {
                    URL.revokeObjectURL(pdfUrl);
                }, 15000);
                
            } catch (error) {
                console.error('PDF generation error:', error);
                alert('Error generating PDF. Please try again.');
            }
            
            // Restore all original styles
            node.style.overflow = originalOverflow;
            if (table) {
                table.style.width = originalTableStyles.width;
                table.style.fontSize = originalTableStyles.fontSize;
                table.style.minWidth = originalTableStyles.minWidth;
            }
            
            cells.forEach((cell, index) => {
                if (originalCellStyles[index]) {
                    cell.style.padding = originalCellStyles[index].padding;
                    cell.style.fontSize = originalCellStyles[index].fontSize;
                }
            });
            
            // Remove loading message
            const loading = document.getElementById('pdf-loading');
            if (loading) {
                loading.remove();
            }
            
        }).catch(function(error) {
            console.error('Canvas generation error:', error);
            alert('Error generating PDF canvas. Please try again.');
            
            // Restore styles on error
            node.style.overflow = originalOverflow;
            if (table) {
                table.style.width = originalTableStyles.width;
                table.style.fontSize = originalTableStyles.fontSize;
                table.style.minWidth = originalTableStyles.minWidth;
            }
            
            cells.forEach((cell, index) => {
                if (originalCellStyles[index]) {
                    cell.style.padding = originalCellStyles[index].padding;
                    cell.style.fontSize = originalCellStyles[index].fontSize;
                }
            });
            
            // Remove loading message
            const loading = document.getElementById('pdf-loading');
            if (loading) {
                loading.remove();
            }
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
            win.document.body.innerHTML = `<div><img id="diet-img" src="${dataUrl}" class="max-w-full h-auto block m-2 bg-white"/>`;
            
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

    // Excel export functionality
    function exportToExcel(id) {
        const node = document.getElementById(id);
        if (!node) {
            alert('Content not found');
            return;
        }
        
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'excel-loading';
        loadingDiv.style.cssText = `
            position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.8); color: white; padding: 20px;
            border-radius: 8px; z-index: 10000; font-size: 16px;
        `;
        loadingDiv.innerHTML = 'Generating Excel file... Please wait.';
        document.body.appendChild(loadingDiv);
        
        try {
            // Dynamically load SheetJS library if needed
            if (typeof XLSX === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js';
                script.onload = function() {
                    generateExcel(id, loadingDiv);
                };
                script.onerror = function() {
                    alert('Failed to load Excel export library. Please try again later.');
                    loadingDiv.remove();
                };
                document.body.appendChild(script);
            } else {
                generateExcel(id, loadingDiv);
            }
        } catch (e) {
            console.error('Excel export error:', e);
            alert('Error generating Excel file: ' + e.message);
            loadingDiv.remove();
        }
    }
    
    function generateExcel(id, loadingDiv) {
        const table = document.getElementById(id).querySelector('table');
        if (!table) {
            alert('Table not found');
            loadingDiv.remove();
            return;
        }
        
        try {
            // Get report title
            const title = document.querySelector('h2')?.textContent || 'Diet Analysis Report';
            
            // Prepare the workbook
            const wb = XLSX.utils.book_new();
            wb.Props = {
                Title: "Diet Analysis Report",
                Subject: "Diet Analysis",
                Author: "NutriCount",
                CreatedDate: new Date()
            };
            
            // Extract table data
            const data = [];
            
            // Add header row
            const headerRow = [];
            const headerCells = table.querySelectorAll('thead tr th');
            headerCells.forEach(cell => {
                // Get text content and clean it up
                let text = cell.textContent.trim();
                // For rotated headers, extract the text from the span
                const span = cell.querySelector('div span');
                if (span) {
                    text = span.textContent.trim();
                }
                headerRow.push(text);
            });
            data.push(headerRow);
            
            // Add data rows
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const rowData = [];
                const cells = row.querySelectorAll('td');
                cells.forEach(cell => {
                    // Ensure numeric values are exported as numbers
                    let value = cell.textContent.trim();
                    if (!isNaN(parseFloat(value)) && isFinite(value)) {
                        value = parseFloat(value);
                    }
                    rowData.push(value);
                });
                data.push(rowData);
            });
            
            // Create worksheet
            const ws = XLSX.utils.aoa_to_sheet(data);
            
            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, "Diet Analysis");
            
            // Apply some styling (limited support in SheetJS)
            ws['!cols'] = headerRow.map(() => ({ wch: 15 })); // Set column width
            
            // Generate Excel file
            const wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });
            
            // Convert to Blob and download
            function s2ab(s) {
                const buf = new ArrayBuffer(s.length);
                const view = new Uint8Array(buf);
                for (let i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }
            
            const blob = new Blob([s2ab(wbout)], {type: 'application/octet-stream'});
            const filename = 'Diet_Analysis_' + new Date().toISOString().split('T')[0] + '.xlsx';
            
            // Create download link
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            
            // Cleanup
            setTimeout(() => {
                document.body.removeChild(a);
                URL.revokeObjectURL(a.href);
                loadingDiv.remove();
            }, 100);
            
        } catch (e) {
            console.error('Excel generation error:', e);
            alert('Error generating Excel file: ' + e.message);
            loadingDiv.remove();
        }
    }

    // Execute after page load
    document.addEventListener('DOMContentLoaded', function() {
        removeInactiveDietColumns();
    });
</script>
@endif

@if(!$canView)
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
