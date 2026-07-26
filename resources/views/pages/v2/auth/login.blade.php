@extends('layouts.v2.auth.index')

@section('title', 'Login SIRMED')

@section('content')
    <div class="auth-frame-wrapper">
        <div class="row g-0 h-100">
            <div class="col-xl-7 col-lg-7 col-md-5 d-none d-md-block">
                <div class="auth-frame" style="background-image: url(/images/pku/bg.png);opacity:50%;"></div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-5 align-self-center">
                <div class="p-4 p-sm-5 maxw-450px m-auto">
                    <div class="mb-4 text-center">
                        <a href="{{ route('v2.dashboard') }}" aria-label="SIRMED Logo">
                            <img class="visible-light w-90" src="{{ asset('images/pku/logo-kop.png') }}" alt="NexLink logo">
                        </a>
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <p class="">
                        <div class="alert alert-secondary pb-0 ps-0 mb-1">
                            <ul>
                                <li>
                                    Belum memiliki Akun? <a data-bs-toggle="modal" data-bs-target="#hubsdi" href="javascript: void(0);" class="ms-1"><b>Buat Akun Baru</b></a>
                                </li>
                                <li>Bridging Local <b class="text-green">SIMGOS Versi 2</b></li>
                            </ul>
                        </div>
                    </p>
                    {{-- <div class="text-center mb-5">
                        <h5 class="mb-1">Welcome to NexLink</h5>
                        <p>Sign in to access your secure admin dashboard.</p>
                    </div> --}}
                    <form method="POST" action="{{ route('login') }}">
                        <div class="mb-4" data-validate="Username is required">
                            <label class="form-label" for="loginEmail">Username</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Tuliskan Username Simgos" value="{{ old('name') }}" autocomplete="name" autofocus required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4" data-validate="Password is required">
                            <label class="form-label" for="loginPassword">Password</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-control password-inputform-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Tuliskan Password Simgos" autocomplete="current-password" required>
                                <button type="button" id="togglePassword" class="toggle-password" aria-pressed="false" aria-label="Show password" title="Show password">
                                    <i class="close fi fi-rr-eye-crossed" aria-hidden="true"></i>
                                    <i class="open fi fi-rr-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary {{ old('remember') ? 'checked' : '' }}" type="checkbox" id="remember" name="remember"/>
                                    <label class="form-check-label text-muted" for="customCheckc1">Ingat Saya?</label>
                                </div>
                                <a href="{{ route('lupapassword.index') }}" class="f-w-400 mb-0">
                                    Lupa Password Akun?
                                </a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light w-100 shadow-lg">Masuk / Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="hubsdi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="catatanLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header p-3">
                    <h5 class="modal-title" id="catatanLabel">✆ Daftar Nomor Yang Bisa Dihubungi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 pb-0">
                    <h6 class="mb-3">Masalah <mark>Terkait Akun</mark> silakan Hubungi No.Telp Bagian SDI : <b class="text-primary">188</b> (Hanya Disaat Jam Kerja)</h6>
                    <h6>Nomor Bagian SDI :</h6>
                    <ul>
                        <li>Novita Yuliani, S.KM, M.Kes (<b class="text-success">Whatsapp</b> : <b class="text-danger">089689514960</b>)</li>
                        <li>Kholid Hidayat Al-Khoiri, S.Psi (<b class="text-success">Whatsapp</b> : <b class="text-danger">0882003805027</b>)</li>
                        <li>Sri Suryani, SM (<b class="text-success">Whatsapp</b> : <b class="text-danger">081330795309</b>)</li>
                    </ul>
                    <h6 class="mb-3">Masalah <mark>Teknis Sistem</mark> silakan Hubungi No.Telp IT : <b class="text-primary">102</b> / <b class="text-primary">193</b></h6>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
