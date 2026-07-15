<aside class="app-menubar-tabs" id="appMenubar">
    <div class="app-navbar-brand">
        <a class="navbar-brand-logo" href="{{ route('v2.dashboard') }}">
            <img src="{{ asset('images/logo/logo.png') }}" class="w-100" alt="Dashboard Logo">
        </a>
    </div>
    <div class="app-navbar-tabs" data-simplebar>
        <ul class="nav" id="appMenubarTabs" role="tablist" aria-orientation="vertical">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Publik">
                <a class="menu-link active" href="#publikTab" role="tab" aria-controls="publikTab"
                    aria-selected="true" data-bs-toggle="tab">
                    <i class="ri-home-line fs-24"></i>
                </a>
            </li>
            <li class="nav-item-hr"></li>
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Digital">
                <a class="menu-link" href="#digitalTab" role="tab" aria-controls="digitalTab"
                    aria-selected="false" data-bs-toggle="tab">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18 8C18 11.3137 15.3137 14 12 14C8.68629 14 6 11.3137 6 8C6 4.68629 8.68629 2 12 2C15.3137 2 18 4.68629 18 8Z"
                            stroke="var(--bs-heading-color)" stroke-width="2" />
                        <path
                            d="M6.5 10.189C3.91216 10.855 2 13.2042 2 15.9999C2 19.3136 4.68629 21.9999 8 21.9999C11.3137 21.9999 14 19.3136 14 15.9999C14 15.2452 13.8607 14.5231 13.6063 13.8578"
                            stroke="var(--bs-heading-color)" stroke-width="2" />
                        <path opacity="0.5"
                            d="M12 20.4722C13.0615 21.4222 14.4633 21.9999 16 21.9999C19.3137 21.9999 22 19.3136 22 15.9999C22 13.2042 20.0878 10.855 17.5 10.189"
                            stroke="var(--bs-heading-color)" stroke-width="2" />
                    </svg>
                </a>
            </li>
            {{-- <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Dashboard">
                <a class="menu-link position-relative" href="{{ route('v2.dashboard') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                        fill="none">
                        <path
                            d="m21,23c0,.553-.448,1-1,1s-1-.447-1-1c0-2.206-1.794-4-4-4h-6c-2.206,0-4,1.794-4,4,0,.553-.448,1-1,1s-1-.447-1-1c0-3.309,2.691-6,6-6h6c3.309,0,6,2.691,6,6Zm1-15.5v2c0,.827-.673,1.5-1.5,1.5h-.5c0,2.206-1.794,4-4,4h-8c-2.206,0-4-1.794-4-4h-.5c-.827,0-1.5-.673-1.5-1.5v-2c0-.827.673-1.5,1.5-1.5h.5c0-2.206,1.794-4,4-4h3v-1c0-.553.448-1,1-1s1,.447,1,1v1h3c2.206,0,4,1.794,4,4h.5c.827,0,1.5.673,1.5,1.5Zm-4-1.5c0-1.103-.897-2-2-2h-8c-1.103,0-2,.897-2,2v5c0,1.103.897,2,2,2h8c1.103,0,2-.897,2-2v-5Zm-8.5,1c-.828,0-1.5.672-1.5,1.5s.672,1.5,1.5,1.5,1.5-.672,1.5-1.5-.672-1.5-1.5-1.5Zm5,0c-.828,0-1.5.672-1.5,1.5s.672,1.5,1.5,1.5,1.5-.672,1.5-1.5-.672-1.5-1.5-1.5Z"
                            fill="var(--bs-heading-color)"></path>
                    </svg>
                </a>
            </li> --}}
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
            </li>
            <li class="nav-item mt-5" data-bs-toggle="tooltip" data-bs-placement="right"
                data-bs-title="Login">
                <a class="menu-link" href="authentication/login-frame.html">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5"
                            d="M9.00195 7C9.01406 4.82497 9.11051 3.64706 9.87889 2.87868C10.7576 2 12.1718 2 15.0002 2H16.0002C18.8286 2 20.2429 2 21.1215 2.87868C22.0002 3.75736 22.0002 5.17157 22.0002 8V16C22.0002 18.8284 22.0002 20.2426 21.1215 21.1213C20.2429 22 18.8286 22 16.0002 22H15.0002C12.1718 22 10.7576 22 9.87889 21.1213C9.11051 20.3529 9.01406 19.175 9.00195 17"
                            stroke="var(--bs-heading-color)" stroke-width="2" stroke-linecap="round" />
                        <path d="M15 12H2M2 12L5.5 9M2 12L5.5 15" stroke="var(--bs-heading-color)"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
                                <a class="menu-link" href="index.html" role="button">
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
                        </ul>
                    </nav>
                </div>
                <div class="tab-pane fade" id="digitalTab" role="tabpanel" tabindex="0">
                    <nav class="app-navbar" data-simplebar>
                        <ul class="side-menubar">
                            <li class="menu-heading">
                                <span class="menu-label">Digital</span>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link" href="authentication/login-basic.html">
                                    <i class="fi fi-rr-unlock"></i>
                                    <span class="menu-label">Electronic Medical Record</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link" href="authentication/login-basic.html">
                                    <i class="fi fi-rr-unlock"></i>
                                    <span class="menu-label">Monitoring</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link" href="authentication/login-basic.html">
                                    <i class="fi fi-rr-unlock"></i>
                                    <span class="menu-label">Smart Claim</span>
                                </a>
                            </li>
                            {{-- <li>
                                <div class="menu-divider"></div>
                            </li> --}}
                        </ul>
                    </nav>
                </div>
            </div>
            {{-- <div class="card card-gradient mx-3 d-none d-xl-block">
                <div class="card-body">
                    <h5>Upgrade to Pro</h5>
                    <p class="text-1xs">Get unlimited leads, advanced analytics, and 24/7 priority support.
                    </p>
                    <a target="_blank"
                        href="https://themeforest.net/item/nexlink-crm-admin-dashboard-bootstrap-template/60903033"
                        class="btn btn-primary waves-effect">Upgrade Now</a>
                </div>
            </div> --}}
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
