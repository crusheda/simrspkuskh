@extends('layouts.index')

@section('title', 'Display Rating')

@section('content')

<style>
    #myDiv:fullscreen,
    #myDiv:-webkit-full-screen {
        display: flex;
        flex-direction: column;
        justify-content: center; /* vertikal */
        align-items: center;     /* horizontal */
        height: 100vh;
        width: 100%;
        text-align: center;
    }
</style>

<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Display</a></li>
                    <li class="breadcrumb-item" aria-current="page">Rating</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Display <b class="text-danger">Rating</b></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<div class="row">
    <div class="card">
        <div class="card-header align-items-center d-flex justify-content-between py-2 px-0">
            <div>
                <div class="input-group">
                    @php
                        $bulan = collect([
                            (object)['bulan' => 'Januari'],
                            (object)['bulan' => 'Februari'],
                            (object)['bulan' => 'Maret'],
                            (object)['bulan' => 'April'],
                            (object)['bulan' => 'Mei'],
                            (object)['bulan' => 'Juni'],
                            (object)['bulan' => 'Juli'],
                            (object)['bulan' => 'Agustus'],
                            (object)['bulan' => 'September'],
                            (object)['bulan' => 'Oktober'],
                            (object)['bulan' => 'November'],
                            (object)['bulan' => 'Desember'],
                        ]);
                    @endphp
                    <select class="form-select" id="bulan_laporan">
                        <option value="">Pilih Bulan</option>
                        <option value="0">Semua Bulan</option>
                        @if ($bulan)
                            @foreach ($bulan as $key => $item)
                                <option value="{{ $key+1 }}">{{ $item->bulan }}</option>
                            @endforeach
                        @endif
                    </select>
                    <button class="btn btn-primary btn-wave waves-effect waves-light" id="tarik_laporan" onclick="laporan($('#bulan_laporan').val())" disabled>
                        <span class="pc-micon me-1"><i class="ph-duotone ph-file-pdf"></i></span> Tarik Laporan
                    </button>
                </div>
            </div>
            <div class="btn-group">
                {{-- <button id="btn-refresh" class="btn btn-warning btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                    data-bs-placement="bottom" title="Refresh Data Display">
                    <i class="fas fa-sync align-middle"></i>
                </button> --}}
                <button data-showrating="0" id="btn-show-rating" class="btn btn-info btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                    data-bs-placement="bottom" title="Tampilkan Nilai Rating">
                    <i class="fas fa-eye align-middle"></i>
                </button>
                <button id="openFullscreenBtn" class="btn btn-success d-inline-flex align-items-center" data-bs-toggle="tooltip"
                    data-bs-placement="left" title="Terapkan Display Layar Penuh">
                    <i class="ti ti-arrows-maximize me-2"></i> <span>Tampilan Layar Penuh</span>
                </button>
            </div>
        </div>
    </div>
    <div class="container-fluid py-3 card" id="myDiv">

        <h2 class="mt-4 mb-4 text-center">Bagaimana pengalaman Anda di Rumah Sakit Kami?</h2>

        <div class="emot-rating mb-5 d-flex justify-content-center gap-5">

            {{-- DIPAKAI --}}
            <div class="emot-item" hidden>
                <span class="emot" data-rating="5">😍</span>
                <div><b>PUAS</b></div>
                <div class="emot-count mt-3" id="count-5" hidden>{{ $rating[5] ?? 0 }}</div>
            </div>
            <div class="emot-item" hidden>
                <span class="emot" data-rating="1">😡</span>
                <div><b>TIDAK PUAS</b></div>
                <div class="emot-count mt-3" id="count-1" hidden>{{ $rating[1] ?? 0 }}</div>
            </div>

            {{-- TIDAK DIPAKAI --}}
            {{-- <div class="emot-item" hidden>
                <span class="emot" data-rating="5">😍</span>
                <div><b>Sangat Baik</b></div>
                <div class="emot-count mt-3" id="count-5" hidden>{{ $rating[5] ?? 0 }}</div>
            </div> --}}

            <div class="emot-item" hidden>
                <span class="emot" data-rating="4">😊</span>
                <div><b>Baik</b></div>
                <div class="emot-count mt-3" id="count-4" hidden>{{ $rating[4] ?? 0 }}</div>
            </div>

            <div class="emot-item" hidden>
                <span class="emot" data-rating="3">😐</span>
                <div><b>Cukup</b></div>
                <div class="emot-count mt-3" id="count-3" hidden>{{ $rating[3] ?? 0 }}</div>
            </div>

            <div class="emot-item" hidden>
                <span class="emot" data-rating="2">😕</span>
                <div><b>Kurang</b></div>
                <div class="emot-count mt-3" id="count-2" hidden>{{ $rating[2] ?? 0 }}</div>
            </div>

            {{-- <div class="emot-item" hidden>
                <span class="emot" data-rating="1">😡</span>
                <div><b>Sangat Kurang</b></div>
                <div class="emot-count mt-3" id="count-1" hidden>{{ $rating[1] ?? 0 }}</div>
            </div> --}}

        </div>

    </div>
</div>

<script>
    let ratingLock = false;
    $(document).ready(function() {
        const elem = $("#myDiv")[0];

        $("#openFullscreenBtn").on("click", function() {
            if (elem.requestFullscreen) elem.requestFullscreen();
            else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen();
            else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
            else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
        });

        $('#bulan_laporan').on('change', function () {
            const selDr = $(this).val();
            if (!selDr) {
                $('#tarik_laporan').prop('disabled',true);
            } else {
                $('#tarik_laporan').prop('disabled',false);
            };
        });

        $('#btn-show-rating').click(function() {
            const show = $(this).data('showrating');
            if (show == 0) {
                $('.emot-count').removeAttr('hidden');
                $(this).data('showrating', 1);
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                $(this).attr('title', 'Sembunyikan Nilai Rating').tooltip('_fixTitle');
                $(this).removeClass('btn-info').addClass('btn-danger');
            } else {
                $('.emot-count').attr('hidden', true);
                $(this).data('showrating', 0);
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                $(this).attr('title', 'Tampilkan Nilai Rating').tooltip('_fixTitle');
                $(this).removeClass('btn-danger').addClass('btn-info');
            }
        });

        $('.emot').click(function(){

            if(ratingLock) return; // jika masih terkunci, abaikan klik
            ratingLock = true;

            let rating = $(this).data('rating');
            let emot = $(this);

            // reset
            // $('.emot').removeClass('active bounce');

            // aktifkan yang dipilih
            emot.addClass('active bounce');

            $('.emot-rating').addClass('selected');

            $.ajax({
                url: "{{ route('api.rating.store') }}",
                type: "POST",
                data: {
                    rating: rating,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res){

                    let ratingtot = emot.data('rating');

                    let current = parseInt($('#count-'+ratingtot).text());

                    $('#count-'+ratingtot).text(current + 1);

                    iziToast.success({
                        title: 'Yeayy Berhasil!',
                        message: 'Terima kasih atas penilaiannya 😊.',
                        position: 'topRight'
                    });

                    // Swal.fire({
                    //     title: `Yeayy Berhasil!`,
                    //     text: 'Terima kasih atas penilaiannya 😊',
                    //     icon: `success`,
                    //     showConfirmButton: true,
                    //     showCancelButton: false,
                    //     allowOutsideClick: false,
                    //     allowEscapeKey: false,
                    //     timer: 5000,
                    //     timerProgressBar: true,
                    //     backdrop: `rgba(26,27,41,0.8)`,
                    // });

                    // disable semua emot setelah berhasil
                    $('.emot').css('pointer-events','none');
                    // $('.emot-rating').addClass('locked');

                },
                error: function(err){
                    iziToast.error({
                        title: 'Ahh Maaf!',
                        message: 'Terjadi kesalahan saat mengirim penilaian.',
                        position: 'topRight'
                    });
                    // Swal.fire({
                    //     title: `Ahh Maaf!`,
                    //     text: 'Terjadi kesalahan saat mengirim penilaian',
                    //     icon: `error`,
                    //     showConfirmButton: false,
                    //     showCancelButton: false,
                    //     allowOutsideClick: false,
                    //     allowEscapeKey: false,
                    //     timer: 5000,
                    //     timerProgressBar: true,
                    //     backdrop: `rgba(26,27,41,0.8)`,
                    // });
                },
                complete: function() {
                    setTimeout(() => {
                        emot.removeClass('active bounce');
                        $('.emot-rating').removeClass('selected');
                        ratingLock = false; // buka lock setelah 2 detik
                        $('.emot').css('pointer-events','auto');
                    }, 2000);
                }

            });

        });

    });

    function laporan(bulan) {
        let url = "{{ route('display.rating.laporan', ':bulan') }}";
        url = url.replace(':bulan', bulan);
        window.open(url, '_blank');
    }
</script>

@endsection

