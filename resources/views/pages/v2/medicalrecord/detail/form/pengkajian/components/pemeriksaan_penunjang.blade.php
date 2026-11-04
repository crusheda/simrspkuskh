{{-- HASIL RIWAYAT LAB & RADIOLOGI ALL --}}
<div class="accordion accordion-flush p-2 border bg-light rounded" id="accordionPemeriksaanPenunjang">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#PemeriksaanPenunjang-collapseOne" aria-expanded="false"
                aria-controls="PemeriksaanPenunjang-collapseOne">Hasil Laboratorium</button>
        </h2>
        <div id="PemeriksaanPenunjang-collapseOne" class="accordion-collapse collapse" aria-labelledby="headingLaboratorium">
            <div class="accordion-body">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_laborat')
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#PemeriksaanPenunjang-collapseTwo" aria-expanded="false"
                aria-controls="PemeriksaanPenunjang-collapseTwo">Hasil Radiologi</button>
        </h2>
        <div id="PemeriksaanPenunjang-collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingRadiologi">
            <div class="accordion-body">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_radiologi')
            </div>
        </div>
    </div>
</div>
