@extends('filament::page')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Diet Amounts Calendar</h1>
        <div id="calendar" class="bg-white rounded shadow p-4">
            <!-- Calendar will be rendered here -->
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 700,
                events: @json($events ?? []),
            });
            calendar.render();
        });
    </script>
@endsection
