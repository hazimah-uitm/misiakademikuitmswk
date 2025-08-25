@extends('layouts.master')

@section('content')
    <div class="container-fluid">

        <div class="row mb-3 sticky-md-top" style="top: 60px; z-index: 1030;">
            <div class="col">
                <!-- Filter and Export PDF Buttons -->
                <div class="d-flex justify-content-end mb-2 gap-2">
                    <button class="btn btn-primary rounded" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                        <i class="bx bx-filter"></i>
                    </button>

                    <form id="exportPdfForm" method="POST" action="{{ route('export-maklumbalaspelanggan-pdf') }}"
                        target="_blank">
                        {{ csrf_field() }}
                        <input type="hidden" name="campus"
                            value="{{ request('campus') ? implode(',', (array) request('campus')) : '' }}">
                        <input type="hidden" name="month"
                            value="{{ request('month') ? implode(',', (array) request('month')) : '' }}">
                        <input type="hidden" name="year"
                            value="{{ request('year') ? implode(',', (array) request('year')) : '' }}">
                        <input type="hidden" name="units_rating_chart" id="unitsRatingChartInput">
                        <input type="hidden" name="mkom_rating_chart" id="mkomRatingChartInput">
                        <input type="hidden" name="wifi_rating_chart" id="wifiRatingChartInput">
                        <input type="hidden" name="wired_rating_chart" id="wiredRatingChartInput">
                        <input type="hidden" name="main_system_rating_chart" id="mainsystemRatingChartInput">
                        <input type="hidden" name="emel_apps_rating_chart" id="emelappsRatingChartInput">
                        <input type="hidden" name="kad_rating_chart" id="kadRatingChartInput">
                        <input type="hidden" name="peralatan_bsu_rating_chart" id="peralatanBSURatingChartInput">
                        <input type="hidden" name="webex_rating_chart" id="webexRatingChartInput">
                        <input type="hidden" name="mkom_bsu_rating_chart" id="mkomBSURatingChartInput">
                        <input type="hidden" name="software_bsu_rating_chart" id="softwareBSURatingChartInput">
                        <input type="hidden" name="staff_rating_chart" id="staffRatingChartInput">
                        <input type="hidden" name="helpdesk_rating_chart" id="helpdeskRatingChartInput">
                        <input type="hidden" name="grant_ict_rating_chart" id="grantICTRatingChartInput">

                        <!-- Add ID and change type to button -->
                        <button type="button" class="btn btn-info" id="exportPdfBtn">
                            <i class="bx bxs-file-pdf"></i> Export PDF
                        </button>
                    </form>

                </div>

                <!-- Collapsible Filter Section -->
                <div class="collapse" id="filterSection">
                    <div class="card card-body">
                        <form action="{{ route('dashboard-maklumbalaspelanggan') }}" method="GET" id="searchForm"
                            class="d-flex flex-column gap-3">
                            <div class="row row-cols-auto g-2">
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="dropdown w-100">
                                        <button
                                            class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                            type="button" id="campusDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="campusDropdownLabel">Kampus</span>
                                            <i class="bi bi-caret-down-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu w-100 p-3" aria-labelledby="campusDropdown"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($campusFilter as $campus)
                                                <li>
                                                    <div class="form-check d-flex align-items-center">
                                                        <input type="checkbox" name="campus[]" value="{{ $campus }}"
                                                            class="form-check-input me-2 campus-checkbox"
                                                            id="campus-{{ $loop->index }}"
                                                            style="transform: scale(1.3); margin-right: 8px;"
                                                            {{ in_array($campus, request('campus', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label w-100"
                                                            for="campus-{{ $loop->index }}">{{ $campus }}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="dropdown w-100">
                                        <button
                                            class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                            type="button" id="monthDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span id="monthDropdownLabel">Bulan</span>
                                            <i class="bi bi-caret-down-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu w-100 p-3" aria-labelledby="monthDropdown"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @for ($m = 1; $m <= 12; $m++)
                                                <li>
                                                    <div class="form-check d-flex align-items-center">
                                                        <input type="checkbox" name="month[]"
                                                            value="{{ $m }}"
                                                            class="form-check-input me-2 month-checkbox"
                                                            id="month-{{ $m }}"
                                                            style="transform: scale(1.3); margin-right: 8px;"
                                                            {{ in_array($m, request('month', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label w-100"
                                                            for="month-{{ $m }}">
                                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <div class="dropdown w-100">
                                        <button
                                            class="btn btn-light dropdown-toggle w-100 text-start d-flex justify-content-between align-items-center"
                                            type="button" id="yearDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <span
                                                id="yearDropdownLabel">{{ request('year') ? implode(', ', (array) request('year')) : 'Tahun' }}</span>
                                            <i class="bi bi-caret-down-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu w-100 p-3" aria-labelledby="yearDropdown"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($availableYears as $year)
                                                <li>
                                                    <div class="form-check d-flex align-items-center">
                                                        <input type="checkbox" name="year[]"
                                                            value="{{ $year }}"
                                                            class="form-check-input me-2 year-checkbox"
                                                            id="year-{{ $year }}"
                                                            style="transform: scale(1.3); margin-right: 8px;"
                                                            {{ in_array($year, (array) request('year', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label w-100"
                                                            for="year-{{ $year }}">
                                                            {{ $year }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <!-- Reset Button aligned to the right -->
                                <div class="col-lg-12 text-end">
                                    <button type="button" class="btn btn-secondary rounded" id="resetButton">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mb-4">
            <h4 class="fw-bold">ANALISA MAKLUM BALAS PELANGGAN BAHAGIAN INFOSTRUKTUR UiTM CAWANGAN SARAWAK</h4>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="unitsRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($unitsRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($unitsRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($unitsRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($unitsRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($unitsRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($unitsRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="mkomRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($mkomRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($mkomRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($mkomRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($mkomRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($mkomRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($mkomRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="wifiRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($wifiRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($wifiRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($wifiRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($wifiRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($wifiRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($wifiRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="wiredRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($wiredRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($wiredRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($wiredRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($wiredRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($wiredRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($wiredRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="mainsystemRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($mainsystemRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($mainsystemRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($mainsystemRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($mainsystemRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($mainsystemRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($mainsystemRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="emelappsRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($emelappsRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($emelappsRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($emelappsRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($emelappsRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($emelappsRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($emelappsRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="kadRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($kadRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($kadRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($kadRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($kadRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($kadRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($kadRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="peralatanBSURatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($peralatanBSURatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($peralatanBSURatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($peralatanBSURatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($peralatanBSURatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($peralatanBSURatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($peralatanBSURatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="webexRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($webexRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($webexRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($webexRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($webexRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($webexRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($webexRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="mkomBSURatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($mkomBSURatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($mkomBSURatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($mkomBSURatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($mkomBSURatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($mkomBSURatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($mkomBSURatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="softwareBSURatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($softwareBSURatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($softwareBSURatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($softwareBSURatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($softwareBSURatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($softwareBSURatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($softwareBSURatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="staffRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($staffRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($staffRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($staffRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($staffRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($staffRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($staffRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="helpdeskRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($helpdeskRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($helpdeskRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($helpdeskRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($helpdeskRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($helpdeskRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($helpdeskRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div class="row justify-content-center flex-grow-1">
                            <canvas id="grantICTRatingChart" style="height: 450px; width: 100%;"></canvas>
                        </div>
                        <div class="table-responsive mt-1">
                            <table class="table table-bordered table-striped table-sm text-center align-middle">
                                <thead style="background-color: #002e6e; color: rgb(255, 255, 255);">
                                    <tr>
                                        <th rowspan="2">Skor</th>
                                        @foreach ($grantICTRatings as $year => $data)
                                            <th colspan="2">{{ $year }}</th>
                                        @endforeach
                                        <th rowspan="2">Jumlah</th> {{-- Kolum jumlah per skor --}}
                                    </tr>
                                    <tr>
                                        @foreach ($grantICTRatings as $year => $data)
                                            <th>Jumlah</th>
                                            <th>%</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $allRatings = [0, 1, 2, 3, 4, 5];
                                        $yearlyTotals = [];
                                        $ratingTotals = [];

                                        foreach ($grantICTRatings as $year => $data) {
                                            $yearlyTotals[$year] = array_sum(array_values($data));
                                        }

                                        foreach ($allRatings as $rating) {
                                            $ratingTotals[$rating] = 0;
                                            foreach ($grantICTRatings as $year => $data) {
                                                $ratingTotals[$rating] += $data[$rating] ?? 0;
                                            }
                                        }
                                    @endphp
                                    @foreach ($allRatings as $rating)
                                        <tr>
                                            <td><strong>{{ $rating === 0 ? 'NA' : $rating }}</strong></td>
                                            @foreach ($grantICTRatings as $year => $data)
                                                @php
                                                    $count = $data[$rating] ?? 0;
                                                    $total = $yearlyTotals[$year] ?? 1;
                                                    $percent =
                                                        $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
                                                @endphp
                                                <td>{{ $count }}</td>
                                                <td>{{ $percent }}%</td>
                                            @endforeach
                                            <td><strong>{{ $ratingTotals[$rating] }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Jumlah</th>
                                        @foreach ($grantICTRatings as $year => $data)
                                            @php
                                                $total = array_sum(array_values($data));
                                            @endphp
                                            <th colspan="2">{{ $total }}</th>
                                        @endforeach
                                        <th>{{ array_sum($ratingTotals) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- units by year -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('unitsRatingChart').getContext('2d');
            const ratingData = @json($unitsRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'PENGURUSAN ADUAN DAN PERKHIDMATAN ICT (SISTEM UNITS)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- mkom -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('mkomRatingChart').getContext('2d');
            const ratingData = @json($mkomRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'PERKHIDMATAN MAKMAL KOMPUTER',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- wifi-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('wifiRatingChart').getContext('2d');
            const ratingData = @json($wifiRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'KEMUDAHAN INTERNET TANPA WAYAR (WIFI)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- wired -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('wiredRatingChart').getContext('2d');
            const ratingData = @json($wiredRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'KEMUDAHAN INTERNET BERWAYAR',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- main system -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('mainsystemRatingChart').getContext('2d');
            const ratingData = @json($mainsystemRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'PENGGUNAAN SISTEM UTAMA UNIVERSITI',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- emel apss -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('emelappsRatingChart').getContext('2d');
            const ratingData = @json($emelappsRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'PENGGUNAAN EMEL UNIVERSITI DAN APLIKASI BERKAITAN',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- kad -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('kadRatingChart').getContext('2d');
            const ratingData = @json($kadRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'PENGURUSAN KAD PELBAGAI GUNA (UiTM CARD MANAGEMENT SYSTEM)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- peraltan ict bsu -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('peralatanBSURatingChart').getContext('2d');
            const ratingData = @json($peralatanBSURatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'TEMPAHAN PERALATAN ICT (SISTEM BSU)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- webex -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('webexRatingChart').getContext('2d');
            const ratingData = @json($webexRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'TEMPAHAN TELESIDANG / PENGURUSAN ACARA (SISTEM BSU / UNITS)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- mkom bsu -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('mkomBSURatingChart').getContext('2d');
            const ratingData = @json($mkomBSURatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'TEMPAHAN MAKMAL KOMPUTER (SISTEM BSU)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- software bsu-->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('softwareBSURatingChart').getContext('2d');
            const ratingData = @json($softwareBSURatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'TEMPAHAN PERISIAN LESEN UiTM (SISTEM BSU)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- staf -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('staffRatingChart').getContext('2d');
            const ratingData = @json($staffRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'KECEKAPAN STAF DALAM MENYELESAIKAN ADUAN ICT',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- helpdesk -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('helpdeskRatingChart').getContext('2d');
            const ratingData = @json($helpdeskRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'PERKHIDMATAN KAUNTER / HELPDESK / WHATSAPP',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- grant ict -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('grantICTRatingChart').getContext('2d');
            const ratingData = @json($grantICTRatings); // Format: {2023: {'NA': 12, 1: 33, ..., 5: 55}}

            const years = Object.keys(ratingData);
            const ratings = [0, 1, 2, 3, 4, 5];

            // Define full labels for legend
            const fullLabels = {
                0: 'NA - Tidak Berkaitan',
                1: '1 - Sangat Tidak Memuaskan',
                2: '2 - Tidak Memuaskan',
                3: '3 - Sederhana',
                4: '4 - Memuaskan',
                5: '5 - Sangat Memuaskan'
            };

            const colors = {
                0: '#999999',
                1: '#FF4136',
                2: '#FF851B',
                3: '#FFDC00',
                4: '#2ECC40',
                5: '#0074D9'
            };

            const datasets = ratings.map(rating => {
                return {
                    label: fullLabels[rating],
                    data: years.map(year => ratingData[year][rating] ?? 0),
                    backgroundColor: colors[rating],
                };
            });

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: years,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: [
                                'PENGURUSAN GERAN PERKAKASAN ICT (SISTEM UNITS)',
                                @php
                                    $line2 = [];
                                    if (!empty($selectedMonth) || !empty($selectedYear)) {
                                        $tempoh = ($selectedMonth ? $selectedMonth . ' - ' : '') . $selectedYear;
                                        if (!empty($selectedCampus)) {
                                            $tempoh .= ' | ' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus);
                                        }
                                        echo json_encode("($tempoh)");
                                    } elseif (!empty($selectedCampus)) {
                                        echo json_encode('(' . strtoupper(is_array($selectedCampus) ? implode(', ', $selectedCampus) : $selectedCampus) . ')');
                                    }

                                @endphp
                            ],
                            color: '#000',
                            font: {
                                size: 15,
                                weight: 'bold',

                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const yearIndex = context.dataIndex;
                                    const total = context.chart.data.datasets.reduce((sum, ds) => sum +
                                        ds.data[yearIndex], 0);
                                    const percent = total > 0 ? ((value / total) * 100).toFixed(2) :
                                        '0.00';
                                    return `${context.dataset.label}: ${value} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                color: '#000'
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            stacked: false,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        }
                    },
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 10
                        }
                    }
                },
                plugins: [{
                    id: 'barTopLabels',
                    afterDatasetsDraw(chart) {
                        const {
                            ctx,
                            data
                        } = chart;
                        ctx.save();
                        ctx.textAlign = 'center';
                        ctx.font = '12px Arial';
                        ctx.fillStyle = '#000';

                        data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);

                            dataset.data.forEach((value, index) => {
                                const bar = meta.data[index];
                                if (!bar) return;

                                const total = data.datasets.reduce((sum, ds) =>
                                    sum + ds.data[index], 0);
                                const percent = total > 0 ? ((value / total) * 100)
                                    .toFixed(2) : '0.00';

                                // Adjust vertical spacing
                                ctx.fillText(value, bar.x, bar.y - 20); // total
                                ctx.fillText(`(${percent}%)`, bar.x, bar.y -
                                    8); // percent with spacing and %
                            });
                        });

                        ctx.restore();
                    }
                }]
            });
        });
    </script>

    <!-- Filter -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let timeout;

            // Detect changes in checkboxes and submit the form
            document.querySelectorAll(
                ".campus-checkbox, .month-checkbox, .year-checkbox"
            ).forEach(checkbox => {
                checkbox.addEventListener("change", function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        document.getElementById("searchForm").submit();
                    }, 1000);
                });
            });

            // Reset button to clear selections
            document.getElementById("resetButton").addEventListener("click", function() {
                document.querySelectorAll(
                    ".campus-checkbox,.month-checkbox, .year-checkbox"
                ).forEach(checkbox => {
                    checkbox.checked = false;
                });
                document.getElementById("searchForm").submit();
            });

            function updateDropdownLabel(dropdownId, labelId, checkboxClass, defaultText) {
                const checkboxes = document.querySelectorAll(`.${checkboxClass}`);
                const selectedValues = [];

                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedValues.push(checkbox.nextElementSibling.innerText.trim());
                    }
                });

                const label = document.getElementById(labelId);
                if (selectedValues.length > 0) {
                    // If more than one item is selected, show the format "Pilih PIC (2)"
                    label.textContent = `${defaultText} (${selectedValues.length})`;
                } else {
                    label.textContent = defaultText;
                }
            }

            function attachListeners(dropdownId, labelId, checkboxClass, defaultText) {
                const checkboxes = document.querySelectorAll(`.${checkboxClass}`);
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener("change", function() {
                        updateDropdownLabel(dropdownId, labelId, checkboxClass, defaultText);
                    });
                });

                // Initial update on page load
                updateDropdownLabel(dropdownId, labelId, checkboxClass, defaultText);
            }

            // Apply to each filter
            attachListeners("campusDropdown", "campusDropdownLabel", "campus-checkbox", "Kampus");
            attachListeners("monthDropdown", "monthDropdownLabel", "month-checkbox", "Bulan");
            attachListeners("yearDropdown", "yearDropdownLabel", "year-checkbox", "Tahun");
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('exportPdfBtn').addEventListener('click', function() {
                let unitsRatingChart = document.getElementById('unitsRatingChart');
                let mkomRatingChart = document.getElementById('mkomRatingChart');
                let wifiRatingChart = document.getElementById('wifiRatingChart');
                let wiredRatingChartChart = document.getElementById('wiredRatingChartChart');
                let mainsystemRatingChart = document.getElementById('mainsystemRatingChart');
                let emelappsRatingChart = document.getElementById('emelappsRatingChart');
                let kadRatingChart = document.getElementById('kadRatingChart');
                let peralatanBSURatingChartChart = document.getElementById('peralatanBSURatingChartChart');
                let webexRatingChart = document.getElementById('webexRatingChart');
                let mkomBSURatingChart = document.getElementById('mkomBSURatingChart');
                let softwareBSURatingChart = document.getElementById('softwareBSURatingChart');
                let staffRatingChart = document.getElementById('staffRatingChart');
                let helpdeskRatingChart = document.getElementById('helpdeskRatingChart');
                let grantICTRatingChart = document.getElementById('grantICTRatingChart');

                let unitsRatingChartImg = unitsRatingChart.toDataURL('image/png');
                let mkomRatingChartImg = mkomRatingChart.toDataURL('image/png');
                let wifiRatingChartImg = wifiRatingChart.toDataURL('image/png');
                let wiredRatingChartImg = wiredRatingChart.toDataURL('image/png');
                let mainsystemRatingChartImg = mainsystemRatingChart.toDataURL('image/png');
                let emelappsRatingChartImg = emelappsRatingChart.toDataURL('image/png');
                let kadRatingChartImg = kadRatingChart.toDataURL('image/png');
                let peralatanBSURatingChartImg = peralatanBSURatingChart.toDataURL('image/png');
                let webexRatingChartImg = webexRatingChart.toDataURL('image/png');
                let mkomBSURatingChartImg = mkomBSURatingChart.toDataURL('image/png');
                let softwareBSURatingChartImg = softwareBSURatingChart.toDataURL('image/png');
                let staffRatingChartImg = staffRatingChart.toDataURL('image/png');
                let helpdeskRatingChartImg = helpdeskRatingChart.toDataURL('image/png');
                let grantICTRatingChartImg = grantICTRatingChart.toDataURL('image/png');

                document.getElementById('unitsRatingChartInput').value = unitsRatingChartImg;
                document.getElementById('mkomRatingChartInput').value = mkomRatingChartImg;
                document.getElementById('wifiRatingChartInput').value = wifiRatingChartImg;
                document.getElementById('wiredRatingChartInput').value = wiredRatingChartImg;
                document.getElementById('mainsystemRatingChartInput').value = mainsystemRatingChartImg;
                document.getElementById('emelappsRatingChartInput').value = emelappsRatingChartImg;
                document.getElementById('kadRatingChartInput').value = kadRatingChartImg;
                document.getElementById('peralatanBSURatingChartInput').value = peralatanBSURatingChartImg;
                document.getElementById('webexRatingChartInput').value = webexRatingChartImg;
                document.getElementById('mkomBSURatingChartInput').value = mkomBSURatingChartImg;
                document.getElementById('softwareBSURatingChartInput').value = softwareBSURatingChartImg;
                document.getElementById('staffRatingChartInput').value = staffRatingChartImg;
                document.getElementById('helpdeskRatingChartInput').value = helpdeskRatingChartImg;
                document.getElementById('grantICTRatingChartInput').value = grantICTRatingChartImg;

                document.getElementById('exportPdfForm').submit();
            });
        });
    </script>
@endsection
