<aside class="app-menubar-tabs" id="appMenubar">
    <div class="app-navbar-brand">
        <a class="navbar-brand-logo" href="{{ route('v2.dashboard') }}">
            <img src="{{ asset('images/logo/logo.png') }}" class="w-100" alt="Dashboard Logo">
        </a>
    </div>
    <div class="app-navbar-tabs" data-simplebar>
        <ul class="nav" id="appMenubarTabs" role="tablist" aria-orientation="vertical">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Publik">
                <a class="menu-link" href="#publikTab" role="tab" aria-controls="publikTab"
                    aria-selected="false" data-bs-toggle="tab">
                    <i class="ri-home-line fs-24"></i>
                </a>
            </li>
            <li class="nav-item-hr"></li>
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Digital">
                <a class="menu-link" href="#digitalTab" role="tab" aria-controls="digitalTab"
                    aria-selected="false" data-bs-toggle="tab">
                    <i class="ri-health-book-line fs-24"></i>
                </a>
            </li>
            <li class="nav-item-hr"></li>
            <li class="nav-item mb-auto" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Sirmed V3">
                <a class="menu-link border border-primary text-primary-emphasis border-dashed" href="{{ route('v3.dashboard') }}">
                    <i class="ri-rocket-line fs-24"></i>
                </a>
            </li>
            
            {{-- <li class="nav-item-hr"></li>
            <li class="nav-item mb-auto" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Add Customer">
                <a href="javascript:void(0);" class="btn btn-icon btn-lg btn-white waves-effect waves-light"
                    data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5"
                            d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                            stroke="var(--bs-primary)" stroke-width="2" />
                        <path d="M15 12H12M12 12H9M12 12V9M12 12V15" stroke="var(--bs-primary)"
                            stroke-width="2" stroke-linecap="round" />
                    </svg>
                </a>
            </li> --}}
            {{-- <li class="nav-item mt-5" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Ujicoba Sirmed v3">
                <a class="menu-link" href="{{ route('v3.dashboard') }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.609 13.5616L21.8382 11.1263C22.0182 9.2137 22.1082 8.25739 21.781 7.86207C21.604 7.64823 21.3633 7.5172 21.106 7.4946C20.6303 7.45282 20.0329 8.1329 18.8381 9.49307C18.2202 10.1965 17.9113 10.5482 17.5666 10.6027C17.3757 10.6328 17.1811 10.6018 17.0047 10.5131C16.6865 10.3529 16.4743 9.91812 16.0499 9.04851L13.8131 4.46485C13.0112 2.82162 12.6102 2 12 2C11.3898 2 10.9888 2.82162 10.1869 4.46486L7.95007 9.04852C7.5257 9.91812 7.31351 10.3529 6.99526 10.5131C6.81892 10.6018 6.62434 10.6328 6.43337 10.6027C6.08872 10.5482 5.77977 10.1965 5.16187 9.49307C3.96708 8.1329 3.36968 7.45282 2.89399 7.4946C2.63666 7.5172 2.39598 7.64823 2.21899 7.86207C1.8918 8.25739 1.9818 9.2137 2.16181 11.1263L2.391 13.5616C2.76865 17.5742 2.95748 19.5805 4.14009 20.7902C5.32271 22 7.09517 22 10.6401 22H13.3599C16.9048 22 18.6773 22 19.8599 20.7902C21.0425 19.5805 21.2313 17.5742 21.609 13.5616Z" stroke="var(--bs-heading-color)" stroke-width="2"></path>
                        <path opacity="0.5" d="M9 18H15" stroke="var(--bs-heading-color)" stroke-width="2" stroke-linecap="round"></path>
                    </svg>
                </a>
            </li> --}}
        </ul>
    </div>
    <div class="app-tab-content">
        <div class="app-side-brands">
            <a href="{{ route('v2.dashboard') }}" class="navbar-brand-text d-inline-flex align-items-center">
                <span>SIR<b class="text-primary">MED</b></span>
                <span class="badge bg-primary-subtle badge-sm text-primary ms-2">v2.0</span>
            </a>
        </div>
        <div class="app-content-inner">
            <div class="tab-content" id="appMenubarTabsContent">
                <div class="tab-pane fade" id="publikTab" role="tabpanel" tabindex="0">
                    <nav class="app-navbar" data-simplebar>
                        <ul class="side-menubar">
                            <li class="menu-heading">
                                <span class="menu-label">Publik</span>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('v2.dashboard') }}" role="button">
                                    <i class="ri-dashboard-line"></i>
                                    <span class="menu-label">Dashboard</span>
                                </a>
                            </li>
                            {{-- <li class="menu-item">
                                <a class="menu-link" href="deals.html" role="button">
                                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="20" height="20" rx="6"
                                            fill="var(--bs-primary)" />
                                    </svg>
                                    <span class="menu-label">Deals</span>
                                    <span class="badge badge-sm text-bg-success">+12%</span>
                                </a>
                            </li> --}}
                            @canany(['display_tt', 'display_rating', 'display_antrian_poli'])
                            <li>
                                <div class="menu-divider"></div>
                            </li>
                            <li class="menu-heading">
                                <span class="menu-label">Display</span>
                            </li>
                            @endcanany
                            @canany(['display_tt', 'display_rating', 'display_antrian_poli'])
                            <li class="menu-item">
                                @can('display_antrian_poli')
                                <a class="menu-link" href="{{ route('v2.display.antrian.poli') }}" role="button">
                                    <i class="ri-slideshow-2-line"></i>
                                    <span class="menu-label">Antrian Poliklinik</span>
                                </a>
                                @endcan
                                @can('display_tt')
                                <a class="menu-link" href="{{ route('v2.display.bed') }}" role="button">
                                    <i class="ri-tv-2-line"></i>
                                    <span class="menu-label">Tempat Tidur</span>
                                </a>
                                @endcan
                                @can('display_rating')
                                <a class="menu-link" href="{{ route('v2.display.rating') }}" role="button">
                                    <i class="ri-emoji-sticker-line"></i>
                                    <span class="menu-label">Rating</span>
                                </a>
                                @endcan
                            </li>
                            @endcanany
                        </ul>
                    </nav>
                </div>
                @canany(['emr', 'monitoring', 'smart_claim', 'smart_claim_farmasi'])
                <div class="tab-pane fade" id="digitalTab" role="tabpanel" tabindex="0">
                    <nav class="app-navbar" data-simplebar>
                        <ul class="side-menubar">
                            <li class="menu-heading">
                                <span class="menu-label">Digital</span>
                            </li>
                            @can('emr')
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('v2.emr') }}">
                                    <i class="ri-nurse-line fs-20"></i>
                                    <span class="menu-label">Medical Record</span>
                                </a>
                            </li>
                            @endcan
                            @can('monitoring')
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('v2.monitoring') }}">
                                    <i class="ri-line-chart-fill fs-20"></i>
                                    <span class="menu-label">Monitoring</span>
                                </a>
                            </li>
                            @endcan
                            @canany(['smart_claim', 'smart_claim_farmasi'])
                            <li class="menu-item">
                                <a class="menu-link" href="{{ route('v2.smartclaim') }}">
                                    <i class="ri-file-lock-line fs-20"></i>
                                    <span class="menu-label">Smart Claim</span>
                                </a>
                            </li>
                            @endcanany
                            {{-- <li>
                                <div class="menu-divider"></div>
                            </li> --}}
                        </ul>
                    </nav>
                </div>
                @endcanany
            </div>
            <div class="card card-gradient mx-3 d-none d-xl-block">
                <div class="card-body">
                    <h5>SIRMED V3 Terbaru</h5>
                    <p class="text-1xs">Versi 3 telah diluncurkan, kunjungi segera!
                    </p>
                    <a target="_blank" href="{{ route('v3.dashboard') }}" class="btn btn-primary waves-effect"><i class="fas fa-dashboard me-1"></i> Buka Sekarang</a>
                </div>
            </div>
        </div>
    </div>
</aside>

{{-- <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" placeholder="Enter full name">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="text" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" placeholder="e.g. +1 234 567 8900">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-control" placeholder="Company name">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Country</label>
                        <select class="form-select">
                            <option value="">Select country</option>
                            <option value="US">United States</option>
                            <option value="UK">United Kingdom</option>
                            <option value="IN">India</option>
                            <option value="CA">Canada</option>
                            <option value="DE">Germany</option>
                            <option value="FR">France</option>
                            <option value="JP">Japan</option>
                            <option value="BR">Brazil</option>
                            <option value="EG">Egypt</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Customer Type</label>
                        <select class="form-select">
                            <option value="">Select type</option>
                            <option value="Lead">Lead</option>
                            <option value="Prospect">Prospect</option>
                            <option value="Client">Client</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Account Status</label>
                        <select class="form-select">
                            <option value="">Select status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Blocked">Blocked</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="form-label">Joined Date</label>
                        <input type="text" class="form-control flatpickr-date" readonly="readonly">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary ms-2">Add Customer</button>
            </div>
        </div>
    </div>
</div> --}}

<script>

</script>
