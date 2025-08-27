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
        </div>

        {{-- Charts --}}

        <div class="card border shadow-sm">
            <div class="card-body">

                <!-- ✅ Bekas tetap 380px -->
                <div style="position:relative; height:380px;">
                    <canvas id="programChart"></canvas>
                </div>

                @if (count($programLabels) === 0)
                    <div class="text-muted small mt-2">Tiada data program untuk kombinasi filter semasa.</div>
                @endif
            </div>
        </div>

        <div class="card border shadow-sm mt-3">
            <div class="card-body">
                <div id="allProgramContainer" style="position:relative;">
                    <canvas id="allProgramChart"></canvas>
                </div>

                @if (count($allProgramLabels) === 0)
                    <div class="text-muted small mt-2">Tiada data bidang untuk kombinasi filter semasa.</div>
                @endif
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
            const labelsAll = @json($allProgramLabels);
            const dataAll = @json($allProgramData);

            const totalAllBars = dataAll.reduce((s, v) => s + (parseFloat(v) || 0), 0);

            const container = document.getElementById('allProgramContainer');

            // ✅ Tinggi per bar + padding bawah supaya tak terpotong
            const PER_BAR = 28; // 26–30 pun ok
            const BOTTOM_PADDING = 80; // ruang untuk label/tooltip
            const dynamicHeight = (labelsAll.length * PER_BAR) + BOTTOM_PADDING;
            container.style.height = dynamicHeight + 'px';
            container.style.marginBottom = '32px'; // ruang dari footer

            const ctxAll = document.getElementById('allProgramChart').getContext('2d');

            const colorsPool = [
                'rgba(46, 204, 113, 0.85)', 'rgba(52, 152, 219, 0.85)', 'rgba(231, 76, 60, 0.85)',
                'rgba(241, 196, 15, 0.85)', 'rgba(155, 89, 182, 0.85)', 'rgba(230, 126, 34, 0.85)',
                'rgba(26, 188, 156, 0.85)', 'rgba(149, 165, 166, 0.85)', 'rgba(243, 156, 18, 0.85)',
                'rgba(52, 73, 94, 0.85)'
            ];
            const bgAll = dataAll.map((_, i) => colorsPool[i % colorsPool.length]);

            const showValueLabels = labelsAll.length <= 60;

            const chartAll = new Chart(ctxAll, {
                type: 'bar',
                data: {
                    labels: labelsAll,
                    datasets: [{
                        label: 'Bil. Minat',
                        data: dataAll,
                        backgroundColor: bgAll
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    layout: {
                        padding: {
                            right: 24,
                            left: 8,
                            bottom: 24,
                            top: 16
                        }
                    }, // ruang tambahan
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
                                callback: (v) => Number.isInteger(v) ? v : ''
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
                                callback: (value, index) => {
                                    const label = labelsAll[index] || '';
                                    return label.length > 60 ? label.slice(0, 57) + '…' : label;
                                }
                            }
                        }
                    }
                },
                plugins: [{
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
                        dataset.data.forEach((v, i) => {
                            const bar = meta.data[i];
                            if (!bar) return;
                            const val = dataset.data[i] || 0;
                            const pct = totalAllBars ? ((val / totalAllBars) * 100).toFixed(
                                1) : '0.0';
                            // Tulis betul-betul di hujung bar, tapi jangan melepasi chartArea.right
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

            // Sesetengah layout perlukan resize kecil supaya Chart.js “reflow” betul
            setTimeout(() => chartAll.resize(), 0);
        });
    </script>
@endsection
