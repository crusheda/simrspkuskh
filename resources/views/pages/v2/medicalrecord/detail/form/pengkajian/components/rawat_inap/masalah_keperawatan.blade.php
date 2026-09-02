@php
    $formType = strtolower($form ?? 'dewasa');

    $diagnosaByForm = [
        'dewasa' => [
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => 'Bersihan jalan nafas tidak efektif',
            'GANGGUAN_PERTUKARAN_GAS' => 'Gangguan pertukaran gas',
            'GANGGUAN_VENTILASI_SPONTAN' => 'Gangguan ventilasi spontan',
            'POLA_NYERI_TIDAK_EFEKTIF' => 'Pola nyeri tidak efektif',
            'GANGGUAN_SIRKULASI_SPONTAN' => 'Gangguan sirkulasi spontan',
            'PENURUNAN_CURAH_JANTUNG' => 'Penurunan curah jantung',
            'PERFUSI_PERIFER_TIDAK_EFEKTIF' => 'Perfusi perifer tidak efektif',
            'TERMOREGULASI_TIDAK_EFEKTIF' => 'Termoregulasi tidak efektif',
            'RESIKO_PERFUSI_GASTROINTESTINAL_TIDAK_EFEKTIF' => 'Resiko perfusi gastrointestinal tidak efektif',
            'RESIKO_PERDARAHAN' => 'Resiko perdarahan',
            'DEFISIT_NUTRISI' => 'Defisit nutrisi',
            'DIARE' => 'Diare',
            'KETIDAKSTABILAN_KADAR_GLUKOSA_DARAH' => 'Ketidakstabilan kadar glukosa darah',
            'RESIKO_KETIDAKSEIMBANGAN_CAIRAN' => 'Resiko ketidakseimbangan cairan',
            'RESIKO_KETIDAKSEIMBANGAN_ELEKTROLIT' => 'Resiko ketidakseimbangan elektrolit',
            'RESIKO_SYOK' => 'Resiko syok',
            'DISFUNGSI_MOTILITAS_GASTROINTESTINAL' => 'Disfungsi motilitas gastrointestinal',
            'GANGGUAN_ELIMINASI_URINE' => 'Gangguan eliminasi urine',
            'KONSTIPASI' => 'Konstipasi',
            'RETENSI_URINE' => 'Retensi urine',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'GANGGUAN_POLA_TIDUR' => 'Gangguan pola tidur',
            'INTOLERANSI_AKTIVITAS' => 'Intoleransi aktivitas',
            'GANGGUAN_MENELAN' => 'Gangguan menelan',
            'GANGGUAN_RASA_NYAMAN' => 'Gangguan rasa nyaman',
            'NAUSEA' => 'Nausea',
            'NYERI_AKUT' => 'Nyeri akut',
            'NYERI_KRONIS' => 'Nyeri kronis',
            'ANSIETAS' => 'Ansietas',
            'GANGGUAN_PERSEPSI_SENSORI' => 'Gangguan persepsi sensori',
            'DEFISIT_PERAWATAN_DIRI' => 'Defisit perawatan diri',
            'DEFISIT_PENGETAHUAN' => 'Defisit pengetahuan',
            'GANGGUAN_INTERAKSI_SOSIAL' => 'Gangguan interaksi sosial',
            'GANGGUAN_KOMUNIKASI_VERBAL' => 'Gangguan komunikasi verbal',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'HIPERTERMI' => 'Hipertermia',
            'HIPOTERMI' => 'Hipotermia',
            'PERLAMBATAN_PEMULIHAN_PASCA_BEDAH' => 'Perlambatan pemulihan pasca bedah',
            'RESIKO_ALERGI' => 'Resiko alergi',
            'RESIKO_CIDERA' => 'Resiko cidera',
            'RESIKO_INFEKSI' => 'Resiko infeksi',
            'HIPERVOLEMIA' => 'Hipervolemia',
            'HIPOVOLEMIA' => 'Hipovolemia',
            'BERAT_BADAN_LEBIH' => 'Berat badan lebih',
            'CEMAS' => 'Cemas',
        ],
        'anak' => [
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => 'Bersihan jalan nafas tidak efektif',
            'GANGGUAN_PERTUKARAN_GAS' => 'Gangguan pertukaran gas',
            'GANGGUAN_VENTILASI_SPONTAN' => 'Gangguan ventilasi spontan',
            'POLA_NYERI_TIDAK_EFEKTIF' => 'Pola nyeri tidak efektif',
            'GANGGUAN_SIRKULASI_SPONTAN' => 'Gangguan sirkulasi spontan',
            'PENURUNAN_CURAH_JANTUNG' => 'Penurunan curah jantung',
            'PERFUSI_PERIFER_TIDAK_EFEKTIF' => 'Perfusi perifer tidak efektif',
            'TERMOREGULASI_TIDAK_EFEKTIF' => 'Termoregulasi tidak efektif',
            'RESIKO_PERFUSI_GASTROINTESTINAL_TIDAK_EFEKTIF' => 'Resiko perfusi gastrointestinal tidak efektif',
            'RESIKO_PERDARAHAN' => 'Resiko perdarahan',
            'DEFISIT_NUTRISI' => 'Defisit nutrisi',
            'DIARE' => 'Diare',
            'KETIDAKSTABILAN_KADAR_GLUKOSA_DARAH' => 'Ketidakstabilan kadar glukosa darah',
            'RESIKO_KETIDAKSEIMBANGAN_CAIRAN' => 'Resiko ketidakseimbangan cairan',
            'RESIKO_KETIDAKSEIMBANGAN_ELEKTROLIT' => 'Resiko ketidakseimbangan elektrolit',
            'RESIKO_SYOK' => 'Resiko syok',
            'DISFUNGSI_MOTILITAS_GASTROINTESTINAL' => 'Disfungsi motilitas gastrointestinal',
            'GANGGUAN_ELIMINASI_URINE' => 'Gangguan eliminasi urine',
            'KONSTIPASI' => 'Konstipasi',
            'RETENSI_URINE' => 'Retensi urine',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'GANGGUAN_POLA_TIDUR' => 'Gangguan pola tidur',
            'INTOLERANSI_AKTIVITAS' => 'Intoleransi aktivitas',
            'GANGGUAN_MENELAN' => 'Gangguan menelan',
            'GANGGUAN_RASA_NYAMAN' => 'Gangguan rasa nyaman',
            'NAUSEA' => 'Nausea',
            'NYERI_AKUT' => 'Nyeri akut',
            'NYERI_KRONIS' => 'Nyeri kronis',
            'ANSIETAS' => 'Ansietas',
            'GANGGUAN_PERSEPSI_SENSORI' => 'Gangguan persepsi sensori',
            'DEFISIT_PERAWATAN_DIRI' => 'Defisit perawatan diri',
            'DEFISIT_PENGETAHUAN' => 'Defisit pengetahuan',
            'GANGGUAN_INTERAKSI_SOSIAL' => 'Gangguan interaksi sosial',
            'GANGGUAN_KOMUNIKASI_VERBAL' => 'Gangguan komunikasi verbal',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'HIPERTERMI' => 'Hipertermia',
            'HIPOTERMI' => 'Hipotermia',
            'PERLAMBATAN_PEMULIHAN_PASCA_BEDAH' => 'Perlambatan pemulihan pasca bedah',
            'RESIKO_ALERGI' => 'Resiko alergi',
            'RESIKO_CIDERA' => 'Resiko cidera',
            'RESIKO_INFEKSI' => 'Resiko infeksi',
            'HIPERVOLEMIA' => 'Hipervolemia',
            'HIPOVOLEMIA' => 'Hipovolemia',
            'BERAT_BADAN_LEBIH' => 'Berat badan lebih',
            'CEMAS' => 'Cemas',
        ],
        'neonatus' => [
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => 'Bersihan jalan nafas tidak efektif',
            'POLA_NAFAS_TIDAK_EFEKTIF' => 'Pola nafas tidak efektif',
            'GANGGUAN_PERTUKARAN_GAS' => 'Gangguan pertukaran gas',
            'PERFUSI_JARINGAN_TIDAK_EFEKTIF' => 'Perfusi jaringan tidak efektif',
            'HIPOTERMI' => 'Hipotermia',
            'GANGGUAN_KESEIMBANGAN_CAIRAN_ELEKTROLIT' => 'Gangguan keseimbangan cairan dan elektrolit',
            'RESIKO_KERUSAKAN_INTEGRITAS_KULIT' => 'Resiko kerusakan integritas kulit',
            'HIPERTERMI' => 'Hipertermia',
            'GANGGUAN_PERFUSI_JARINGAN_CEREBRAL' => 'Gangguan perfusi jaringan cerebral',
            'KONSTIPASI' => 'Konstipasi',
            'DIARE' => 'Diare',
            'RESIKO_TINGGI_MALNUTRISI' => 'Resiko tinggi malnutrisi',
            'KOPING_KELUARGA_TIDAK_EFEKTIF' => 'Koping keluarga tidak efektif',
            'RESIKO_TERHADAP_ASPIRASI' => 'Resiko terhadap aspirasi',
            'KETIDAKSEIMBANGAN_NUTRISI' => 'Ketidakseimbangan nutrisi',
            'GANGGUAN_ELIMINASI' => 'Gangguan eliminasi',
            'RETENSI_URINE' => 'Retensi urine',
            'KECEMASAN_ORANG_TUA' => 'Kecemasan orang tua',
            'NYERI' => 'Nyeri',
        ],
        'obsgyn' => [
            'NYERI' => 'Nyeri',
            'RESIKO_PERDARAHAN' => 'Resiko perdarahan',
            'RESIKO_INFEKSI' => 'Resiko infeksi',
            'RESIKO_SYOK' => 'Resiko syok',
            'RETENSI_URINE' => 'Retensi urine',
            'DEFISIT_NUTRISI' => 'Defisit nutrisi',
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => 'Bersihan jalan nafas tidak efektif',
            'CEMAS' => 'Cemas',
            'DIARE' => 'Diare',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'GANGGUAN_KOMUNIKASI_VERBAL' => 'Gangguan komunikasi verbal',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'GANGGUAN_POLA_TIDUR' => 'Gangguan pola tidur',
            'HIPERTERMI' => 'Hipertermia',
            'HIPOTERMI' => 'Hipotermia',
            'KURANG_PERAWATAN_DIRI' => 'Kurang perawatan diri',
            'RESIKO_JATUH' => 'Resiko jatuh',
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
            url: `/api/v2/emr/pengkajian/ri/masalahkeperawatan/${kunjungan}`,
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
            url: `/api/v2/emr/pengkajian/ri/masalahkeperawatan/${kunjungan}/simpan`,
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
})();
</script>
