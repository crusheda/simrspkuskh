<ul class="nav nav-pills card-header-pills emr-main-nav gap-2 mb-2" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#porderresep" role="tab"
            aria-selected="false" tabindex="-1" id="tab-porderresep">
            <i class="ph-duotone ph-syringe me-2"></i> Resep Apotek
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#porderlab" role="tab"
            aria-selected="false" tabindex="-1" id="tab-porderlab">
            <i class="ph-duotone ph-flask me-2"></i> Laboratorium
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#porderrad" role="tab"
            aria-selected="false" tabindex="-1" id="tab-porderrad">
            <i class="ph-duotone ph-virus me-2"></i> Radiologi
        </button>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane" id="porderresep" role="tabpanel">
        @include('pages.v3.emr.detail.order.resep.form')
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane" id="porderlab" role="tabpanel">
        @include('pages.v3.emr.detail.order.lab.form')
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane" id="porderrad" role="tabpanel">
        @include('pages.v3.emr.detail.order.rad.form')
    </div>
</div>
