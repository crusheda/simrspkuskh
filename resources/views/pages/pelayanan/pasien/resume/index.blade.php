@extends('layouts.index2')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<style>
    canvas {
    width: 200px;
    height: 200px;
    touch-action: none; /* penting untuk mencegah scroll saat tanda tangan */
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pelayanan</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pelayanan.pasien') }}">Kunjungan Pasien</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Detail Kunjungan</a></li>
                        <li class="breadcrumb-item" aria-current="page">Resume Pasien</li>
                    </ul>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Resume Pasien <b class="text-primary">A/N {{ $list['resume']->NAMAPASIEN }}</b></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-12">
            <div class="card user-card">
                <div class="card-body">
                    <button class="btn btn-primary" id="btn-refresh" onclick="showRESUME('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-sync me-2"></i> Refresh Resume</button>
                    <div class="d-flex align-items-center mt-3 mb-3" id="refresh-iframe">
                        {{-- <div class="flex-shrink-1 m-r-5 m-l-5">
                            <img src="{{ asset('/images/pku/logo.png') }}" alt="user-image"
                                class="avtar rounded-circle wid-65 hei-65" style="width: 65px; height: 65px">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h1 class="mb-1">RS PKU MUHAMMADIYAH SUKOHARJO</h1>
                            <h5 class="text-muted mb-1">JL. Mayor Sunaryo No. 37 Sukoharjo 57512</h5>
                        </div> --}}



                        {{-- <table class="table table-borderless border-dark border" style="width: 100%">
                            <thead>
                                <tr>
                                    <td rowspan="2" class="p-t-10 p-b-10" style="text-align: center; width:15%;">
                                        <img src="{{ asset('/images/pku/logo.png') }}" alt="user-image" class="avtar rounded-circle wid-65 hei-65" style="width: 120px; height: 120px">
                                    </td>
                                    <td><h1 class="mt-0 f-48">RS PKU MUHAMMADIYAH SUKOHARJO</h1></td>
                                </tr>
                                <tr>
                                    <td><h5 class="text-muted mt-0 f-36">JL. Mayor Sunaryo No. 37 Sukoharjo 57512</h5></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-dark border bg-gray-900">
                                    <td class="p-" style="text-align: center" colspan="2">
                                        <span class="f-32 text-gray-100">RESUME MEDIS</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table> --}}


                        {{-- <iframe src="{{ route('pelayanan.pasien.resume.print', ['KUNJUNGAN' => $list['KUNJUNGAN']]) }}" width="100%" height="800px" style="border: none;"></iframe> --}}
                    </div>
                    <div>
                        <input type="hidden" name="nama" id="nama" value="{{ $list['KUNJUNGAN'] }}">
                        <canvas id="signature-pad" width="400" height="200" style="border:1px solid #ccc;"></canvas>
                        <button id="clear" class="btn btn-danger btn-sm">Clear</button>
                        <button onclick="simpanTtd()" class="btn btn-primary btn-sm">Simpan</button>
                        <input type="hidden" name="signature" id="signature-input">



                        <div>
                            <br>
                            <h3 id="result"></h3>
                        </div>
                    {{-- </div>
                    <div class="saprator my-3">
                        <span>..</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">IT</h6>
                            <p class="text-muted text-sm mb-0">DM on <a href="#" class="text-primary">@itpkuskh</a></p>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-primary btn-sm" onclick="cetakResumeMedis">Simpan</button>
                            <button class="btn btn-outline-secondary btn-sm ms-1">Cetak</button>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <script>
        $(document).ready(function() {
            showRESUME("{{ $list['KUNJUNGAN'] }}");
            // showLoader();
            // refresh();
            // FLATPICKR DATE
        const today = new Date(); // Hari ini
        const fiveYearsAgo = new Date();
        fiveYearsAgo.setFullYear(today.getFullYear() - 5); // 5 tahun ke belakang
        $("#filter_tgl").flatpickr(
            {
                // enableTime: true,
                // dateFormat: "Y-m-d H:i",
                mode: 'range',
                minDate: fiveYearsAgo, // Mulai dari 5 tahun yang lalu
                maxDate: today,        // Sampai hari ini
                dateFormat: 'Y-m-d',
                defaultDate: [today,today]
            }
        );

        // SELECT CHOICES
        elm = $('#filter_dpjp')[0];
        choices = new Choices(elm);
        });

        const canvas = document.getElementById('signature-pad');
            const signaturePad = new SignaturePad(canvas);

            // Simpan base64 ke input saat submit
            document.querySelector('form').addEventListener('submit', function () {
                if (!signaturePad.isEmpty()) {
                    const dataURL = signaturePad.toDataURL('image/png');
                    document.getElementById('signature-input').value = dataURL;
                }
            });

            // Tombol hapus
            document.getElementById('clear').addEventListener('click', function () {
                signaturePad.clear();
            });
            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear(); // Reset tanda tangan
        }
        function refreshResume() {
            // <iframe src="{{ route('pelayanan.pasien.resume.print', ['KUNJUNGAN' => $list['KUNJUNGAN']]) }}" width="100%" height="800px" style="border: none;"></iframe>
            // $("#refresh-iframe").empty().append(`
            //     <iframe src="/pelayanan/pasien/resume/{{ $list['KUNJUNGAN'] }}/print" width="100%" height="800px" style="border: none;"></iframe>
            // `);
        }

        // Submit via AJAX
        function simpanTtd() {
            // const nama = document.getElementById('nama').value;
            const nama = document.getElementById('nama').value.trim();
            const signature = signaturePad.toDataURL('image/png');

            if (!nama || signaturePad.isEmpty()) {
                alert("Nama dan tanda tangan wajib diisi.");
                return;
            }

            // fetch("{{ route('api.pasien.resume.ttd') }}", {
            //     method: "POST",
            //     headers: {
            //         "Content-Type": "application/json",
            //         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //     },
            //     body: JSON.stringify({ nama: nama, signature: signature })
            // })
            // .then(res => res.json())
            // .then(data => {
            //     if (data.success) {
            //         document.getElementById('result').innerHTML = `<p><strong>Berhasil!</strong> ID Pasien: ${data.id}</p>`;
            //     } else {
            //         alert("Gagal menyimpan data");
            //     }
            // })
            // .catch(err => {
            //     console.error(err);
            //     alert("Error saat mengirim data.");
            // });
            $.ajax({
                url: "{{ route('api.pasien.resume.ttd') }}", // Ganti dengan URL rute yang sesuai
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({ nama: nama, signature: signature }),
                contentType: 'application/json',
                success: function(data) {
                    if (data.success) {
                        // $('#result').html(`<p><strong>Berhasil!</strong> ID Pasien: ${data.id}</p>`);
                        Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data berhasil disimpan.',
                        confirmButtonText: 'Oke'
                        });
                        // refreshResume();
                        showRESUME('{{ $list['KUNJUNGAN'] }}')
                    } else {
                        alert("Gagal menyimpan data");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // alert("Error saat mengirim data.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Dokumen telah ditandatangani.',
                    });
                }
            });

        }

        function showRESUME(kunjungan) {
            $('#btn-refresh').prop('disabled',true).find('i').addClass('fa-spin');
            fetch("/api/pelayanan/pasien/rj/resume/" + kunjungan)
            .then(response => {
                if (!response.ok) {
                    throw new Error('File tidak ditemukan atau gagal diambil.');
                }
                return response.blob();
            })
            .then(blob => {
                // Buat object URL dari blob
                const fileURL = URL.createObjectURL(blob);

                // Tampilkan ke iframe dalam modal
                $("#refresh-iframe").empty().html(`
                    <iframe src="${fileURL}" width="100%" height="800px" style="border: none;"></iframe>
                `);
                $('#btn-refresh').prop('disabled',false).find('i').removeClass('fa-spin');
            })
            .catch(error => {
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Data SEP tidak ditemukan atau belum digenerate.',
                    position: 'topRight'
                });
                console.error(error);
            });
        }
    </script>
@endsection
