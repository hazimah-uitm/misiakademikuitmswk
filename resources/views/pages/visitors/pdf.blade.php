<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANALISA MAKLUM BALAS PELANGGAN BAHAGIAN INFOSTRUKTUR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 5px;
            padding: 5px;
            text-align: center;
            font-size: 12px;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
            page-break-inside: avoid;
            margin-bottom: 20px;
            background-color: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #03244c;
            color: rgb(255, 255, 255);
            font-size: 12px;
            text-align: center;
        }

        tfoot {
            background-color: #cecece;
            color: rgb(0, 0, 0);
            font-weight: bold;
            font-size: 12px;
            text-align: center;
        }


        .header-image {
            text-align: center;
            margin-bottom: 15px;
        }

        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .chart-container {
            padding-top: 10px;
            page-break-inside: avoid;
            background-color: #f1faff;
            margin-bottom: 15px;
        }

        .chart-container img {
            width: 100%;
            max-width: 500px;
        }

        .table-title {
            background-color: #03244c;
            padding: 5px;
            color: white;
            font-weight: bold;
            font-size: 13px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header-image">
        <img src="{{ public_path('assets/images/Logo-Infostruktur.svg') }}" alt="UiTM Logo" width="180">
    </div>

    <h2 class="report-title">
        ANALISA MAKLUM BALAS PELANGGAN BAHAGIAN INFOSTRUKTUR <br> UiTM CAWANGAN SARAWAK
        <span style="text-transform: uppercase">
            (@if ($selectedMonth)
                {{ $selectedMonth }} -
            @endif
            {{ $selectedYear }}
            @if ($selectedCampus)
                | {{ strtoupper($selectedCampus) }}
            @endif
            )
        </span>
    </h2>

    <div class="chart-container">
        <img src="{{ $unitsRatingChart }}" alt="Units Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($unitsRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container" style="page-break-before: always;">
        <img src="{{ $mkomRatingChart }}" alt="MKOM Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($mkomRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $wifiRatingChart }}" alt="Wifi Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($wifiRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $wiredRatingChart }}" alt="Wired Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($wiredRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $mainSystemRatingChart }}" alt="Main System Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($mainsystemRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $emelAppsRatingChart }}" alt="Emel Apps Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($emelappsRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $kadRatingChart }}" alt="Kad Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($kadRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $peralatanBSURatingChart }}" alt="Peralatan BSU Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($peralatanBSURatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $webexRatingChart }}" alt="Webex Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($webexRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $mkomBSURatingChart }}" alt="MKOM BSU Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($mkomBSURatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $softwareBSURatingChart }}" alt="Software BSU Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($softwareBSURatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $staffRatingChart }}" alt="Staff Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($staffRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $helpdeskRatingChart }}" alt="Helpdesk Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($helpdeskRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container">
        <img src="{{ $grantICTRatingChart }}" alt="Grant Rating Chart">
    </div>
    <table>
        <thead>
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
                            $percent = $total > 0 ? number_format(($count / $total) * 100, 2) : '0.00';
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
                <td>Jumlah</td>
                @foreach ($grantICTRatings as $year => $data)
                    @php
                        $total = array_sum(array_values($data));
                    @endphp
                    <td colspan="2">{{ $total }}</td>
                @endforeach
                <td>{{ array_sum($ratingTotals) }}</td>
            </tr>
        </tfoot>
    </table>

</body>

</html>
