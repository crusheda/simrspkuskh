@extends('layouts.index')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pelayanan</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Penunjang</a></li>
                    <li class="breadcrumb-item" aria-current="page">RIS</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Radiology Information System</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header p-3">
                <div class="d-sm-flex align-items-center justify-content-between ms-2">
                    <h6 class="mt-2"><i class="fas fa-filter text-primary me-2"></i> Filter</h6>
                    <div class="dropdown">
                        {{-- <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical f-18"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(0)">Batal Kunjungan</a>
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(1)">Sedang Dilayani</a>
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(2)">Selesai Kunjungan</a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                {{-- <div id="toolbar"></div>
                <div id="dwv" style="width:512px;height:512px;"></div> --}}
                <div id="dicomViewer" style="width:100%;height:100vh;border:1px solid #333;"></div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
<!-- Load library -->
<script src="https://unpkg.com/cornerstone-core@2.6.1/dist/cornerstone.min.js"></script>
<script src="https://unpkg.com/dicom-parser@1.8.7/dist/dicomParser.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cornerstone-wado-image-loader@4.13.2/dist/cornerstoneWADOImageLoader.bundle.min.js"></script>
<script src="https://unpkg.com/hammerjs@2.0.8/hammer.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cornerstone-tools@6.0.10/dist/cornerstoneTools.min.js"></script>

<script>
    const element = document.getElementById('dicomViewer');
    cornerstone.enable(element);

    // registrasi image loader
    cornerstoneWADOImageLoader.external.cornerstone = cornerstone;
    cornerstoneWADOImageLoader.external.dicomParser = dicomParser;

    cornerstoneWADOImageLoader.webWorkerManager.initialize({
            maxWebWorkers: navigator.hardwareConcurrency || 1,
            startWebWorkersOnDemand: true,
            webWorkerPath:
            "https://cdn.jsdelivr.net/npm/cornerstone-wado-image-loader@4.13.2/dist/cornerstoneWADOImageLoaderWebWorker.min.js",
            taskConfiguration: {
            decodeTask: {
                codecsPath:
                "https://cdn.jsdelivr.net/npm/cornerstone-wado-image-loader@4.13.2/dist/cornerstoneWADOImageLoaderCodecs.min.js",
            },
        },
    });

    // load dicom
    var imageId = "wadouri:http://localhost:8000/api/dcom/MUHAMMAD ALLEGRA 00122310.dcm";

    cornerstone
        .loadImage(imageId)
        .then(function (image) {
            cornerstone.displayImage(element, image);
            cornerstone.fitToWindow(element); 
            cornerstoneTools.setToolActive("Wwwc", { mouseButtonMask: 1 });
        })
        .catch(function (err) {
            console.error("Gagal load DICOM:", err);
        });
</script>
@endsection