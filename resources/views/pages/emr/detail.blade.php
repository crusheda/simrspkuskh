@extends('layouts.index')

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Electronic</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('emr.index') }}">Medical Record</a></li>
                        <li class="breadcrumb-item" aria-current="page">NOKUNJ. <b>{{ $KUNJUNGAN }}</b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="col-sm-12">
        <div class="card social-profile">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-auto">
                        <div data-back-button class="d-flex align-items-center btn btn-outline-secondary">
                            <div class="flex-shrink-0 me-3">
                                <i class="ph-duotone ph-caret-double-left align-middle"></i>
                                {{-- <div class="btn btn-icon btn-link-secondary avtar">
                                </div> --}}
                            </div>
                            <div class="flex-grow-1 align-items-left">
                                <small>Kembali ke Halaman<br><a style="font-size: 15px">Sebelumnya</a></small>
                                {{-- <h6 class="mb-0">Sebelumnya</h6> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row justify-content-between d-flex align-items-center p-2">
                            <div class="col-md-5 col-xl-6">
                                <h4 class="mb-1 align-middle">A/N <a id="nama_px" class="text-primary">Muhammad Arizal Yusuf Hermawan</a></h4>
                                <p class="mb-0" style="font-size: 12px"><b>RM. <a id="rm_px" class="text-danger">00037804</a></b> | <b>NOBPJS. <a id="bpjs_px" class="text-warning">0001263101444</a></b></p>
                            </div>
                            <div class="col-md-7 col-xl-6 col-xxl-5">
                                <div class="row g-1 text-center">
                                    <div class="col-3">
                                        <h5 class="mb-0">239k</h5>
                                        <p class="text-muted mb-0">Followers</p>
                                    </div>
                                    <div class="col-3 border border-top-0 border-bottom-0">
                                        <h5 class="mb-0">539k</h5>
                                        <p class="text-muted mb-0">Following</p>
                                    </div>
                                    <div class="col-3 border border-top-0 border-bottom-0">
                                        <h5 class="mb-0">400</h5>
                                        <p class="text-muted mb-0">Post</p>
                                    </div>
                                    <div class="col-3">
                                        <h5 class="mb-0">539k</h5>
                                        <p class="text-muted mb-0">Like</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body py-0">
                <ul class="nav nav-tabs profile-tabs" id="" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="" data-bs-toggle="tab" href="#frehab" role="tab"
                            aria-selected="false" tabindex="-1">
                            <i class="ph-duotone ph-user-circle me-2"></i> Form Rehab Medik
                        </a>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <a class="nav-link" id="followers-tab" data-bs-toggle="tab" href="#followers" role="tab"
                            aria-selected="false" tabindex="-1">
                            <i class="ph-duotone ph-users me-2"></i> Friends
                            <span class="badge bg-secondary rounded-pill ms-2">99</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="friends-tab" data-bs-toggle="tab" href="#friends" role="tab"
                            aria-selected="false" tabindex="-1">
                            <i class="ph-duotone ph-user-circle-plus me-2"></i> Friends Request
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="gallery-tab" data-bs-toggle="tab" href="#gallery" role="tab"
                            aria-selected="true">
                            <i class="ph-duotone ph-image me-2"></i> Gallery
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-xxl-9">
                <div class="tab-content">
                    <div class="tab-pane active show" id="frehab" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <textarea class="form-control border-0 shadow-none" placeholder="What’s new, Stebin?" rows="1" style="height: 159px;"></textarea>
                                <div class="row align-items-center mt-3">
                                    <div class="col">
                                        <ul class="list-inline ms-auto mb-0">
                                            <li class="list-inline-item border-end pe-2 me-2">
                                                <a href="#" class="avtar avtar-s btn-link-warning">
                                                    <i class="ti ti-mood-smile f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="#" class="avtar avtar-s btn-link-secondary">
                                                    <i class="ti ti-photo f-18"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="#" class="avtar avtar-s btn-link-secondary">
                                                    <i class="ti ti-paperclip f-18"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-auto text-end">
                                        <button class="btn btn-primary">Post</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="tab-pane" id="followers" role="tabpanel" aria-labelledby="followers-tab">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-10.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-2.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-3.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-4.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-5.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-6.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-7.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-8.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-9.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-10.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-2.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-3.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-4.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-5.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-6.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-7.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-8.jpg" alt="User image">
                                                        <i class="chat-badge bg-success mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-xxl-4">
                                        <div class="card border shadow-none">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="chat-avtar d-inline-flex">
                                                        <img class="rounded-circle img-thumbnail img-fluid wid-80"
                                                            src="../assets/images/user/avatar-9.jpg" alt="User image">
                                                        <i class="chat-badge bg-danger mb-2 me-2"></i>
                                                    </div>
                                                    <div class="my-3">
                                                        <h5 class="mb-0">William Bond</h5>
                                                        <p class="mb-0">DM on <a href="#"
                                                                class="link-primary">@williambond</a>😍</p>
                                                    </div>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-primary">Accept</button></div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-grid"><button
                                                                class="btn btn-outline-secondary">Decline</button></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="friends" role="tabpanel" aria-labelledby="friends-tab">
                        <div class="card">
                            <div class="card-header">
                                <h5>Personal Details</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Full Name</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Akshay Handge</h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Father's Name</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Mr. Deepak Handge</h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Address</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Street 110-B Kalani Bag, Dewas, M.P. INDIA</h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Zip Code</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">12345</h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Phone</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">+0 123456789 , +0 123456789</h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Email</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0"><a href="mailto:support@example.com"
                                                class="link-primary">support@example.com</a></h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Website</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0"><a href="#"
                                                class="link-primary">http://example.com</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5>other Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Occupation</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Designer</h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Skills</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">C#, Javascript, Scss</h6>
                                    </div>
                                </div>
                                <div class="row g-3 mt-0">
                                    <div class="col-md-4">
                                        <p class="mb-0 text-muted">Jobs</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-0">Phoenixcoded</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane active show" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                        <div class="card">
                            <div class="card-header">
                                <h5>Gallery</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <a class="img-post card social-gallery-card"
                                            data-lightbox="../assets/images/application/img-gallery-1.jpg">
                                            <img src="../assets/images/application/img-gallery-1.jpg" alt="img"
                                                class="card-img">
                                            <div class="card-img-overlay">
                                                <i class="ti ti-cloud-download"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row g-2">
                                            <div class="col-md-12">
                                                <a class="img-post card social-gallery-card"
                                                    data-lightbox="../assets/images/application/img-gallery-2.jpg">
                                                    <img src="../assets/images/application/img-gallery-2.jpg"
                                                        alt="img" class="card-img">
                                                    <div class="card-img-overlay">
                                                        <i class="ti ti-cloud-download"></i>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-md-12">
                                                <a class="img-post card social-gallery-card"
                                                    data-lightbox="../assets/images/application/img-gallery-3.jpg">
                                                    <img src="../assets/images/application/img-gallery-3.jpg"
                                                        alt="img" class="card-img">
                                                    <div class="card-img-overlay">
                                                        <i class="ti ti-cloud-download"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="img-post card social-gallery-card"
                                            data-lightbox="../assets/images/application/img-gallery-5.jpg">
                                            <img src="../assets/images/application/img-gallery-5.jpg" alt="img"
                                                class="card-img">
                                            <div class="card-img-overlay">
                                                <i class="ti ti-cloud-download"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="img-post card social-gallery-card"
                                            data-lightbox="../assets/images/application/img-gallery-6.jpg">
                                            <img src="../assets/images/application/img-gallery-6.jpg" alt="img"
                                                class="card-img">
                                            <div class="card-img-overlay">
                                                <i class="ti ti-cloud-download"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <a class="img-post card social-gallery-card"
                                            data-lightbox="../assets/images/application/img-gallery-4.jpg">
                                            <img src="../assets/images/application/img-gallery-4.jpg" alt="img"
                                                class="card-img">
                                            <div class="card-img-overlay">
                                                <i class="ti ti-cloud-download"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a class="img-post card social-gallery-card"
                                            data-lightbox="../assets/images/application/img-gallery-8.jpg">
                                            <img src="../assets/images/application/img-gallery-8.jpg" alt="img"
                                                class="card-img">
                                            <div class="card-img-overlay">
                                                <i class="ti ti-cloud-download"></i>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a class="img-post card social-gallery-card"
                                            data-lightbox="../assets/images/application/img-gallery-7.jpg">
                                            <img src="../assets/images/application/img-gallery-7.jpg" alt="img"
                                                class="card-img">
                                            <div class="card-img-overlay">
                                                <i class="ti ti-cloud-download"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="col-lg-4 col-xxl-3">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Personal information</h5>
                        <div class="dropdown">
                            <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="material-icons-two-tone f-18">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item" href="#">Edit</a>
                                <a class="dropdown-item" href="#">Delete</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li>
                                <a class="d-flex align-items-center text-muted text-hover-primary mb-2"
                                    href="https://phoenixcoded.net/" target="_blank">
                                    <div class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                        <i class="material-icons-two-tone text-secondary f-16">language</i>
                                    </div>
                                    <span class="text-truncate w-100">https://phoenixcoded.net/</span>
                                </a>
                            </li>
                            <li>
                                <div class="d-flex align-items-center text-muted mb-2">
                                    <div class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                        <i class="material-icons-two-tone text-secondary f-16">home</i>
                                    </div>
                                    <span class="text-truncate w-100">Hanoi, Vietnam</span>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-center text-muted mb-2">
                                    <div class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                        <i class="material-icons-two-tone text-secondary f-16">calendar_today</i>
                                    </div>
                                    <span class="text-truncate w-100">Auguest, 21,1996</span>
                                </div>
                            </li>
                            <li>
                                <a class="d-flex align-items-center text-muted text-hover-primary mb-0"
                                    href="mailto:demo123@mail.com" target="_blank">
                                    <div class="avtar avtar-xs bg-light-secondary flex-shrink-0 me-2">
                                        <i class="material-icons-two-tone text-secondary f-16">email</i>
                                    </div>
                                    <span class="text-truncate w-100">demo123@mail.com</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5>Who is follow you</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img class="rounded img-fluid wid-40" src="../assets/images/user/avatar-1.jpg"
                                        alt="User image">
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <h6 class="mb-0">John Doe</h6>
                                    <p class="mb-0">@John_Doe</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-muted">5 min ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img class="rounded img-fluid wid-40" src="../assets/images/user/avatar-5.jpg"
                                        alt="User image">
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <h6 class="mb-0">Addie Bass</h6>
                                    <p class="mb-0">@A_Bass</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-muted">Yesterday</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img class="rounded img-fluid wid-40" src="../assets/images/user/avatar-3.jpg"
                                        alt="User image">
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <h6 class="mb-0">Alberta Robbins</h6>
                                    <p class="mb-0">@AlbeRob12</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-muted">1 Day ago</span>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <img class="rounded img-fluid wid-40" src="../assets/images/user/avatar-4.jpg"
                                        alt="User image">
                                </div>
                                <div class="flex-grow-1 mx-2">
                                    <h6 class="mb-0">Agnes McGee</h6>
                                    <p class="mb-0">@AgnMcGee</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-muted">2 Day ago</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Your page <span class="badge bg-light-secondary rounded-pill">2</span></h5>
                        <button class="btn btn-link-primary btn-sm">See All</button>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-s bg-light-primary flex-shrink-0 rounded-circle">
                                    <i class="ph-duotone ph-paint-brush text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0 f-w-400">UI Design Team</h5>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-s bg-light-warning flex-shrink-0 rounded-circle">
                                    <i class="ph-duotone ph-handshake text-warning"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0 f-w-400">Creative Team</h5>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-s bg-light-warning flex-shrink-0 rounded-circle">
                                    <i class="ph-duotone ph-buildings text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0 f-w-400">Marketing</h5>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="avtar avtar-s bg-light-warning flex-shrink-0 rounded-circle">
                                    <i class="ph-duotone ph-globe text-danger"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-0 f-w-400">SEO Optimized</h5>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('[data-back-button]').on('click', function() {
                if (document.referrer) {
                    window.history.back();
                } else {
                    window.location.href = "{{ route('klaim.index') }}"; // fallback ke klaim
                }
            });

        });
    </script>
@endsection
