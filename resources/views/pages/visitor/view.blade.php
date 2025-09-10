@extends('layouts.master')

@section('content')
    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Pengurusan Data Pengunjung</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('visitor') }}">Senarai Data Pengunjung</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Maklumat {{ ucfirst($visitor->full_name) }}</li>
                </ol>
            </nav>
        </div>
        @hasanyrole('Superadmin|Admin')
        <div class="ms-auto">
            <a href="{{ route('visitor.edit', $visitor->id) }}">
                <button type="button" class="btn btn-primary mt-2 mt-lg-0">Kemaskini Maklumat</button>
            </a>
        </div>
        @endhasanyrole
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">Maklumat {{ ucfirst($visitor->full_name) }}</h6>
    <hr />

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama Penuh</th>
                            <td>{{ ucfirst($visitor->full_name) ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>No. Telefon</th>
                            <td>{{ $visitor->phone ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Emel</th>
                            <td>{{ $visitor->email ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Program/Bidang</th>
                            <td>
                                @if ($visitor->program_bidang)
                                    <ul class="mb-0">
                                        @foreach (explode(',', $visitor->program_bidang) as $item)
                                            <li>{{ trim($item) }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $visitor->lokasi ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tarikh Respons</th>
                            <td>{{ optional($visitor->response_at)->format('Y-m-d H:i') ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page Wrapper -->
@endsection
