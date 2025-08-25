@extends('layouts.master')
@section('content')
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Pengurusan Data Pengunjung</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Senarai Data Pengunjung</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->
<h6 class="mb-0 text-uppercase">Senarai Data Pengunjung</h6>
<hr />
@if (session('success'))
<div class="alert alert-success mt-2">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger mt-2">
    {{ session('error') }}
</div>
@endif
<div class="card">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-lg-12">
                <form action="{{ route('maklumbalaspelanggan.search') }}" method="GET" id="searchForm"
                    class="d-lg-flex align-items-center gap-3">
                    <div class="input-group">
                        <input type="text" class="form-control rounded" placeholder="Carian..." name="search"
                            value="{{ request('search') }}" id="searchInput">

                        <select name="campus" class="form-select form-select-sm ms-2 rounded" id="campusFilter">
                            <option value="all" {{ request('campus', 'all') == 'all' ? 'selected' : '' }}>Semua Kampus</option>
                            @foreach ($campusFilter as $campus)
                            <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>
                                {{ $campus }}
                            </option>
                            @endforeach
                        </select>

                        <select name="month" class="form-select form-select-sm ms-2 rounded" id="monthFilter">
                            <option value="all" {{ request('month') == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                </option>
                                @endfor
                        </select>

                        <select name="year" class="form-select form-select-sm ms-2 rounded" id="yearFilter">
                            <option value="all" {{ request('year') == 'all' ? 'selected' : '' }}>Semua Tahun</option>
                            @for ($y = now()->year; $y >= now()->year - 10; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                            @endfor
                        </select>

                        <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">
                        <button type="submit" class="btn btn-primary ms-1 rounded" id="searchButton">
                            <i class="bx bx-search"></i>
                        </button>
                        <button type="button" class="btn btn-secondary ms-1 rounded" id="resetButton">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12 d-flex justify-content-end align-items-center gap-2">
                <form action="{{ route('maklumbalaspelanggan.import') }}" method="POST" enctype="multipart/form-data"
                    class="d-flex align-items-center">
                    {{ csrf_field() }}
                    <div class="form-group mb-0">
                        <input type="file" name="file" class="form-control form-control-sm" required>
                    </div>
                    <button type="submit" class="btn btn-info ms-2">Import</button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-striped table-hover">
                <thead class="table-light text-uppercase">
                    <tr>
                        <th style="width: 30px;">#</th>
                        <th class="text-wrap" style="width: 90px;">Tarikh</th>
                        <th class="text-wrap" style="width: 100px;">Kampus</th>
                        <th class="text-wrap" style="width: 100px;">UNITS</th>
                        <th class="text-wrap" style="width: 100px;">MKOM</th>
                        <th class="text-wrap" style="width: 120px;">Internet Berwayar</th>
                        <th class="text-wrap" style="width: 100px;">WiFi</th>
                        <th class="text-wrap" style="width: 120px;">Sistem Utama</th>
                        <th class="text-wrap" style="width: 130px;">Emel Apps</th>
                        <th class="text-wrap" style="width: 120px;">Kad</th>
                        <th class="text-wrap" style="width: 120px;">Tempahan Peralatan</th>
                        <th class="text-wrap" style="width: 140px;">Telesidang</th>
                        <th class="text-wrap" style="width: 120px;">Tempahan MKOM</th>
                        <th class="text-wrap" style="width: 120px;">Tempahan Perisian</th>
                        <th class="text-wrap" style="width: 140px;">Kecekapan Staf</th>
                        <th class="text-wrap" style="width: 160px;">Helpdesk</th>
                        <th class="text-wrap" style="width: 120px;">Geran ICT</th>
                        <th style="width: 80px;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($maklumbalaspelangganList) > 0)
                        @foreach ($maklumbalaspelangganList as $maklumbalaspelanggan)
                            <tr>
                                <td>{{ ($maklumbalaspelangganList->currentPage() - 1) * $maklumbalaspelangganList->perPage() + $loop->iteration }}</td>
                                <td>{{ $maklumbalaspelanggan->tarikh_respon }}</td>
                                <td>{{ $maklumbalaspelanggan->campus }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_units }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_mkom }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_wired_internet }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_wifi }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_main_system }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_emel_apps }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_kad }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_peralatan_bsu }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_webex }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_mkom_bsu }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_software_bsu }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_staf }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_helpdesk }}</td>
                                <td>{{ $maklumbalaspelanggan->perkhidmatan_ict_grant }}</td>
                                <td>
                                    <a type="button" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Papar">
                                        <span class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#showModal{{ $maklumbalaspelanggan->id }}">
                                            <i class="bx bx-show"></i>
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="18">Tiada rekod</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        
        
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="mr-2 mx-1">Jumlah rekod per halaman</span>
                <form action="{{ route('maklumbalaspelanggan.search') }}" method="GET" id="perPageForm"
                    class="d-flex align-items-center">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="campus" value="{{ request('campus') }}">
                    <input type="hidden" name="month" value="{{ request('month') }}">
                    <input type="hidden" name="year" value="{{ request('year') }}">
                    <select name="perPage" id="perPage" class="form-select form-select-sm"
                        onchange="document.getElementById('perPageForm').submit()">
                        <option value="10" {{ Request::get('perPage') == '10' ? 'selected' : '' }}>10</option>
                        <option value="20" {{ Request::get('perPage') == '20' ? 'selected' : '' }}>20</option>
                        <option value="30" {{ Request::get('perPage') == '30' ? 'selected' : '' }}>30</option>
                    </select>
                </form>
            </div>

            <div class="d-flex justify-content-end align-items-center">
                <span class="mx-2 mt-2 small text-muted">
                    Menunjukkan {{ $maklumbalaspelangganList->firstItem() }} hingga {{ $maklumbalaspelangganList->lastItem() }} daripada
                    {{ $maklumbalaspelangganList->total() }} rekod
                </span>
                <div class="pagination-wrapper">
                    {{ $maklumbalaspelangganList->appends([
                                'search' => request('search'),
                                'month' => request('month'),
                                'year' => request('year'),
                                'campus' => request('campus'),
                                'perPage' => request('perPage'),
                            ])->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Show Modal -->
@foreach ($maklumbalaspelangganList as $maklumbalaspelanggan)
<div class="modal fade" id="showModal{{ $maklumbalaspelanggan->id }}" tabindex="-1"
    aria-labelledby="showModalLabel{{ $maklumbalaspelanggan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="showModalLabel{{ $maklumbalaspelanggan->id }}">Maklumat:
                    {{ $maklumbalaspelanggan->tarikh_respon }}
                </h5>
                <div class="d-flex align-items-center">
                    <a href="{{ route('maklumbalaspelanggan.show', $maklumbalaspelanggan->id) }}" class="btn btn-link me-0" style="padding: 0"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Paparan Penuh">
                        <i class='bx bx-fullscreen'></i>
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tutup"></button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <table class="table table-borderless table-striped table-hover">
                    <tbody>
                        <tr>
                            <th scope="row">Data Pengunjung</th>
                            <td>{{ $maklumbalaspelanggan->tarikh_respon }} {{ $maklumbalaspelanggan->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tarikh Respon</th>
                            <td>{{ $maklumbalaspelanggan->tarikh_respon }} {{ $maklumbalaspelanggan->masa_respon }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Emel</th>
                            <td>{{ $maklumbalaspelanggan->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kategori</th>
                            <td>{{ $maklumbalaspelanggan->kategori }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Jantina</th>
                            <td>{{ $maklumbalaspelanggan->jantina }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kampus</th>
                            <td>{{ $maklumbalaspelanggan->campus }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Bahagian/Unit/Fakulti</th>
                            <td>{{ $maklumbalaspelanggan->bahagian_unit_fakulti }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Sistem Pengurusan Perkhidmatan ICT (UNITS)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_units }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Perkhidmatan Makmal Komputer</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_mkom }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kemudahan Internet Berwayar</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_wired_internet }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kemudahan Internet Tanpa Wayar (Wi-Fi)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_wifi }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Penggunaan Sistem Utama Universiti</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_main_system }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Emel Universiti dan Aplikasi Berkaitan</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_emel_apps }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Pengurusan Kad Pelbagai Guna</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_kad }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tempahan Peralatan ICT (BSU)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_peralatan_bsu }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tempahan Telesidang  - Akaun Webex UiTM / Video Conference (BSU/UNITS)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_webex }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tempahan Makmal Komputer (BSU)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_mkom_bsu }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tempahan Perisian Lesen UiTM (BSU)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_software_bsu }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Kecekapan Staf Dalam Menyelesaikan Aduan ICT</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_staf }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Perkhidmatan Kaunter / Helpdesk / WhatsApp</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_helpdesk }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Pengurusan Geran Perkakasan ICT (UNITS)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_ict_grant }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit the form on input change
        document.getElementById('searchInput').addEventListener('input', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('campusFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('monthFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('yearFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        document.getElementById('resetButton').addEventListener('click', function() {
            // Redirect to the base route to clear query parameters
            window.location.href = "{{ route('maklumbalaspelanggan') }}";
        });

    });
</script>
@endsection