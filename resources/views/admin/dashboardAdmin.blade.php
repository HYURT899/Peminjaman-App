@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard Management Asset</h1>
@stop

@section('content')
    <div class="row">
        {{-- Card Total Assets --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalAssets }}</h3>
                    <p>Total Assets</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box"></i>
                </div>
                <a href="{{ route('admin.assets.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        {{-- Bisa tambahin card lain di sini, misalnya jumlah kategori --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $kategori->count() }}</h3>
                    <p>Total Kategori</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="{{ route('categories.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalPeminjams }}</h3>
                    <p>Total Peminjam</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-alt"></i>
                </div>
                <a href="{{ route('admin.peminjam.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total User</p>
                </div>
                <div class="icon">
                    <i class="fa fa-address-card"></i>
                </div>
                <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Grafik & tabel --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Grafik Asset per Kategori</h3>
                </div>
                <div class="card-body">
                    <canvas id="kategoriChart" style="width:100%; height:60vh; max-height:345px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Jumlah Asset per Kategori</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 385px; overflow-y: auto;">
                        <table class="table table-bordered mb-0">
                            <thead class="sticky-top" style="background-color: #ffff;">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid p-0">
            <div class="row g-0">
                <!-- Kalender Main Area -->
                <div class="col-md-9">
                    <div class="calendar-full-width">
                        <div class="calendar-controls">
                            <div>
                                <button class="btn btn-sm btn-outline-primary" id="prevMonth">
                                    <i class="fas fa-angle-left"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" id="todayBtn">Hari Ini</button>
                                <button class="btn btn-sm btn-outline-primary" id="nextMonth">
                                    <i class="fas fa-angle-right"></i>
                                </button>
                            </div>
                            <h4 class="mb-0 flex-grow-1 text-center" id="currentMonthYear"></h4>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="calendar-header">
                            <div>Minggu</div>
                            <div>Senin</div>
                            <div>Selasa</div>
                            <div>Rabu</div>
                            <div>Kamis</div>
                            <div>Jumat</div>
                            <div>Sabtu</div>
                        </div>

                        <div class="calendar-grid" id="calendarGrid">
                            <!-- Days will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Panduan Fitur & Tambah Acara -->
                <div class="col-md-3 border-start">
                    <div class="p-3">
                        <!-- Panduan Fitur -->
                        <div class="sidebar-section">
                            <h6 class="border-bottom pb-2">📋 Panduan Kalender</h6>
                            <div class="feature-guide">
                                <div class="guide-item">
                                    <span class="guide-icon">📅</span>
                                    <div class="guide-content">
                                        <strong>Klik Tanggal</strong>
                                        <small class="text-muted">Untuk tambah acara cepat</small>
                                    </div>
                                </div>
                                <div class="guide-item">
                                    <span class="guide-icon">🗑️</span>
                                    <div class="guide-content">
                                        <strong>Klik Acara</strong>
                                        <small class="text-muted">Untuk hapus acara</small>
                                    </div>
                                </div>
                                <div class="guide-item">
                                    <span class="guide-icon">💾</span>
                                    <div class="guide-content">
                                        <strong>Auto Save</strong>
                                        <small class="text-muted">Data tersimpan otomatis</small>
                                    </div>
                                </div>
                                <div class="guide-item">
                                    <span class="guide-icon">🎨</span>
                                    <div class="guide-content">
                                        <strong>Warna Acara</strong>
                                        <small class="text-muted">Biru: Kerja, Hijau: Pribadi, Merah: Penting</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Tambah Acara -->
                        <div class="sidebar-section">
                            <h6 class="border-bottom pb-2">➕ Tambah Acara Baru</h6>
                            <form id="quickEventForm">
                                <div class="mb-2">
                                    <label class="form-label small">Judul Acara</label>
                                    <input type="text" class="form-control form-control-sm" id="eventTitle"
                                        placeholder="Meeting, Ulang Tahun, dll..." required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Tanggal</label>
                                    <input type="date" class="form-control form-control-sm" id="eventDate" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Kategori</label>
                                    <select class="form-select form-select-sm" id="eventCategory">
                                        <option value="work">💼 Kerja</option>
                                        <option value="personal">👤 Pribadi</option>
                                        <option value="important">⚠️ Penting</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">
                                    <i class="bi bi-plus-circle"></i> Tambah Acara
                                </button>
                            </form>
                        </div>

                        <!-- Acara Hari Ini -->
                        <div class="sidebar-section">
                            <h6 class="border-bottom pb-2">📝 Acara Hari Ini</h6>
                            <div id="todayEvents" class="today-events">
                                <div class="text-muted small">Tidak ada acara hari ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('kategoriChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($kategori->pluck('name')),
                datasets: [{
                    data: @json($kategori->pluck('total')),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                }]
            }
        });
    </script>

    <script>
        class Calendar {
            constructor() {
                this.currentDate = new Date();
                this.events = this.loadEventsFromStorage();
                this.init();
            }

            init() {
                this.renderCalendar();
                this.setupEventListeners();
                this.setDefaultEventDate();
                this.renderTodayEvents();
            }

            renderCalendar() {
                const monthYear = document.getElementById('currentMonthYear');
                const calendarGrid = document.getElementById('calendarGrid');

                // Clear existing days
                calendarGrid.innerHTML = '';

                // Set month year title
                monthYear.textContent = this.currentDate.toLocaleDateString('id-ID', {
                    month: 'long',
                    year: 'numeric'
                });

                const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
                const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);

                // Add empty cells for days before the first day of month
                const startDay = firstDay.getDay();
                for (let i = 0; i < startDay; i++) {
                    const prevDate = new Date(firstDay);
                    prevDate.setDate(prevDate.getDate() - (startDay - i));
                    this.createDayElement(prevDate, true);
                }

                // Add days of current month
                for (let i = 1; i <= lastDay.getDate(); i++) {
                    const date = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), i);
                    this.createDayElement(date, false);
                }

                // Add empty cells to complete 6 weeks
                const totalCells = 42;
                const existingCells = calendarGrid.children.length;
                const remainingCells = totalCells - existingCells;

                for (let i = 1; i <= remainingCells; i++) {
                    const nextDate = new Date(lastDay);
                    nextDate.setDate(nextDate.getDate() + i);
                    this.createDayElement(nextDate, true);
                }

                this.renderEvents();
            }

            createDayElement(date, isOtherMonth) {
                const calendarGrid = document.getElementById('calendarGrid');
                const dayElement = document.createElement('div');
                dayElement.className = 'calendar-day';

                if (isOtherMonth) {
                    dayElement.classList.add('other-month');
                }

                const today = new Date();
                if (date.toDateString() === today.toDateString()) {
                    dayElement.classList.add('today');
                }

                dayElement.innerHTML = `
                    <div class="calendar-date">${date.getDate()}</div>
                    <div class="calendar-events" data-date="${date.toISOString().split('T')[0]}"></div>
                `;

                dayElement.addEventListener('click', () => {
                    this.addQuickEvent(date);
                });

                calendarGrid.appendChild(dayElement);
            }

            addQuickEvent(date) {
                // Gunakan tanggal lokal tanpa mengkonversi ke ISO
                const localDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                const dateStr = this.formatDateForStorage(localDate);

                const eventTitle = prompt(`Tambah acara untuk ${date.toLocaleDateString('id-ID')}:`);
                if (eventTitle && eventTitle.trim() !== '') {
                    const event = {
                        id: Date.now(),
                        title: eventTitle.trim(),
                        date: dateStr, // Pakai dateStr yang sudah diformat
                        type: 'work'
                    };

                    this.events.push(event);
                    this.saveEventsToStorage();
                    this.renderEvents();
                    this.renderTodayEvents();
                    alert('Acara berhasil ditambahkan!');
                }
            }

            renderEvents() {
                document.querySelectorAll('.calendar-events').forEach(container => {
                    const dateStr = container.getAttribute('data-date');
                    const dayEvents = this.events.filter(event => event.date === dateStr);

                    container.innerHTML = '';

                    dayEvents.forEach(event => {
                        const eventElement = document.createElement('div');
                        eventElement.className = `calendar-event event-${event.type}`;
                        eventElement.textContent = event.title;
                        eventElement.title = event.title;

                        eventElement.addEventListener('click', (e) => {
                            e.stopPropagation();
                            if (confirm(`Hapus acara "${event.title}"?`)) {
                                this.events = this.events.filter(e => e.id !== event.id);
                                this.saveEventsToStorage();
                                this.renderEvents();
                                this.renderTodayEvents();
                            }
                        });

                        container.appendChild(eventElement);
                    });
                });
            }

            renderTodayEvents() {
                const todayEvents = document.getElementById('todayEvents');
                const today = new Date().toISOString().split('T')[0];
                const todayEventsList = this.events.filter(event => event.date === today);

                if (todayEventsList.length === 0) {
                    todayEvents.innerHTML = '<div class="text-muted small">Tidak ada acara hari ini</div>';
                    return;
                }

                todayEvents.innerHTML = todayEventsList.map(event => {
                    const categoryNames = {
                        'work': '💼 Kerja',
                        'personal': '👤 Pribadi',
                        'important': '⚠️ Penting'
                    };

                    const categoryColors = {
                        'work': 'badge-work',
                        'personal': 'badge-personal',
                        'important': 'badge-important'
                    };

                    return `
                        <div class="d-flex align-items-center mb-2 p-2 border rounded">
                            <span class="event-category-badge ${categoryColors[event.type]}"></span>
                            <div class="flex-grow-1">
                                <div class="small">${event.title}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">${categoryNames[event.type]}</div>
                            </div>
                            <button class="btn btn-sm btn-outline-danger" onclick="calendar.deleteEvent(${event.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                }).join('');
            }

            deleteEvent(eventId) {
                this.events = this.events.filter(event => event.id !== eventId);
                this.saveEventsToStorage();
                this.renderEvents();
                this.renderTodayEvents();
            }

            loadEventsFromStorage() {
                try {
                    return JSON.parse(localStorage.getItem('calendarEvents')) || [];
                } catch {
                    return [];
                }
            }

            saveEventsToStorage() {
                localStorage.setItem('calendarEvents', JSON.stringify(this.events));
            }

            setDefaultEventDate() {
                document.getElementById('eventDate').value = new Date().toISOString().split('T')[0];
            }

            setupEventListeners() {
                // Navigation
                document.getElementById('prevMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                    this.renderCalendar();
                });

                document.getElementById('nextMonth').addEventListener('click', () => {
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                    this.renderCalendar();
                });

                document.getElementById('todayBtn').addEventListener('click', () => {
                    this.currentDate = new Date();
                    this.renderCalendar();
                });

                // Quick Event Form
                document.getElementById('quickEventForm').addEventListener('submit', (e) => {
                    e.preventDefault();

                    const title = document.getElementById('eventTitle').value;
                    const dateInput = document.getElementById('eventDate').value;
                    const category = document.getElementById('eventCategory').value;

                    if (title && dateInput) {
                        // Parse tanggal dari input tanpa mengubah timezone
                        const [year, month, day] = dateInput.split('-');
                        const dateStr = `${year}-${month}-${day}`; // Langsung pakai format YYYY-MM-DD

                        const event = {
                            id: Date.now(),
                            title: title,
                            date: dateStr, // Langguna pakai string dari input
                            type: category
                        };

                        this.events.push(event);
                        this.saveEventsToStorage();
                        this.renderEvents();
                        this.renderTodayEvents();

                        // Reset form
                        document.getElementById('quickEventForm').reset();
                        this.setDefaultEventDate();

                        alert('Acara berhasil ditambahkan!');
                    }
                });
            }

            formatDateForStorage(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                }

                createDayElement(date, isOtherMonth) {
                    const calendarGrid = document.getElementById('calendarGrid');
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day';

                    if (isOtherMonth) {
                        dayElement.classList.add('other-month');
                    }

                    const today = new Date();
                    if (date.toDateString() === today.toDateString()) {
                        dayElement.classList.add('today');
                    }

                    // Format tanggal untuk storage tanpa timezone issues
                    const dateStr = this.formatDateForStorage(date);

                    dayElement.innerHTML = `
                        <div class="calendar-date">${date.getDate()}</div>
                        <div class="calendar-events" data-date="${dateStr}"></div>`;

                    dayElement.addEventListener('click', () => {
                        this.addQuickEvent(date);
                    });

                    calendarGrid.appendChild(dayElement);
                }
        }

        // Initialize calendar
        let calendar;
        document.addEventListener('DOMContentLoaded', () => {
            calendar = new Calendar();
        });
    </script>
@stop

@section('css')
    <style>
        .calendar-full-width {
            background: white;
            min-height: 100vh;
        }

        .calendar-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 20px 20px 0;
        }

        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: bold;
            padding: 15px 0;
            background: #f8f9fa;
            margin: 0 20px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            min-height: 600px;
            margin: 0 20px 20px;
            background: #dee2e6;
            border: 1px solid #dee2e6;
        }

        .calendar-day {
            background: white;
            padding: 12px;
            min-height: 120px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .calendar-day:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .calendar-day.other-month {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .calendar-day.today {
            background-color: #e7f3ff;
            border: 2px solid #007bff;
        }

        .calendar-date {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .calendar-events {
            font-size: 0.75rem;
        }

        .calendar-event {
            color: white;
            padding: 2px 5px;
            border-radius: 3px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .event-work {
            background: #0dcaf0;
        }

        .event-personal {
            background: #198754;
        }

        .event-important {
            background: #dc3545;
        }

        /* Sidebar Styles */
        .feature-guide {
            max-height: 200px;
            overflow-y: auto;
        }

        .guide-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .guide-item:last-child {
            border-bottom: none;
        }

        .guide-icon {
            font-size: 1.2rem;
            margin-right: 10px;
            width: 30px;
            text-align: center;
        }

        .guide-content {
            flex: 1;
        }

        .guide-content strong {
            display: block;
            font-size: 0.85rem;
        }

        .guide-content small {
            font-size: 0.75rem;
        }

        .today-events {
            max-height: 150px;
            overflow-y: auto;
        }

        .event-category-badge {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .badge-work {
            background: #0dcaf0;
        }

        .badge-personal {
            background: #198754;
        }

        .badge-important {
            background: #dc3545;
        }

        .sidebar-section {
            margin-bottom: 20px;
        }
    </style>
@stop
