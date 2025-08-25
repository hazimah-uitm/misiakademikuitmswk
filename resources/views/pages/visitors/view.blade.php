@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Maklum Balas Pelanggan</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('maklumbalaspelanggan') }}">Senarai Maklum Balas Pelanggan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Maklumat {{ $maklumbalaspelanggan->tarikh_respon }} {{ $maklumbalaspelanggan->email }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">Maklumat {{ $maklumbalaspelanggan->tarikh_respon }} {{ $maklumbalaspelanggan->email }}</h6>
    <hr />

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Tarikh Respon</th>
                            <td>{{ $maklumbalaspelanggan->tarikh_respon }} {{ $maklumbalaspelanggan->masa_respon }}</td>
                        </tr>
                        <tr>
                            <th>Emel</th>
                            <td>{{ $maklumbalaspelanggan->email }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $maklumbalaspelanggan->kategori }}</td>
                        </tr>
                        <tr>
                            <th>Jantina</th>
                            <td>{{ $maklumbalaspelanggan->jantina }}</td>
                        </tr>
                        <tr>
                            <th>Kampus</th>
                            <td>{{ $maklumbalaspelanggan->campus }}</td>
                        </tr>
                        <tr>
                            <th>Bahagian/Unit/Fakulti</th>
                            <td>{{ $maklumbalaspelanggan->bahagian_unit_fakulti }}</td>
                        </tr>
                        <tr>
                            <th>Sistem Pengurusan Perkhidmatan ICT (UNITS)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_units }}</td>
                        </tr>
                        <tr>
                            <th>Perkhidmatan Makmal Komputer</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_mkom }}</td>
                        </tr>
                        <tr>
                            <th>Kemudahan Internet Berwayar</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_wired_internet }}</td>
                        </tr>
                        <tr>
                            <th>Kemudahan Internet Tanpa Wayar (Wi-Fi)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_wifi }}</td>
                        </tr>
                        <tr>
                            <th>Penggunaan Sistem Utama Universiti</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_main_system }}</td>
                        </tr>
                        <tr>
                            <th>Emel Universiti dan Aplikasi Berkaitan</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_emel_apps }}</td>
                        </tr>
                        <tr>
                            <th>Pengurusan Kad Pelbagai Guna</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_kad }}</td>
                        </tr>
                        <tr>
                            <th>Tempahan Peralatan ICT (BSU)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_peralatan_bsu }}</td>
                        </tr>
                        <tr>
                            <th>Tempahan Telesidang  - Akaun Webex UiTM / Video Conference (BSU/UNITS)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_webex }}</td>
                        </tr>
                        <tr>
                            <th>Tempahan Makmal Komputer (BSU)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_mkom_bsu }}</td>
                        </tr>
                        <tr>
                            <th>Tempahan Perisian Lesen UiTM (BSU)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_software_bsu }}</td>
                        </tr>
                        <tr>
                            <th>Kecekapan Staf Dalam Menyelesaikan Aduan ICT</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_staf }}</td>
                        </tr>
                        <tr>
                            <th>Perkhidmatan Kaunter / Helpdesk / WhatsApp</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_helpdesk }}</td>
                        </tr>
                        <tr>
                            <th>Pengurusan Geran Perkakasan ICT (UNITS)</th>
                            <td>{{ $maklumbalaspelanggan->perkhidmatan_ict_grant }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Wrapper -->
@endsection
