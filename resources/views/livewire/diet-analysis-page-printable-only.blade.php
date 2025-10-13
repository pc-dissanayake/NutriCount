<div class="p-4">
    <div class="mb-2 text-sm text-gray-700 dark:text-gray-300">
        Report page size (from settings): <strong>{{ $reportPageSize }}</strong>
        — Width: <strong>{{ $pageWidth }}mm</strong>, Height: <strong>{{ $pageHeight }}mm</strong>
    </div>



    

    <script>
        window.REPORT_PAGE = {
            size: @json($reportPageSize),
            widthMm: @json($pageWidth),
            heightMm: @json($pageHeight),
        };
    </script>

    <!-- Place printable content here -->
    <div id="print-area" class="bg-white dark:bg-gray-900 rounded shadow p-4">
        <!-- content -->
    </div>
</div>
