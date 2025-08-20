@extends('layouts.header')

@section('title', 'Dashboard Page')

<style>
    /* Styling agar teks lebih jelas */
    #calendarMini {
        max-width: 100%;
        margin: 0 auto;
    }

    #calendarMini .fc-daygrid-day-number,
    #calendarMini .fc-col-header-cell-cushion,
    #calendarMini .fc-event-title {
        color: #000 !important;
        /* teks hitam */
        font-weight: normal !important;
        /* font normal */
        font-size: 14px !important;
        /* lebih mudah dibaca */
    }

    #calendarMini .fc-toolbar-title {
        font-size: 20px;
        font-weight: 600;
        color: #000;
        text-transform: uppercase;
    }
</style>

@section('content')

    <div class="row">
        <h2>Selamat Datang Admin @isset($namaspbu)
                {{ $namaspbu }}
            @endisset <br>
            Kode SPBU @isset($nomorspbu)
                - {{ $nomorspbu }}
            @endisset
        </h2>
        <div class="row mt-5">

            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">Jadwal Operator <i class="fa-solid fa-calendar-days"></i></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="/kalender">View Details</a>
                        <div class="small text-white">
                            <svg class="svg-inline--fa fa-angle-right" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 256 512">
                                <path fill="currentColor"
                                    d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning mb-4">
                    <div class="card-body">Kehadiran karyawan <i class="fas fa-clipboard-user"></i></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-black stretched-link" href="/kehadiran">View Details</a>
                        <div class="small text-white">
                            <svg class="svg-inline--fa fa-angle-right" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 256 512">
                                <path fill="currentColor"
                                    d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">Manajemen SPBU <i class="fa-solid fa-list-check"></i></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="/manajemen">View Details</a>
                        <div class="small text-white">
                            <svg class="svg-inline--fa fa-angle-right" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 256 512">
                                <path fill="currentColor"
                                    d="M246.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L178.7 256 41.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">Operator Aktif Hari Ini <i class="fa-solid fa-user-check"></i></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <span class="small text-white">{{ $operatorAktif }} Operator</span>
                    </div>
                </div>
            </div>
            <div class="row mt-4 justify-content-center">
                <div class="col-12 col-lg-8 mb-3">
                    <div class="card w-100 shadow-sm" style="max-width: 900px; margin: auto;">
                        <div class="card-body p-2">
                            <div id="calendarMini"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendarMini');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto', // biar responsif mengikuti tinggi kontainer
                aspectRatio: 1.35, // rasio lebih natural
                expandRows: true, // baris kalender otomatis menyesuaikan
                events: "{{ route('kalender.api') }}",
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                windowResize: function() {
                    calendar.updateSize(); // otomatis menyesuaikan saat resize
                }
            });
            calendar.render();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        // Jam realtime
        function updateClock() {
            const now = new Date();

            // Format jam: HH:MM:SS
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            let timeString = `${hours}:${minutes}:${seconds}`;

            // Format tanggal: Senin, 18 Agustus 2025
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            let dateString = now.toLocaleDateString('id-ID', options);

            document.getElementById('realTimeClock').textContent = timeString;
            document.getElementById('realDate').textContent = dateString;
        }

        setInterval(updateClock, 1000); // update tiap 1 detik
        updateClock(); // jalankan pertama kali
    </script>
@endsection
