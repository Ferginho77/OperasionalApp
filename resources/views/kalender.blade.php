@extends('layouts.header')

@section('title', 'Dashboard Page')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<div class="container mt-4">
    <a href="/jadwal" class="btn btn-outline-danger">Kembali</a>
    <h3>Kalender Jadwal Operator @isset($namaspbu) {{ $namaspbu }}@endisset</h3>
    <div class="card mb-4">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: "{{ route('kalender.api') }}",
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
    });
    calendar.render();
});
</script>
@endsection
