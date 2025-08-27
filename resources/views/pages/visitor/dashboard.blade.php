@extends('layouts.master')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h2 class="fw-bold text-dark mb-3 mb-md-0 d-flex align-items-center flex-wrap" style="font-size: 1.5rem;">
                DASHBOARD MISI AKADEMIK {{ $tahun }}
            </h2>

            <form id="searchFilterForm" method="GET" action="{{ route('home') }}"
                class="d-flex flex-wrap align-items-center gap-2">
                <div>
                    <input type="text" name="search" class="form-control form-select-sm rounded-pill shadow-sm"
                        placeholder="Carian Data Pengunjung.." value="{{ request('search') }}">
                </div>
                {{-- Tahun --}}
                <div>
                    <select name="tahun" class="form-select form-select-sm shadow-sm rounded-pill">
                        <option value="">📅 Semua Tahun</option>
                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lokasi --}}
                <div>
                    <select name="lokasi" class="form-select form-select-sm shadow-sm rounded-pill">
                        <option value="">📍 Semua Lokasi</option>
                        @foreach ($availableLokasi as $l)
                            <option value="{{ $l }}" {{ $lokasi == $l ? 'selected' : '' }}>{{ $l }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Reset --}}
                <div>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-info rounded-pill">Reset</a>
                </div>
            </form>
        </div>


        {{-- KPI --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-start border-4 border-primary shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bx bx-user fs-1 text-primary me-3"></i>
                        <div>
                            <h6 class="text-muted mb-1">Jumlah Responden</h6>
                            <h4 class="fw-bold text-primary mb-0">{{ $totalResponden }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-4 border-success shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bx bx-map fs-1 text-success me-3"></i>
                        <div>
                            <h6 class="text-muted mb-1">Jumlah Lokasi</h6>
                            <h4 class="fw-bold text-success mb-0">{{ $jumlahLokasiUnik }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-start border-4 border-warning shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <i class="bx bx-book-open fs-1 text-warning me-3"></i>
                        <div>
                            <h6 class="text-muted mb-1">Jumlah Bidang</h6>
                            <h4 class="fw-bold text-warning mb-0">{{ $jumlahBidangUnik }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}

        <div class="row">
            {{-- Top 10 Program --}}
            <div class="col-md-7 mb-3">
                <div class="card border shadow-sm h-100">
                    <div class="card-body">
                        <div style="position:relative; height:380px;">
                            <canvas id="programChart"></canvas>
                        </div>
                        @if (count($programLabels) === 0)
                            <div class="text-muted small mt-2">Tiada data program untuk kombinasi filter semasa.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Lokasi --}}
            <div class="col-md-5 mb-3">
                <div class="card border shadow-sm h-100">
                    <div class="card-body">
                        <div id="lokasiChartContainer" style="position:relative; height:480px;">
                            <canvas id="lokasiChart"></canvas>
                        </div>
                        @if (count($lokasiLabels ?? []) === 0)
                            <div class="text-muted small mt-2">Tiada data lokasi untuk kombinasi filter semasa.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border shadow-sm mt-3">
            <div class="card-body">
                <div id="allProgramScroll"
                    style="
    max-height: 520px;
    overflow-y: auto;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;">
                    <div id="allProgramContainer" style="position:relative; min-height:280px;">
                        <canvas id="allProgramChart"></canvas>
                    </div>
                </div>

                @if (count($allProgramLabels) === 0)
                    <div class="text-muted small mt-2">Tiada data bidang untuk kombinasi filter semasa.</div>
                @endif
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
            <div class="card">
                <div class="card-header text-center text-white h6" style="background-color:#03244c;">
                    SENARAI DATA PENGUNJUNG
                </div>

                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table id="visitorTable" class="table table-sm table-striped table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tarikh</th>
                                    <th>Nama</th>
                                    <th>Lokasi</th>
                                    <th>Program/Bidang</th>
                                    <th class="d-none">ID</th> {{-- lajur ID tersembunyi --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tableRows as $r)
                                    <tr>
                                        <td></td>
                                        <td
                                            data-order="{{ $r->response_at ? \Carbon\Carbon::parse($r->response_at)->format('Y-m-d H:i:s') : '0000-00-00 00:00:00' }}">
                                            {{ $r->response_at ? \Carbon\Carbon::parse($r->response_at)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td>{{ $r->full_name }}</td>
                                        <td>{{ $r->lokasi }}</td>
                                        <td style="min-width:260px">
                                            @if ($r->program_bidang)
                                                @php
                                                    $items = preg_split('/[,;]+/', $r->program_bidang);
                                                    $items = array_filter(array_map('trim', $items));
                                                @endphp
                                                <ul class="mb-0 ps-3" style="list-style: disc;">
                                                    @foreach ($items as $item)
                                                        <li>{{ $item }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="d-none">{{ $r->id }}</td> {{-- nilai ID --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tiada rekod</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('searchFilterForm');
            const searchInput = form.querySelector('input[name="search"]');
            const tahunInput = form.querySelector('select[name="tahun"]');
            const lokasiInput = form.querySelector('select[name="lokasi"]');

            // Debounce for search input
            let debounceTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimeout);
                    debounceTimeout = setTimeout(() => {
                        form.submit();
                    }, 500);
                });
            }

            // Instant submit on tahun change
            if (tahunInput) {
                tahunInput.addEventListener('change', function() {
                    form.submit();
                });
            }

            // Instant submit on lokasi change
            if (lokasiInput) {
                lokasiInput.addEventListener('change', function() {
                    form.submit();
                });
            }

            // ✅ FIXED: Register all reset buttons (both user types)
            const resetButtons = document.querySelectorAll('.reset-button');
            resetButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    // Reload base route, clear all filters
                    window.location.href = "{{ route('home') }}";
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('programChart').getContext('2d');

            const programLabels = @json($programLabels);
            const programData = @json($programData);

            // jumlah keseluruhan untuk kira peratus
            const totalAll = programData.reduce((sum, v) => sum + (parseFloat(v) || 0), 0);

            const colors = [
                'rgba(46, 204, 113, 0.85)', // hijau terang
                'rgba(52, 152, 219, 0.85)', // biru
                'rgba(231, 76, 60, 0.85)', // merah
                'rgba(241, 196, 15, 0.85)', // kuning
                'rgba(155, 89, 182, 0.85)', // ungu
                'rgba(230, 126, 34, 0.85)', // oren
                'rgba(26, 188, 156, 0.85)', // turquoise
                'rgba(149, 165, 166, 0.85)', // kelabu
                'rgba(243, 156, 18, 0.85)', // amber
                'rgba(52, 73, 94, 0.85)' // navy gelap
            ];

            const backgroundColors = programData.map((_, i) => colors[i % colors.length]);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: programLabels,
                    datasets: [{
                        label: 'Bil. Minat',
                        data: programData,
                        backgroundColor: backgroundColors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'TOP 10 PROGRAM/BIDANG',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 45
                            },
                            color: '#000'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const value = ctx.parsed.y || 0;
                                    const pct = totalAll > 0 ? ((value / totalAll) * 100).toFixed(2) :
                                        '0.00';
                                    return ` ${value} (${pct}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#000'
                            },
                            ticks: {
                                callback: (v) => Number.isInteger(v) ? v : ''
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Program/Bidang',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#000'
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 0
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'percentageLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.font = 'bold 12px Arial';
                        ctx.textAlign = 'center';
                        ctx.fillStyle = '#000';

                        data.datasets[0].data.forEach((value, index) => {
                            const meta = chart.getDatasetMeta(0);
                            const bar = meta.data[index];
                            if (!bar) return;

                            const x = bar.x;
                            const y = bar.y - 10;

                            const pct = totalAll > 0 ? ((value / totalAll) * 100).toFixed(
                                1) : '0.0';
                            ctx.fillText(`${value}`, x, y - 12);
                            ctx.fillText(`(${pct}%)`, x, y + 3);
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---------- Data dari controller ----------
            const labelsAll = @json($allProgramLabels ?? []);
            const dataAll = @json($allProgramData ?? []);

            // ---------- Guard: kalau tiada data, jangan inisialisasi ----------
            if (!Array.isArray(labelsAll) || labelsAll.length === 0) return;

            // ---------- Tinggi dinamik + minimum utk 1–2 bar ----------
            const PER_BAR = 28;
            const BOTTOM_PAD = 80;
            const MIN_CANVAS = 280;

            const canvasAll = document.getElementById('allProgramChart');
            const dynamicHeight = Math.max(MIN_CANVAS, (labelsAll.length * PER_BAR) + BOTTOM_PAD);

            // Set tinggi pada canvas supaya Chart.js lukis semua bar
            canvasAll.height = dynamicHeight; // intrinsic height (px)
            canvasAll.style.height = dynamicHeight + 'px'; // CSS height (px)


            // ---------- Warna berturutan ----------
            const colorsPool = [
                'rgba(46, 204, 113, 0.85)', 'rgba(52, 152, 219, 0.85)', 'rgba(231, 76, 60, 0.85)',
                'rgba(241, 196, 15, 0.85)', 'rgba(155, 89, 182, 0.85)', 'rgba(230, 126, 34, 0.85)',
                'rgba(26, 188, 156, 0.85)', 'rgba(149, 165, 166, 0.85)', 'rgba(243, 156, 18, 0.85)',
                'rgba(52, 73, 94, 0.85)'
            ];
            const bgAll = dataAll.map((_, i) => colorsPool[i % colorsPool.length]);

            // ---------- Total utk percent ----------
            const totalAllBars = dataAll.reduce((s, v) => s + (parseFloat(v) || 0), 0);

            // ---------- Setting label nilai/persen (elak semak bila terlalu banyak) ----------
            const showValueLabels = labelsAll.length <= 60;

            const ctxAll = document.getElementById('allProgramChart').getContext('2d');

            // ---------- Bina chart ----------
            const allChart = new Chart(ctxAll, {
                type: 'bar',
                data: {
                    labels: labelsAll,
                    datasets: [{
                        label: 'Bil. Minat',
                        data: dataAll,
                        backgroundColor: bgAll,

                        // Pastikan bar kekal “gemuk” walaupun label sedikit
                        barThickness: 24, // boleh adjust 20–30
                        maxBarThickness: 36,
                        categoryPercentage: 0.8,
                        barPercentage: 0.9
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // horizontal
                    layout: {
                        padding: {
                            top: 16,
                            right: 24,
                            bottom: 24,
                            left: 8
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'SENARAI SEMUA PROGRAM/BIDANG',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: {
                                top: 6,
                                bottom: 16
                            },
                            color: '#000'
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const val = ctx.parsed.x || 0;
                                    const pct = totalAllBars ? ((val / totalAllBars) * 100).toFixed(2) :
                                        '0.00';
                                    return ` ${val} (${pct}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                color: '#000'
                            },
                            ticks: {
                                // papar integer sahaja
                                callback: (v) => Number.isInteger(v) ? v : ''
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Program/Bidang',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                color: '#000'
                            },
                            ticks: {
                                autoSkip: false,
                                // potong label terlalu panjang utk kemas
                                callback: (value, index) => {
                                    const label = labelsAll[index] || '';
                                    return label.length > 60 ? label.slice(0, 57) + '…' : label;
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        }
                    }
                },
                plugins: [{
                    // Tulis "nilai (xx.x%)" di hujung bar — disable automatik bila label banyak
                    id: 'valueLabelsAll',
                    afterDatasetsDraw(chart) {
                        if (!showValueLabels) return;
                        const {
                            ctx,
                            data,
                            chartArea
                        } = chart;
                        const dataset = data.datasets[0];
                        const meta = chart.getDatasetMeta(0);

                        ctx.save();
                        ctx.font = 'bold 11px Arial';
                        ctx.textAlign = 'left';
                        ctx.fillStyle = '#000';

                        dataset.data.forEach((_, i) => {
                            const bar = meta.data[i];
                            if (!bar) return;
                            const val = dataset.data[i] || 0;
                            const pct = totalAllBars ? ((val / totalAllBars) * 100).toFixed(
                                1) : '0.0';

                            // tulis di hujung bar, tapi jangan keluar chart
                            let x = bar.x + 6;
                            const maxX = chartArea.right - 40;
                            if (x > maxX) x = maxX;

                            const y = bar.y + 4;
                            ctx.fillText(`${val} (${pct}%)`, x, y);
                        });

                        ctx.restore();
                    }
                }]
            });

            // Paksa reflow kecil untuk layout tertentu
            setTimeout(() => allChart.resize(), 0);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lokasiLabels = @json($lokasiLabels ?? []);
            const lokasiData = @json($lokasiData ?? []);
            const totalAll = @json($totalResponden ?? 0); // jumlah untuk % tooltip/label

            if (!Array.isArray(lokasiLabels) || lokasiLabels.length === 0) return;

            // Auto height (tak jadikan 1–2 bar terlalu nipis)
            const container = document.getElementById('lokasiChartContainer');
            const PER_BAR = 28;
            const BOTTOM_PAD = 80;
            const MIN_HEIGHT = 280;
            const dynamicH = Math.max(MIN_HEIGHT, (lokasiLabels.length * PER_BAR) + BOTTOM_PAD);
            container.style.height = dynamicH + 'px';
            container.style.marginBottom = '32px';

            // Warna kitaran
            const colors = [
                'rgba(52,152,219,0.85)', 'rgba(46,204,113,0.85)', 'rgba(231,76,60,0.85)',
                'rgba(241,196,15,0.85)', 'rgba(155,89,182,0.85)', 'rgba(230,126,34,0.85)',
                'rgba(26,188,156,0.85)', 'rgba(149,165,166,0.85)', 'rgba(243,156,18,0.85)',
                'rgba(52,73,94,0.85)'
            ];
            const bgAll = lokasiData.map((_, i) => colors[i % colors.length]);

            const showValueLabels = lokasiLabels.length <= 60;

            const ctx = document.getElementById('lokasiChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: lokasiLabels,
                    datasets: [{
                        label: 'Bil. Responden',
                        data: lokasiData,
                        backgroundColor: bgAll,
                        barThickness: 24,
                        maxBarThickness: 36,
                        categoryPercentage: 0.8,
                        barPercentage: 0.9
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    layout: {
                        padding: {
                            top: 16,
                            right: 24,
                            bottom: 24,
                            left: 8
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Responden Mengikut Lokasi',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#000',
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const v = ctx.parsed.x || 0;
                                    const p = totalAll ? ((v / totalAll) * 100).toFixed(2) : '0.00';
                                    return ` ${v} (${p}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                color: '#000'
                            },
                            ticks: {
                                callback: (v) => Number.isInteger(v) ? v : ''
                            },
                            grid: {
                                drawBorder: false
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Lokasi',
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                color: '#000'
                            },
                            ticks: {
                                autoSkip: false,
                                callback: (val, i) => {
                                    const label = lokasiLabels[i] || '';
                                    return label.length > 60 ? label.slice(0, 57) + '…' : label;
                                }
                            },
                            grid: {
                                drawBorder: false
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'valueLabelsLokasi',
                    afterDatasetsDraw(chart) {
                        if (!showValueLabels) return;
                        const {
                            ctx,
                            data,
                            chartArea
                        } = chart;
                        const ds = data.datasets[0];
                        const meta = chart.getDatasetMeta(0);

                        ctx.save();
                        ctx.font = 'bold 11px Arial';
                        ctx.textAlign = 'left';
                        ctx.fillStyle = '#000';

                        ds.data.forEach((_, i) => {
                            const bar = meta.data[i];
                            if (!bar) return;
                            const val = ds.data[i] || 0;
                            const pct = totalAll ? ((val / totalAll) * 100).toFixed(1) :
                                '0.0';
                            let x = bar.x + 6;
                            const maxX = chartArea.right - 40;
                            if (x > maxX) x = maxX;
                            const y = bar.y + 4;
                            ctx.fillText(`${val} (${pct}%)`, x, y);
                        });

                        ctx.restore();
                    }
                }]
            });

            setTimeout(() => chart.resize(), 0);
        });
    </script>

    {{-- data table --}}
    <script>
        $(document).ready(function() {
            var table = $('#visitorTable').DataTable({
                scrollY: "400px",
                scrollCollapse: true,
                paging: true,
                autoWidth: false,
                responsive: true,
                fixedHeader: true,

                // Order default ikut lajur ID (index 5) DESC
                order: [
                    [5, 'desc']
                ],

                columnDefs: [
                    // Kolum # auto number
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            return meta.settings._iDisplayStart + meta.row + 1;
                        }
                    },
                    // Sembunyikan lajur ID tapi masih boleh digunakan untuk sort
                    {
                        targets: 5,
                        visible: false,
                        searchable: false
                    },

                    {
                        targets: 2,
                        className: 'text-truncate'
                    }, // Nama
                    {
                        targets: 3,
                        className: 'text-truncate'
                    }, // Lokasi
                    {
                        targets: 4,
                        className: 'text-truncate'
                    }, // Program/Bidang
                ],

                language: {
                    paginate: {
                        previous: "Prev",
                        next: "Next"
                    },
                    search: "Cari:",
                    lengthMenu: "Papar _MENU_ rekod setiap halaman",
                    info: "Paparan _START_ hingga _END_ daripada _TOTAL_ rekod",
                    infoEmpty: "Tiada rekod tersedia",
                    zeroRecords: "Tiada padanan rekod ditemukan",
                }
            });

            table.columns.adjust().draw(false);
        });
    </script>



@endsection
