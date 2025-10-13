<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>NutriCount - Diet Distribution Report</title>
        <style>
            @page {
                size: A3 landscape;
                margin: 15mm;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
            
            body {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
                background: white !important;
                color: black !important;
                font-size: 20pt;
                line-height: 2.0;
            }
            
            .a3-page {
                width: 420mm;  /* A3 landscape width */
                height: auto;  /* Let height adjust to content */
                min-height: 297mm;  /* A3 landscape height minimum */
                margin: 0 auto;
                background: white !important;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                padding: 20mm;
                box-sizing: border-box;
                position: relative;
            }
            
            .print-button {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                background: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            }
            
            .print-button:hover {
                background: #0056b3;
            }
            
            .header {
                text-align: center;
                border-bottom: 2px solid #333;
                padding-bottom: 10mm;
                margin-bottom: 15mm;
            }
            
            .header h1 {
                margin: 0;
                font-size: 28pt;
                font-weight: bold;
                color: #333;
            }
            
            .header h2 {
                margin: 5mm 0 0 0;
                font-size: 18pt;
                color: #666;
            }
            
            .content-section {
                margin-bottom: 20mm;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            
            .section-title {
                font-size: 16pt;
                font-weight: bold;
                color: #333;
                border-bottom: 2px solid #ccc;
                padding-bottom: 3mm;
                margin-bottom: 8mm;
                page-break-after: avoid;
                break-after: avoid;
            }
            
            .patient-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15mm;
                margin-bottom: 10mm;
            }
            
            .info-item {
                margin-bottom: 5mm;
                font-size: 12pt;
            }
            
            .info-label {
                font-weight: bold;
                display: inline-block;
                width: 120px;
                color: #495057;
            }
            
            .diet-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 5mm;
                font-size: 11pt;
                table-layout: auto;
            }
            
            .diet-table th,
            .diet-table td {
                border: 1px solid #333;
                padding: 3.6mm; /* Increased by 20% from 3mm */
                text-align: left;
                height: 120%; /* Make cells 20% taller */
            }
            
            .diet-table th {
                background-color: #f5f5f5;
                font-weight: bold;
                text-align: center;
                padding: 6mm 2.4mm; /* Increased by 20% from 5mm 2mm */
                vertical-align: middle;
                word-break: break-word;
            }
            
            .diet-table td {
                vertical-align: middle;
            }
            
            .restrictions {
                background-color: #fff3cd;
                border: 1px solid #ffeaa7;
                padding: 5mm;
                border-radius: 3px;
                margin-top: 5mm;
            }
            
            .footer {
                position: absolute;
                bottom: 15mm;
                left: 15mm;
                right: 15mm;
                text-align: center;
                font-size: 10pt;
                color: #666;
                border-top: 1px solid #ccc;
                padding-top: 5mm;
            }
            
            @media print {
                @page {
                    size: A3 landscape;
                    margin: 15mm;
                }
                
                html, body {
                    width: 100% !important;
                    height: auto !important;
                    overflow: visible !important;
                    background: white !important;
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
                
                .a3-page {
                    box-shadow: none !important;
                    margin: 0 !important;
                    padding: 15mm !important;
                    width: 100% !important;
                    height: auto !important;
                    max-width: none !important;
                    min-height: auto !important;
                    overflow: visible !important;
                    position: relative !important;
                }
                
                .print-button {
                    display: none !important;
                }
                
                .header {
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                }
                
                .content-section {
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                    overflow: visible !important;
                }
                
                .diet-table {
                    width: 100% !important;
                    table-layout: auto !important;
                    overflow: visible !important;
                    display: table !important;
                    page-break-inside: auto !important;
                }
                
                .diet-table thead {
                    display: table-header-group !important;
                }
                
                .diet-table tbody {
                    display: table-row-group !important;
                }
                
                .diet-table tr {
                    page-break-inside: avoid !important;
                    break-inside: avoid !important;
                    display: table-row !important;
                }
                
                .diet-table th, 
                .diet-table td {
                    display: table-cell !important;
                    page-break-inside: avoid !important;
                    white-space: normal !important;
                }
                
                /* Container fix */
                div[style*="overflow-x: auto"] {
                    overflow: visible !important;
                    width: 100% !important;
                    max-width: none !important;
                }
                
                .footer {
                    position: fixed;
                    bottom: 15mm;
                    left: 15mm;
                    right: 15mm;
                }
                
                * {
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
            }
        </style>
    </head>
    <body>
        <!-- Print Button -->
        <button class="print-button" onclick="printA3()">🖨️ Print A3</button>
        
        <div class="a3-page">
            <!-- Header -->
            <div class="header">
                <h1>NutriCount</h1>
                <h2>Hospital Units Diet Amount Summary</h2>
                <p style="margin: 5mm 0 0 0; font-size: 12pt;">Date: {{ \Carbon\Carbon::parse($data['date'])->format('F j, Y') }}</p>
                @if(isset($data['is_test_data']) && $data['is_test_data'])
                    <div style="background-color: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin-top: 10px;">
                        <strong>Note:</strong> Displaying test data. 
                        @if(isset($data['error']))
                            Error: {{ $data['error'] }}
                        @endif
                    </div>
                @endif
            </div>

            <!-- Diet Summary Table -->
            <div class="content-section">
                <div class="section-title">Diet Distribution by Hospital Units</div>
                
                <div style="overflow-x: auto;">
                    <table class="diet-table" style="font-size: 14pt; width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 150px; font-weight: bold; background-color: #e9ecef;">Hospital Unit</th>
                                @foreach($data['diets'] as $diet)
                                <th style="text-align: center; min-width: 80px; background-color: #e9ecef;">
                                    <div style="font-weight: bold;">{{ $diet->DietName_en }}</div>
                                    @if(isset($diet->primary_amount_unit) && $diet->primary_amount_unit)
                                    <div style="font-size: 8pt; color: #666;">({{ $diet->primary_amount_unit }})</div>
                                    @endif
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['diet_data'] as $unitData)
                            <tr>
                                <td style="font-weight: bold; background-color: #f8f9fa;">
                                    {{ $unitData['unit']->name ?? 'Unknown Unit' }}
                                    {{-- @if(isset($unitData['unit']->alias) && $unitData['unit']->alias)
                                    <br><span style="font-size: 8pt; color: #666;">({{ $unitData['unit']->alias }})</span>
                                    @endif --}}
                                </td>
                                @foreach($data['diets'] as $diet)
                                @php
                                    $amount = isset($unitData['diets'][$diet->id]) ? ($unitData['diets'][$diet->id]['amount'] ?? 0) : 0;
                                @endphp
                                <td style="text-align: center; line-height: 1.8; {{ $amount > 0 ? 'background-color: #fff3cd;' : '' }}">
                                    {{ $amount > 0 ? number_format($amount, 1) : '-' }}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                            
                            <!-- Totals Row -->
                            <tr style="border-top: 3px solid #333;">
                                <td style="font-weight: bold; background-color: #343a40; color: white; text-align: center;">
                                    TOTAL PER DIET
                                </td>
                                @foreach($data['diets'] as $diet)
                                @php
                                    $dietTotal = isset($data['diet_totals'][$diet->id]) ? ($data['diet_totals'][$diet->id]['total'] ?? 0) : 0;
                                @endphp
                                <td style="text-align: center; font-weight: bold; background-color: #343a40; color: white;">
                                    {{ $dietTotal > 0 ? number_format($dietTotal, 1) : '-' }}
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="content-section">
                <div class="section-title">Report Information</div>
                
                <div class="patient-info">
                    <div>
                        <div class="info-item">
                            <span class="info-label">Report Date:</span>
                            {{ \Carbon\Carbon::parse($data['date'])->format('l, F j, Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="info-item">
                            <span class="info-label">Generated:</span>
                            {{ \Carbon\Carbon::parse($data['generated_at'])->timezone('Asia/Colombo')->format('M j, Y - g:i A') }} (Colombo)
                        </div>
                        @if(isset($data['is_test_data']) && $data['is_test_data'])
                        <div class="info-item">
                            <span class="info-label">Status:</span>
                            <span style="color: #ffc107; font-weight: bold;">Test Data</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Diet Types Legend -->
            <div class="content-section">
                <div class="section-title">Diet Types Reference</div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-top: 5mm;">
                    @foreach($data['diets'] as $diet)
                    <div style="border: 1px solid #dee2e6; padding: 8px; border-radius: 4px; background-color: #f8f9fa;">
                        <div style="font-weight: bold; color: #495057;">{{ $diet->DietName_en ?? 'Unnamed Diet' }}</div>
                        @if(isset($diet->primary_amount_unit) && $diet->primary_amount_unit)
                        <div style="font-size: 9pt; color: #6c757d;">Unit: {{ $diet->primary_amount_unit }}</div>
                        @endif
                        @if(isset($diet->primary_amount_value) && $diet->primary_amount_value)
                        <div style="font-size: 9pt; color: #6c757d;">Standard: {{ $diet->primary_amount_value }}</div>
                        @endif
                        <div style="font-size: 8pt; color: #868e96;">
                            Total: {{ isset($data['diet_totals'][$diet->id]) ? ($data['diet_totals'][$diet->id]['total'] ?? 0) : 0 }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>NutriCount System - Hospital Diet Management : Health Information and Management Unit  - © 2025</p>
            </div>
        </div>

        <script>
            // Print function with A3 optimization
            function printA3() {
                // Hide the print button before printing
                const printButton = document.querySelector('.print-button');
                if (printButton) {
                    printButton.style.display = 'none';
                }
                
                // Set print-specific styles
                document.body.style.margin = '0';
                document.body.style.padding = '0';
                
                // Fix table display for printing the entire table
                const tableContainer = document.querySelector('.diet-table').closest('div');
                if (tableContainer) {
                    tableContainer.style.overflow = 'visible';
                    tableContainer.style.width = 'auto';
                    tableContainer.style.maxWidth = 'none';
                }
                
                // Get all tables and ensure they're set to a good width
                const tables = document.querySelectorAll('.diet-table');
                tables.forEach(table => {
                    table.style.width = '100%';
                    table.style.tableLayout = 'fixed';
                    table.style.display = 'table';
                    table.style.pageBreakInside = 'avoid';
                    
                    // Make sure all cells are properly sized
                    const cells = table.querySelectorAll('th, td');
                    cells.forEach(cell => {
                        cell.style.whiteSpace = 'normal';
                        cell.style.overflow = 'visible';
                        cell.style.maxWidth = 'none';
                    });
                });
                
                // Force background colors to print
                const style = document.createElement('style');
                style.innerHTML = `
                    * {
                        -webkit-print-color-adjust: exact !important;
                        color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }
                `;
                document.head.appendChild(style);
                
                // Trigger print dialog
                setTimeout(() => {
                    window.print();
                    
                    // Show the print button again after printing
                    setTimeout(() => {
                        if (printButton) {
                            printButton.style.display = 'block';
                        }
                        
                        // Clean up the added style element
                        document.head.removeChild(style);
                    }, 1000);
                }, 100);
            }
            
            // Add keyboard shortcut for printing (Ctrl+P)
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    printA3();
                }
            });
            
            // Auto-focus for better print experience
            window.addEventListener('load', function() {
                document.body.focus();
                
                // Check if test data warning is present
                const testDataWarning = document.querySelector('.header div[style*="background-color: #fff3cd"]');
                if (testDataWarning) {
                    // Add a "Load Real Data" button if test data is being shown
                    const warningBox = testDataWarning;
                    const loadRealDataButton = document.createElement('button');
                    loadRealDataButton.innerText = 'Load Real Data';
                    loadRealDataButton.style.marginLeft = '10px';
                    loadRealDataButton.style.padding = '5px 10px';
                    loadRealDataButton.style.backgroundColor = '#28a745';
                    loadRealDataButton.style.color = 'white';
                    loadRealDataButton.style.border = 'none';
                    loadRealDataButton.style.borderRadius = '3px';
                    loadRealDataButton.style.cursor = 'pointer';
                    loadRealDataButton.onclick = function() {
                        // Reload the page without error forcing
                        window.location.reload();
                    };
                    warningBox.appendChild(loadRealDataButton);
                }
            });
        </script>

        @fluxScripts
    </body>
</html>