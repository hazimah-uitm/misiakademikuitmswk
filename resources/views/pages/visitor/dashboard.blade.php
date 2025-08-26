@extends('layouts.master')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <h2 class="fw-bold text-dark mb-3 mb-md-0 d-flex align-items-center flex-wrap" style="font-size: 1.8rem;">
                DASHBOARD MISI AKADEMIK {{ $tahun }}
            </h2>

            <form id="searchFilterForm" method="GET" action="{{ route('home') }}" class="d-flex flex-wrap align-items-center gap-2">
                <div>
                    <input type="text" name="search" class="form-control form-select-sm rounded-pill shadow-sm"
                        placeholder="Carian Data Pengunjung.." value="{{ request('search') }}">
                </div>
                {{-- Tahun --}}
                <div>
                    <select name="tahun" class="form-select form-select-sm shadow-sm rounded-pill"
                        onchange="this.form.submit()">
                        <option value="">📅 Semua Tahun</option>
                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Lokasi --}}
                <div>
                    <select name="lokasi" class="form-select form-select-sm shadow-sm rounded-pill"
                        onchange="this.form.submit()">
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

        <div class="card border shadow-sm h-100">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Top 10 Program/Bidang</h6>
                <canvas id="programChart" height="100"></canvas>
                @if (count($programLabels) === 0)
                    <div class="text-muted small mt-2">Tiada data program untuk kombinasi filter semasa.</div>
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
        const programLabels = @json($programLabels);
        const programData = @json($programData);

        // Top Program (bar)
        new Chart(document.getElementById('programChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: programLabels,
                datasets: [{
                    label: 'Bil. Minat',
                    data: programData
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                }
            }
        });
    </script>
@endsection
