<div class="col-md-12 mb-3" id="form_admission_note">
    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label fw-bold">Tindak Lanjut :</label>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tl" id="tl_mrs" value="1">
                <label class="form-check-label">
                    MRS
                </label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="tl" id="tl_pulang" value="2" checked>
                <label class="form-check-label">
                    Pulang
                </label>
            </div>
        </div>
    </div>
    <div class="card card-body card-header border border-dashed border-light" id="pri">
        <div class="card-header fw-bold">
            Perencanaan Rawat Inap
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Jenis Ruang Perawatan</label>
                        <select class="form-control" name="pri_ruang" id="pri_ruang">
                            <option value="">Pilih Jenis Ruang Perawatan</option>
                            @foreach ($list['jenis_ruang'] as $item)
                                <option value="{{ $item->ID }}">
                                    {{ $item->DESKRIPSI }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Jenis Perawatan</label>
                        <select class="form-control" name="pri_perawatan" id="pri_perawatan">
                            <option value="">Pilih Jenis Perawatan</option>
                            @foreach ($list['jenis_perawatan'] as $item)
                                <option value="{{ $item->ID }}">
                                    {{ $item->DESKRIPSI }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tanggal</label>
                    <div class="input-group">
                        <input type="text" name="pri_tgl" id="pri_tgl" class="form-control flatpickr-input active" placeholder="Pilih Rentang Tanggal" readonly="readonly">
                        <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Indikasi</label>
                    <textarea class="form-control" name="pri_indikasi" id="pri_indikasi" rows="3"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Keterangan</label>
                    <textarea class="form-control" name="pri_ket" id="pri_ket" rows="3"></textarea>
                </div>
                {{-- <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">DPJP</label>
                    <input type="text" class="form-control" value="{{ $list['pasien']->NAMADOKTER ?? '' }}" placeholder="Nama DPJP" readonly>
                    <input type="hidden" name="pri_dpjp" id="pri_dpjp" value="{{ $list['pasien']->ID ?? '' }}">
                </div> --}}
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">DPJP</label>
                        <select class="form-control" name="pri_dpjp" id="pri_dpjp" style="width: 100%;">
                            @foreach ($list['dokter'] as $d)
                                <option value="{{ $d->ID }}" {{ ($list['pasien']->ID ?? null) == $d->ID ? 'selected' : '' }}>
                                    {{ $d->NAMADOKTER }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <hr>
        <div class="col-md-3">
            <label class="form-label fw-bold">Dirujuk Ke</label>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_gizi" value="1">
                        <label class="form-check-label">
                            Ahli Gizi
                        </label>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_rehab" value="2">
                        <label class="form-check-label">
                            Rehabilitasi Medik
                        </label>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_sp" value="3">
                        <label class="form-check-label">
                            Klinik Spesialis
                        </label>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_lain" value="4">
                        <label class="form-check-label">
                            Lainnya
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="rujuk_lainnya" id="rujuk_lainnya" placeholder="Sebutkan....">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        'use strict';

        const $section = $(@json($section));
        const $form = $section.find('#form_admission_note');

        let isAdmissionNoteLoading = false;
        let isAdmissionNoteSaving = false;
        let isAdmissionNoteHydrating = false;

        let lastSavedData = null;

        let admissionDatePicker = null;

        let admissionSaveTimer = null;



        // ==========================================================
        // GET FORM DATA
        // ==========================================================

        function getAdmissionFormData() {

            const data = getFormDataByName($form, {
                NOKUNJ: kunjungan
            });

            delete data.NOKUNJ;

            return data;
        }


        // ==========================================================
        // SERIALIZE
        // ==========================================================

        function serializeAdmissionData(data) {

            return JSON.stringify(data);
        }


        // ==========================================================
        // CEK PERUBAHAN
        // ==========================================================

        function hasAdmissionChanged() {

            if (lastSavedData === null) {
                return true;
            }

            const currentData =
                getAdmissionFormData();

            return (
                serializeAdmissionData(currentData) !==
                serializeAdmissionData(lastSavedData)
            );
        }


        // ==========================================================
        // SET TANGGAL HARI INI
        // ==========================================================

        function setTodayAdmissionDate() {

            const $tgl =
                $form.find('#pri_tgl');

            if (!$tgl.length) {
                return;
            }


            /*
             * Jangan menimpa tanggal yang sudah ada.
             */
            if (
                $.trim($tgl.val() || '') !== ''
            ) {
                return;
            }


            const today =
                new Date();


            const year =
                today.getFullYear();


            const month =
                String(
                    today.getMonth() + 1
                ).padStart(2, '0');


            const day =
                String(
                    today.getDate()
                ).padStart(2, '0');


            const todayString =
                `${year}-${month}-${day}`;


            if (admissionDatePicker) {

                admissionDatePicker.setDate(
                    todayString,
                    false
                );

            } else {

                $tgl.val(todayString);
            }


            // console.log(
            //     'Admission Note - tanggal otomatis:',
            //     todayString
            // );
        }


        // ==========================================================
        // UPDATE VISIBILITY
        // ==========================================================

        function updateAdmissionVisibility() {

            // ======================================================
            // MRS
            // ======================================================

            if (
                $form.find('#tl_mrs').is(':checked')
            ) {

                $form.find('#pri')
                    .stop(true, true)
                    .slideDown();


                setTodayAdmissionDate();

            }

            // ======================================================
            // PULANG
            // ======================================================

            else {

                $form.find('#pri')
                    .stop(true, true)
                    .slideUp();
            }


            // ======================================================
            // RUJUK LAINNYA
            // ======================================================

            if (
                $form.find('#rujuk_lain').is(':checked')
            ) {

                $form.find('#rujuk_lainnya')
                    .stop(true, true)
                    .slideDown();

            } else {

                $form.find('#rujuk_lainnya')
                    .stop(true, true)
                    .slideUp();
            }
        }


        // ==========================================================
        // CEK PRI LENGKAP
        // ==========================================================

        function isAdmissionMRSComplete() {

            /*
             * Hanya berlaku ketika MRS.
             */

            if (
                !$form.find('#tl_mrs').is(':checked')
            ) {

                return true;
            }


            const fields = {

                pri_ruang:
                    '#pri_ruang',

                pri_perawatan:
                    '#pri_perawatan',

                pri_tgl:
                    '#pri_tgl',

                pri_indikasi:
                    '#pri_indikasi',

                pri_ket:
                    '#pri_ket'
            };


            let complete = true;


            Object.keys(fields).forEach(function (key) {

                const selector =
                    fields[key];

                const $field =
                    $form.find(selector);


                const value =
                    $.trim(
                        $field.val() || ''
                    );


                // console.log(
                //     'CEK PRI:',
                //     key,
                //     '=',
                //     value
                // );


                if (!value) {

                    complete = false;

                    $field.addClass(
                        'is-invalid'
                    );

                } else {

                    $field.removeClass(
                        'is-invalid'
                    );
                }

            });


            // console.log(
            //     'PRI LENGKAP:',
            //     complete
            // );


            return complete;
        }


        // ==========================================================
        // VALIDASI MRS
        // ==========================================================

        function validateAdmissionMRS() {

            if (
                !$form.find('#tl_mrs').is(':checked')
            ) {
                return true;
            }

            const requiredFields = [
                '#pri_ruang',
                '#pri_perawatan',
                '#pri_tgl',
                '#pri_indikasi',
                '#pri_ket'
            ];

            let valid = true;
            let firstInvalid = null;

            requiredFields.forEach(function (selector) {

                const $field =
                    $form.find(selector);

                const value =
                    $.trim(
                        $field.val() || ''
                    );

                if (!value) {

                    valid = false;

                    $field.addClass(
                        'is-invalid'
                    );

                    if (!firstInvalid) {
                        firstInvalid = $field;
                    }

                } else {

                    $field.removeClass(
                        'is-invalid'
                    );
                }
            });

            /*
            * DPJP otomatis.
            * Tidak termasuk field yang harus diisi user.
            */
            const dpjp =
                $.trim(
                    $form.find('#pri_dpjp').val() || ''
                );

            // console.log(
            //     'DPJP OTOMATIS:',
            //     dpjp
            // );

            if (!dpjp) {

                console.warn(
                    'DPJP otomatis kosong.'
                );

                /*
                * Jika memang DPJP wajib ada dari sistem,
                * jangan lanjut menyimpan.
                */
                valid = false;
            }

            if (!valid) {

                if (firstInvalid) {
                    firstInvalid.trigger('focus');
                }

                // console.log(
                //     'VALIDASI MRS: GAGAL'
                // );

                return false;
            }

            // console.log(
            //     'VALIDASI MRS: OK'
            // );

            return true;
        }


        // ==========================================================
        // GET DATA
        // ==========================================================

        function getAdmission() {

            if (!$form.length) {

                console.warn(
                    'Form Admission Note tidak ditemukan.'
                );

                return;
            }


            isAdmissionNoteLoading =
                true;

            isAdmissionNoteHydrating =
                true;


            // console.log(
            //     'Admission Note - GET'
            // );


            $.ajax({

                url:
                    `/api/v2/emr/pengkajian/admission/${kunjungan}`,

                type:
                    'GET',

                dataType:
                    'json',


                success: function (res) {

                    const tlt = res.data;

                    // console.log('Admission Note - DATA GET:', tlt);

                    // ==================================================
                    // TIDAK ADA DATA
                    // ==================================================

                    if (!tlt) {

                        // console.log(
                        //     'Admission Note - belum ada data'
                        // );

                        lastSavedData =
                            getAdmissionFormData();

                        return;
                    }


                    // ==================================================
                    // TINDAK LANJUT
                    // ==================================================

                    const tlValue =
                        tlt.tl !== null &&
                        tlt.tl !== undefined
                            ? String(tlt.tl)
                            : '';

                    $form.find('input[name="tl"]')
                        .prop('checked', false);

                    if (tlValue !== '') {

                        $form.find(
                            'input[name="tl"][value="' + tlValue + '"]'
                        ).prop(
                            'checked',
                            true
                        );
                    }


                    // console.log(
                    //     'GET TL:',
                    //     tlValue,
                    //     'MRS checked:',
                    //     $form.find('#tl_mrs').is(':checked'),
                    //     'Pulang checked:',
                    //     $form.find('#tl_pulang').is(':checked')
                    // );


                    // ==================================================
                    // RUJUK
                    // ==================================================

                    const rujukValue =
                        tlt.rujuk !== null &&
                        tlt.rujuk !== undefined
                            ? String(tlt.rujuk)
                            : '';

                    $form.find('input[name="rujuk"]')
                        .prop('checked', false);

                    if (rujukValue !== '') {

                        $form.find(
                            'input[name="rujuk"][value="' + rujukValue + '"]'
                        ).prop(
                            'checked',
                            true
                        );
                    }


                    // ==================================================
                    // RUJUK LAINNYA
                    // ==================================================

                    $form.find('#rujuk_lainnya')
                        .val(
                            tlt.rujuk_lainnya ?? ''
                        );


                    // ==================================================
                    // PRI RUANG
                    // ==================================================

                    $form.find('#pri_ruang')
                        .val(
                            tlt.pri_ruang ?? ''
                        );


                    // Trigger change hanya untuk refresh UI select
                    $form.find('#pri_ruang')
                        .trigger('change');


                    // ==================================================
                    // PRI PERAWATAN
                    // ==================================================

                    $form.find('#pri_perawatan')
                        .val(
                            tlt.pri_perawatan ?? ''
                        );


                    $form.find('#pri_perawatan')
                        .trigger('change');


                    // ==================================================
                    // PRI TANGGAL
                    // ==================================================

                    let tanggal = '';

                    if (
                        tlt.tanggal !== null &&
                        tlt.tanggal !== undefined &&
                        String(tlt.tanggal).trim() !== ''
                    ) {

                        /*
                        * API:
                        * 2026-09-14 10:45:37
                        *
                        * Ambil hanya:
                        * 2026-09-14
                        */
                        tanggal =
                            String(tlt.tanggal)
                                .substring(0, 10);
                    }


                    // console.log(
                    //     'GET tanggal:',
                    //     tlt.tanggal,
                    //     '→',
                    //     tanggal
                    // );


                    if (tanggal !== '') {

                        if (admissionDatePicker) {

                            admissionDatePicker.setDate(
                                tanggal,
                                false
                            );

                        } else {

                            $form.find('#pri_tgl')
                                .val(tanggal);
                        }

                    } else {

                        if (admissionDatePicker) {

                            admissionDatePicker.clear();

                        } else {

                            $form.find('#pri_tgl')
                                .val('');
                        }
                    }


                    // ==================================================
                    // PRI INDIKASI
                    // ==================================================

                    $form.find('#pri_indikasi')
                        .val(
                            tlt.pri_indikasi ?? ''
                        );


                    // ==================================================
                    // PRI KETERANGAN
                    // ==================================================

                    $form.find('#pri_ket')
                        .val(
                            tlt.pri_ket ?? ''
                        );


                    // ==================================================
                    // PRI DPJP
                    // ==================================================

                    if (
                        tlt.pri_dpjp !== null &&
                        tlt.pri_dpjp !== undefined &&
                        String(tlt.pri_dpjp).trim() !== ''
                    ) {

                        $form.find('#pri_dpjp')
                            .val(tlt.pri_dpjp);

                    }


                    // ==================================================
                    // DEBUG HASIL SET
                    // ==================================================

                    // console.log(
                    //     '===================================='
                    // );

                    // console.log(
                    //     'HASIL HYDRATION FORM:'
                    // );

                    // console.log(
                    //     'tl:',
                    //     $form.find('input[name="tl"]:checked').val()
                    // );

                    // console.log(
                    //     'rujuk:',
                    //     $form.find('input[name="rujuk"]:checked').val()
                    // );

                    // console.log(
                    //     'rujuk_lainnya:',
                    //     $form.find('#rujuk_lainnya').val()
                    // );

                    // console.log(
                    //     'pri_ruang:',
                    //     $form.find('#pri_ruang').val()
                    // );

                    // console.log(
                    //     'pri_perawatan:',
                    //     $form.find('#pri_perawatan').val()
                    // );

                    // console.log(
                    //     'pri_tgl:',
                    //     $form.find('#pri_tgl').val()
                    // );

                    // console.log(
                    //     'pri_indikasi:',
                    //     $form.find('#pri_indikasi').val()
                    // );

                    // console.log(
                    //     'pri_ket:',
                    //     $form.find('#pri_ket').val()
                    // );

                    // console.log(
                    //     'pri_dpjp:',
                    //     $form.find('#pri_dpjp').val()
                    // );

                    // console.log(
                    //     '===================================='
                    // );


                    // ==================================================
                    // BASELINE
                    // ==================================================

                    lastSavedData =
                        getAdmissionFormData();


                    // console.log(
                    //     'Admission Note - GET selesai:',
                    //     lastSavedData
                    // );
                },


                error: function (
                    xhr,
                    status,
                    error
                ) {

                    // console.error(
                    //     'Admission Note GET ERROR:',
                    //     xhr.responseText ||
                    //     error
                    // );
                },


                complete: function () {

                    setTimeout(function () {

                        // ==================================================
                        // VISIBILITY BERDASARKAN HASIL GET
                        // ==================================================

                        if (
                            $form.find('#tl_mrs').is(':checked')
                        ) {

                            $form.find('#pri')
                                .stop(true, true)
                                .show();

                        } else {

                            $form.find('#pri')
                                .stop(true, true)
                                .hide();
                        }


                        // ==================================================
                        // RUJUK LAINNYA
                        // ==================================================

                        if (
                            $form.find('#rujuk_lain').is(':checked')
                        ) {

                            $form.find('#rujuk_lainnya')
                                .stop(true, true)
                                .show();

                        } else {

                            $form.find('#rujuk_lainnya')
                                .stop(true, true)
                                .hide();
                        }


                        lastSavedData =
                            getAdmissionFormData();


                        isAdmissionNoteHydrating =
                            false;

                        isAdmissionNoteLoading =
                            false;


                        // console.log(
                        //     'Admission Note - hydration selesai'
                        // );

                    }, 0);
                }

            });
        }


        // ==========================================================
        // SIMPAN ADMISSION
        // ==========================================================

        function simpanAdmission(
            forceSave = false
        ) {
            // console.log('====================================');
            // console.log('MASUK simpanAdmission()');
            // console.log('forceSave:', forceSave);
            // console.log('isLoading:', isAdmissionNoteLoading);
            // console.log('isHydrating:', isAdmissionNoteHydrating);
            // console.log('isSaving:', isAdmissionNoteSaving);
            // ======================================================
            // PROTEKSI
            // ======================================================

            if (!$form.length) {
                return;
            }


            if (
                isAdmissionNoteLoading ||
                isAdmissionNoteHydrating
            ) {

                // console.log(
                //     'SAVE DIBATALKAN: masih loading/hydrating'
                // );

                return;
            }


            if (isAdmissionNoteSaving) {

                // console.log(
                //     'SAVE DIBATALKAN: masih ada request'
                // );

                return;
            }


            // ======================================================
            // MRS
            // ======================================================

            const isMRS =
                $form.find('#tl_mrs').is(':checked');


            if (isMRS) {

                /*
                 * MRS harus lengkap.
                 */

                if (
                    !isAdmissionMRSComplete()
                ) {

                    // console.log(
                    //     'SAVE DIBATALKAN: PRI belum lengkap'
                    // );

                    return;
                }


                if (
                    !validateAdmissionMRS()
                ) {

                    return;
                }
            }


            // ======================================================
            // CEK PERUBAHAN
            // ======================================================

            /*
             * Jika forceSave = true,
             * JANGAN gunakan hasAdmissionChanged().
             *
             * Ini yang membedakan dengan script sebelumnya.
             */

            if (
                !forceSave &&
                !hasAdmissionChanged()
            ) {

                // console.log(
                //     'SAVE DIBATALKAN: tidak ada perubahan'
                // );

                return;
            }


            // ======================================================
            // AMBIL DATA
            // ======================================================

            const data =
                getFormDataByName(
                    $form,
                    {
                        NOKUNJ: kunjungan
                    }
                );


            // console.log(
            //     '===================================='
            // );

            // console.log(
            //     'ADMISSION NOTE AKAN DISIMPAN'
            // );

            // console.log(
            //     'Tindak lanjut:',
            //     data.tl
            // );

            // console.log(
            //     'PRI ruang:',
            //     data.pri_ruang
            // );

            // console.log(
            //     'PRI perawatan:',
            //     data.pri_perawatan
            // );

            // console.log(
            //     'PRI tanggal:',
            //     data.pri_tgl
            // );

            // console.log(
            //     'PRI indikasi:',
            //     data.pri_indikasi
            // );

            // console.log(
            //     'PRI keterangan:',
            //     data.pri_ket
            // );

            // console.log(
            //     'PRI DPJP:',
            //     data.pri_dpjp
            // );

            // console.log(
            //     '===================================='
            // );


            isAdmissionNoteSaving =
                true;


            // ======================================================
            // AJAX
            // ======================================================

            $.ajax({

                url:
                    `/api/v2/emr/pengkajian/admission/${kunjungan}/simpan`,

                type:
                    'POST',

                data:
                    data,


                headers: {

                    'X-CSRF-TOKEN':
                        $('meta[name="csrf-token"]')
                            .attr('content')

                },


                success: function (res) {

                    // console.log(
                    //     '===================================='
                    // );

                    // console.log(
                    //     'ADMISSION NOTE BERHASIL DISIMPAN'
                    // );

                    // console.log(
                    //     res
                    // );

                    // console.log(
                    //     '===================================='
                    // );


                    /*
                     * Setelah berhasil,
                     * jadikan data sekarang sebagai baseline.
                     */

                    lastSavedData =
                        getAdmissionFormData();
                },


                error: function (xhr) {

                    // console.error(
                    //     '===================================='
                    // );

                    // console.error(
                    //     'ADMISSION NOTE GAGAL DISIMPAN'
                    // );

                    // console.error(
                    //     xhr.responseText
                    // );

                    // console.error(
                    //     '===================================='
                    // );


                    let message =
                        'Data Admission Note gagal disimpan.';


                    if (
                        xhr.status === 422 &&
                        xhr.responseJSON?.errors
                    ) {

                        message =
                            Object
                                .values(
                                    xhr.responseJSON.errors
                                )
                                .flat()
                                .join('<br>');

                    }

                    else if (
                        xhr.responseJSON?.message
                    ) {

                        message =
                            xhr.responseJSON.message;
                    }


                    iziToast.error({

                        title:
                            'Validasi Gagal!',

                        message:
                            message,

                        position:
                            'topRight'
                    });
                },


                complete: function () {

                    isAdmissionNoteSaving =
                        false;
                }

            });
        }


        // ==========================================================
        // TRIGGER AUTOSAVE PRI
        // ==========================================================

        function triggerAdmissionPRIAutosave() {

            clearTimeout(admissionSaveTimer);

            admissionSaveTimer = setTimeout(function () {

                const tl = $form.find('input[name="tl"]:checked').val();

                // console.log('====================================');
                // console.log('TRIGGER PRI AUTOSAVE');
                // console.log('TL:', tl);

                if (String(tl) !== '1') {
                    // console.log('BATAL: bukan MRS');
                    return;
                }

                if (!isAdmissionMRSComplete()) {
                    // console.log('BATAL: PRI belum lengkap');
                    return;
                }

                // console.log('PRI LENGKAP → PANGGIL SIMPAN');

                simpanAdmission(true);

            }, 300);
        }


        // ==========================================================
        // DOCUMENT READY
        // ==========================================================

        $(function () {

            if (!$form.length) {
                return;
            }


            // ======================================================
            // INITIAL HIDE
            // ======================================================

            $form.find('#pri').hide();

            $form.find('#rujuk_lainnya').hide();


            // ======================================================
            // FLATPICKR
            // ======================================================

            const today =
                new Date();


            const fiveYearsAgo =
                new Date();


            fiveYearsAgo.setFullYear(
                today.getFullYear() - 5
            );


            admissionDatePicker =
                $form.find('#pri_tgl').flatpickr({

                    mode:
                        'single',

                    minDate:
                        fiveYearsAgo,

                    maxDate:
                        today,

                    dateFormat:
                        'Y-m-d',


                    onChange:
                        function () {

                            if (
                                isAdmissionNoteLoading ||
                                isAdmissionNoteHydrating
                            ) {

                                return;
                            }


                            $form.find('#pri_tgl')
                                .removeClass(
                                    'is-invalid'
                                );


                            triggerAdmissionPRIAutosave();
                        }

                });


            // ======================================================
            // TINDAK LANJUT
            // ======================================================

            $form.on(
                'change',
                'input[name="tl"]',
                function () {

                    if (
                        isAdmissionNoteLoading ||
                        isAdmissionNoteHydrating
                    ) {

                        return;
                    }


                    // ==================================================
                    // MRS
                    // ==================================================

                    if (
                        $form.find('#tl_mrs').is(':checked')
                    ) {

                        $form.find('#pri')
                            .stop(true, true)
                            .slideDown();


                        setTodayAdmissionDate();


                        /*
                         * Coba autosave.
                         *
                         * Biasanya belum lengkap sehingga
                         * belum POST.
                         */

                        triggerAdmissionPRIAutosave();

                    }


                    // ==================================================
                    // PULANG
                    // ==================================================

                    else {

                        $form.find('#pri')
                            .stop(true, true)
                            .slideUp();


                        /*
                         * Pulang tetap langsung disimpan.
                         */

                        simpanAdmission(true);
                    }

                }
            );


            // ======================================================
            // RUJUKAN
            // ======================================================

            $form.on(
                'change',
                'input[name="rujuk"]',
                function () {

                    if (
                        isAdmissionNoteLoading ||
                        isAdmissionNoteHydrating
                    ) {

                        return;
                    }


                    if (
                        $form.find('#rujuk_lain').is(':checked')
                    ) {

                        $form.find('#rujuk_lainnya')
                            .stop(true, true)
                            .slideDown();

                    } else {

                        $form.find('#rujuk_lainnya')
                            .stop(true, true)
                            .slideUp();
                    }


                    /*
                     * Rujukan tidak perlu menunggu PRI lengkap.
                     */

                    simpanAdmission(true);

                }
            );


            // ======================================================
            // SELECT PRI
            // ======================================================

            $form.on(
                'change',
                '#pri select',
                function () {

                    if (
                        isAdmissionNoteLoading ||
                        isAdmissionNoteHydrating
                    ) {

                        return;
                    }


                    $(this).removeClass(
                        'is-invalid'
                    );


                    // console.log(
                    //     'PRI SELECT CHANGE:',
                    //     this.id,
                    //     this.value
                    // );


                    triggerAdmissionPRIAutosave();

                }
            );


            // ======================================================
            // TEXTAREA PRI
            // ======================================================

            $form.on(
                'input',
                '#pri textarea',
                function () {

                    if (
                        isAdmissionNoteLoading ||
                        isAdmissionNoteHydrating
                    ) {

                        return;
                    }


                    $(this).removeClass(
                        'is-invalid'
                    );


                    // console.log(
                    //     'PRI TEXTAREA INPUT:',
                    //     this.id,
                    //     this.value
                    // );


                    triggerAdmissionPRIAutosave();

                }
            );


            // ======================================================
            // BLUR TEXTAREA
            // ======================================================

            $form.on(
                'blur',
                '#pri textarea',
                function () {

                    if (
                        isAdmissionNoteLoading ||
                        isAdmissionNoteHydrating
                    ) {

                        return;
                    }


                    // console.log(
                    //     'PRI TEXTAREA BLUR:',
                    //     this.id,
                    //     this.value
                    // );


                    triggerAdmissionPRIAutosave();

                }
            );


            // ======================================================
            // TEXT INPUT PRI
            // ======================================================

            $form.on(
                'input',
                '#pri input[type="text"]',
                function () {

                    if (
                        isAdmissionNoteLoading ||
                        isAdmissionNoteHydrating
                    ) {

                        return;
                    }


                    $(this).removeClass(
                        'is-invalid'
                    );


                    // console.log(
                    //     'PRI INPUT:',
                    //     this.id,
                    //     this.value
                    // );


                    triggerAdmissionPRIAutosave();

                }
            );


            // ======================================================
            // LOAD DATA
            // ======================================================

            getAdmission();

        });

    })();
</script>
