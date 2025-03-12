@extends('layouts.index2')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pelayanan</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pelayanan.pasien') }}">Daftar Pasien</a></li>
                    <li class="breadcrumb-item" aria-current="page">Resume Pasien</li>
                </ul>
            </div>
            <div class="col-md-12 mt-2">
                <div class="page-header-title">
                    <h2 class="mb-0">Pasien <b class="text-primary">A/N SUNARYO, TN</b></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-md-12">
        <div class="card user-card">
            <div class="card-body">
                <div class="user-cover-bg">
                    <img src="../assets/images/application/img-user-cover-1.jpg" alt="image"
                        class="img-fluid" />
                    <div class="cover-data">
                        <div class="d-inline-flex align-items-center">
                            <i class="ph-duotone ph-star text-warning me-1"></i>
                            4.5 <small class="text-white text-opacity-50">/ 5</small>
                        </div>
                    </div>
                </div>
                <div class="chat-avtar card-user-image">
                    <img src="../assets/images/user/avatar-1.jpg" alt="user-image"
                        class="img-thumbnail rounded-circle" />
                    <i class="chat-badge bg-success"></i>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">William Bond</h6>
                        <p class="text-muted text-sm mb-0">DM on <a href="#"
                                class="text-primary">@williambond</a></p>
                    </div>
                    <div class="flex-shrink-0">
                        <button class="btn btn-primary btn-sm">Message</button>
                        <button class="btn btn-outline-secondary btn-sm ms-1">Follow</button>
                    </div>
                </div>
                <div class="row g-3 my-3 text-center">
                    <div class="col-4">
                        <h5 class="mb-0">86</h5>
                        <small class="text-muted">Post</small>
                    </div>
                    <div class="col-4 border border-top-0 border-bottom-0">
                        <h5 class="mb-0">40</h5>
                        <small class="text-muted">Project</small>
                    </div>
                    <div class="col-4">
                        <h5 class="mb-0">4.5K</h5>
                        <small class="text-muted">Members</small>
                    </div>
                </div>
                <div class="saprator my-3">
                    <span>Skills</span>
                </div>
                <div class="text-center">
                    <span
                        class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">UX</span>
                    <span
                        class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">Figma</span>
                    <span
                        class="badge bg-light-secondary border rounded-pill border-secondary bg-transparent f-14 me-1 mt-1">Backend</span>
                </div>
                <div class="saprator my-3">
                    <span>Task completed</span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 me-2">
                        <div class="progress" style="height: 8px">
                            <div class="progress-bar bg-primary" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <h6 class="mb-0">30%</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection
