<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL <b class="text-danger">RAWAT INAP</b> <b class="text-warning">DEWASA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>PENGKAJIAN MEDIS (<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h4 class="text-danger">Anamnesis</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Keluhan Utama</h6>
                            <textarea class="form-control" name="ku" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Dahulu</h6>
                            <textarea class="form-control" name="rpd" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Sekarang</h6>
                            <textarea class="form-control" name="rps" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Keluarga</h6>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_h">
                                <label class="form-check-label"> Hipertensi </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_d">
                                <label class="form-check-label"> Diabetes Melitus </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_p">
                                <label class="form-check-label"> Penyakit Jantung </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_a">
                                <label class="form-check-label"> Asma </label>
                            </div>
                            <textarea class="form-control" name="rpk_lain" rows="1" placeholder="Lainnya..."></textarea>
                        </div>
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h4 class="text-danger">Tanda Vital</h4>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <h6>Keadaan Umum</h6>
                            <div class="form-group">
                                <textarea class="form-control" name="keu" rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <h6 class="mb-2">Jalan Nafas ( <b class="text-warning">A</b> )</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="1">
                                <label class="form-check-label"> Paten </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="2">
                                <label class="form-check-label"> Obstruksi Parsial </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="3">
                                <label class="form-check-label"> Obstruksi Total </label>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <label class="form-check-label fw-bold text-dark me-2">Jalan Nafas ( <b class="text-warning">A</b> )</label>
                                <div class="form-check m-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="1">
                                    <label class="form-check-label"> Paten </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="2">
                                    <label class="form-check-label"> Obstruksi Parsial </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="3">
                                    <label class="form-check-label"> Obstruksi Total </label>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Pernafasan ( <b class="text-warning">B</b> )</h6>
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nafas</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group flex-grow-1">
                                    <input type="number" class="form-control" name="fr">
                                    <span class="input-group-text">X/menit</span>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_cb" value="1" checked="">
                                    <label class="form-check-label">
                                        Simetris
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_cb" value="2">
                                    <label class="form-check-label">
                                        Asimetris
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6>Sirkulasi ( <b class="text-warning">C</b> )</h6>
                        <div class="form-group">
                            <label class="form-label">Tekanan Darah (mmHg)</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="number" class="form-control" name="td_up">
                                <div class="input-group-text"> / </div>
                                <input type="number" class="form-control" name="td_down">
                                <div class="input-group-text"> mmHg </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Frekuensi Nadi</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group input-group-sm flex-grow-1">
                                    <input type="number" class="form-control" name="nadi">
                                    <span class="input-group-text">X/menit</span>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_nadi" value="1" checked="">
                                    <label class="form-check-label">
                                        Reguler
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_nadi" value="2">
                                    <label class="form-check-label">
                                        Ireguler
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Suhu</label>
                            <div class="input-group input-group-sm mb-2">
                                <input type="number" class="form-control" name="suhu">
                                <div class="input-group-text">°C</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">SpO2</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="spo2">
                                <div class="input-group-text">%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Neorologi ( <b class="text-warning">D</b> )</h6>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">Tingkat Kesadaran</label>
                                    <select class="form-control" name="tks">
                                        <option value="">Pilih</option>
                                        @if ($list['tingkat_kesadaran'])
                                            @foreach ($list['tingkat_kesadaran'] as $item)
                                                <option value="{{ $item->ID }}">{{ $item->DESKRIPSI }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <h6>Alat Bantu Nafas</h6>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abn" value="2">
                                                    <label class="form-check-label"> Ya </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="abn" value="0" checked="">
                                                    <label class="form-check-label"> Tidak </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <h6>Kulit</h6>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="1" checked="">
                                                    <label class="form-check-label"> Normal </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="2">
                                                    <label class="form-check-label"> Jaundice </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="3">
                                                    <label class="form-check-label"> Akral Dingin </label>
                                                </div>
                                                <div class="form-check form-check-inline mb-2">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="4">
                                                    <label class="form-check-label"> Sianotik </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="5">
                                                    <label class="form-check-label"> Berkeringat </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <h4 class="mb-3 text-danger">Pemeriksaan Fisik</h4>
                <div class="mb-3">
                    @include(
                        'pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_anatomi',
                        [
                            'section' => '#riD_dokter',
                            'metodePemeriksaan' => [
                                'mata',
                                'tenggorokan',
                                'leher',
                                'dada',
                                'perut'
                            ]
                        ]
                    )
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Hasil Pemeriksaan Penunjang</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_lab')
                </div>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_rad')
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <h4 class="form-label fw-bold text-danger mb-3">Rencana Kerja dan Terapi</h4>
                    <textarea class="form-control" name="pt" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
