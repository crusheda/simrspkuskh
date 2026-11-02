@php
    $formType = strtolower($form ?? 'dewasa');

    $diagnosaByForm = [
        'dewasa' => [
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => 'Bersihan jalan nafas tidak efektif',
            'POLA_NAFAS_TIDAK_EFEKTIF' => 'Pola nafas tidak efektif',
            'PERFUSI_PERIFER_TIDAK_EFEKTIF' => 'Perfusi perifer tidak efektif',
            'DIARE' => 'Diare',
            'NYERI_AKUT' => 'Nyeri akut',
            'NAUSEA' => 'Nausea',
            'HIPERTERMI' => 'Hipertermia',
            'ANSIETAS' => 'Ansietas',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'GANGGUAN_ELIMINASI_URINE' => 'Gangguan eliminasi urine',
            'INTOLERANSI_AKTIVITAS' => 'Intoleransi aktivitas',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'GANGGUAN_PERTUKARAN_GAS' => 'Gangguan pertukaran gas',
        ],
        'anak' => [
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => 'Bersihan jalan nafas tidak efektif',
            'POLA_NAFAS_TIDAK_EFEKTIF' => 'Pola nafas tidak efektif',
            'PERFUSI_PERIFER_TIDAK_EFEKTIF' => 'Perfusi perifer tidak efektif',
            'DIARE' => 'Diare',
            'NYERI_AKUT' => 'Nyeri akut',
            'NAUSEA' => 'Nausea',
            'HIPERTERMI' => 'Hipertermia',
            'ANSIETAS' => 'Ansietas',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'GANGGUAN_ELIMINASI_URINE' => 'Gangguan eliminasi urine',
            'INTOLERANSI_AKTIVITAS' => 'Intoleransi aktivitas',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'GANGGUAN_PERTUKARAN_GAS' => 'Gangguan pertukaran gas',
        ],
        'psikiatri' => [
            'ANSIETAS' => 'Ansietas',
            'DEFISIT_PENGETAHUAN' => 'Defisit Pengetahuan',
            'RISIKO_PERILAKU_KEKERASAN' => 'Resiko Perilaku Kekerasan',
            'DEFISIT_PERAWATAN_DIRI' => 'Defisit Perawatan Diri',
            'HARGA_DIRI_RENDAH' => 'Harga Diri Rendah',
            'ISOLASI_SOSIAL' => 'Isolasi Sosial',
            'KEPUTUSASAAN' => 'Keputusasaan',
            'KOPING_TIDAK_EFEKTIF' => 'Koping Tidak Efektif',
            'WAHAM' => 'Waham',
            'PERILAKU_KEKERASAN' => 'Perilaku Kekerasan',
            'GANGGUAN_PERSEPSI_SENSORI' => 'Gangguan Persepsi Sensori',
        ],
        'obsgyn' => [
            'NYERI' => 'Nyeri',
            'GANGGUAN_PERFUSI_JARINGAN_CEREBRAL' => 'Gangguan perfusi jaringan cerebral',
            'CEMAS' => 'Cemas',
            'GANGGUAN_PERSEPSI_SENSORI' => 'Gangguan persepsi sensori',
            'HIPERTERMI' => 'Hipertermia',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'PERFUSI_JARINGAN_TIDAK_EFEKTIF' => 'Perfusi jaringan tidak efektif',
            'BODY_IMAGE' => 'Body Image',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'DEFISIT_PENGETAHUAN' => 'Defisit pengetahuan',
            'DEFISIT_NUTRISI' => 'Defisit nutrisi',
        ],
    ];

    $diagnosaKeperawatan = $diagnosaByForm[$formType] ?? $diagnosaByForm['dewasa'];
    $perKolom = (int) ceil(count($diagnosaKeperawatan) / 3);
    $diagnosaColumns = array_chunk($diagnosaKeperawatan, $perKolom, true);
    $formId = 'form_masalah_keperawatan_' . $formType;
@endphp

<div class="row align-items-center" id="{{ $formId }}">
    <div class="col-md-12 mb-1">
        <div class="form-group mb-2">
            <h5 class="mb-0 text-success"><strong>Masalah Keperawatan</strong></h5>
        </div>
    </div>
    <div class="col-md-12">
        <div class="row">
            @foreach ($diagnosaColumns as $columnIndex => $column)
                <div class="col-md-4">
                    @foreach ($column as $field => $label)
                        @php
                            $nomor = ($columnIndex * $perKolom) + $loop->index + 1;
                            $name = "dmk_{$nomor}";
                            $inputId = "{$formType}_{$name}";
                        @endphp
                        <div class="form-group mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input check-primary" type="checkbox"
                                        name="{{ $name }}" id="{{ $inputId }}" value="1"
                                        data-field="{{ $field }}">
                                </div>
                                <label class="form-label mb-0" for="{{ $inputId }}">{{ $label }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
        {{-- MASALAH LAIN --}}
        <div class="form-group mt-2">
            <label class="form-label fw-bold">
                Masalah Keperawatan Lain
            </label>

            <textarea
                class="form-control form-control-sm masalah-keperawatan-lain"
                name="MASALAH_LAIN"
                rows="2"
                placeholder="Masukkan masalah keperawatan lain jika ada..."
            ></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find(@json('#' . $formId));
    const formType = @json($formType);

    if (!$form.length) {
        console.warn('Form Daftar Masalah Keperawatan tidak ditemukan.');
        return;
    }

    let isLoading = false;
    let isSaving = false;
    let savePending = false;

    function getMasalahKeperawatan() {
        isLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/masalahkeperawatan/${kunjungan}`,
            type: 'GET',
            dataType: 'json',
            data: { form: formType },
            success: function (res) {
                const data = res?.data;

                if (!data) {
                    return;
                }

                $form.find('input[type="checkbox"][data-field]').each(function () {
                    const $input = $(this);
                    const field = $input.data('field');
                    $input.prop('checked', FormHelper.hasValue(data[field]) && Number(data[field]) === 1);
                });
                // Load Masalah Keperawatan Lain
                $form.find('.masalah-keperawatan-lain').val(
                    data.MASALAH_LAIN ?? ''
                );
            },
            error: function (xhr, status, error) {
                console.error('Error Daftar Masalah Keperawatan:', xhr.responseText || error);
            },
            complete: function () {
                isLoading = false;
            }
        });
    }

    function simpanMasalahKeperawatan() {
        if (isLoading) {
            return;
        }

        if (isSaving) {
            savePending = true;
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan,
            form: formType
        });

        isSaving = true;
        savePending = false;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/masalahkeperawatan/${kunjungan}/simpan`,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                if (!res?.status) {
                    console.warn('Masalah Keperawatan gagal disimpan:', res?.message);
                }
            },
            error: function (xhr) {
                let message = 'Data Daftar Masalah Keperawatan gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },
            complete: function () {
                isSaving = false;

                if (savePending) {
                    simpanMasalahKeperawatan();
                }
            }
        });
    }

    getMasalahKeperawatan();

    $form.on('change', 'input[type="checkbox"]', function () {
        simpanMasalahKeperawatan();
    });

    $form.on(
        'blur',
        '.masalah-keperawatan-lain',
        function () {
            simpanMasalahKeperawatan();
        }
    );
})();
</script>
