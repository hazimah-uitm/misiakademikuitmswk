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
                    <li class="breadcrumb-item active" aria-current="page">{{ $str_mode }} Data Pengunjung</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <h6 class="mb-0 text-uppercase">{{ $str_mode }} Data Pengunjung</h6>
    <hr />

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ $save_route }}">
                {{ csrf_field() }}

                {{-- response_at --}}
                <div class="mb-3">
                    <label for="response_at" class="form-label">Tarikh Respons</label>
                    <input type="datetime-local" class="form-control {{ $errors->has('response_at') ? 'is-invalid' : '' }}"
                        id="response_at" name="response_at"
                        value="{{ old('response_at', isset($visitor) ? $visitor->response_at : '') }}">
                    @if ($errors->has('response_at'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('response_at') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- full_name --}}
                <div class="mb-3">
                    <label for="full_name" class="form-label">Nama Penuh</label>
                    <input type="text" class="form-control {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                        id="full_name" name="full_name"
                        value="{{ old('full_name', isset($visitor) ? $visitor->full_name : '') }}">
                    @if ($errors->has('full_name'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('full_name') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- phone --}}
                <div class="mb-3">
                    <label for="phone" class="form-label">No. Telefon</label>
                    <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                        id="phone" name="phone" value="{{ old('phone', isset($visitor) ? $visitor->phone : '') }}">
                    @if ($errors->has('phone'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('phone') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Emel</label>
                    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        id="email" name="email" value="{{ old('email', isset($visitor) ? $visitor->email : '') }}">
                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('email') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- program_bidang --}}
                <div class="mb-3">
                    <label for="program_bidang" class="form-label">Program/Bidang</label>
                    <textarea class="form-control {{ $errors->has('program_bidang') ? 'is-invalid' : '' }}" id="program_bidang"
                        name="program_bidang">{{ old('program_bidang', isset($visitor) ? $visitor->program_bidang : '') }}</textarea>
                    @if ($errors->has('program_bidang'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('program_bidang') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- lokasi --}}
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" class="form-control {{ $errors->has('lokasi') ? 'is-invalid' : '' }}"
                        id="lokasi" name="lokasi" value="{{ old('lokasi', isset($visitor) ? $visitor->lokasi : '') }}">
                    @if ($errors->has('lokasi'))
                        <div class="invalid-feedback">
                            @foreach ($errors->get('lokasi') as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">{{ $str_mode }}</button>
            </form>
        </div>
    </div>

    <!-- End Page Wrapper -->
@endsection
