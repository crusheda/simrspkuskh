<div class="card table-card border shadow-none">
    <div class="card-header pb-0 pt-2">
        <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Form Layanan KFR</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                    type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Riwayat</button>
            </li>
        </ul>
    </div>
    <div class="card-body p-3">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Diisi Oleh Dokter Sp.KFR</h5>
                        <hr>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-floating mb-0">
                                <input type="text" class="form-control" id="floatingInput" placeholder="">
                                <label for="floatingInput">Anamnesa</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>demo</td>
                                <td>/demo</td>
                                <td><span class="badge text-bg-danger">demo</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>Main Page</td>
                                <td>/main.page</td>
                                <td><span class="badge text-bg-success">Published</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Login Page</td>
                                <td>/login-page.design</td>
                                <td><span class="badge text-bg-success">Published</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Main Page</td>
                                <td>/main.page</td>
                                <td><span class="badge text-bg-success">Published</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Privacy Policy</td>
                                <td>/privacy-policy</td>
                                <td><span class="badge text-bg-danger">Unpublished</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Login Page</td>
                                <td>/login-page.design</td>
                                <td><span class="badge text-bg-success">Published</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Main Page</td>
                                <td>/main.page</td>
                                <td><span class="badge text-bg-success">Published</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Privacy Policy</td>
                                <td>/privacy-policy</td>
                                <td><span class="badge text-bg-danger">Unpublished</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Login Page</td>
                                <td>/login-page.design</td>
                                <td><span class="badge text-bg-success">Published</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
