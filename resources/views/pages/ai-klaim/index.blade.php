@extends('layouts.index')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>AI Klaim RS PKU Muhammadiyah Sukoharjo</strong>
        </div>

        <div class="card-body">

            <div class="alert alert-warning">
                ⚠️ <b>PERHATIAN:</b><br>
                Dilarang memasukkan nama pasien, nomor RM, NIK, atau data pribadi pasien.
                Jawaban AI bersifat <b>pendukung</b>, bukan keputusan final klaim.
            </div>

            <div class="form-group">
                <label>Pertanyaan Klaim</label>
                <textarea id="pertanyaan"
                          class="form-control"
                          rows="4"
                          placeholder="Contoh: Berkas apa saja yang wajib untuk klaim rawat inap INA-CBG?"></textarea>
            </div>

            <button class="btn btn-success mt-2" onclick="kirimPertanyaan()">
                Tanya AI
            </button>

            <hr>

            <h5>Jawaban AI</h5>
            <div id="jawaban"
                 style="white-space: pre-line; background:#f8f9fa; padding:15px; border-radius:5px;">
                —
            </div>

        </div>
    </div>

</div>

<script>
function kirimPertanyaan() {
    const pertanyaan = document.getElementById('pertanyaan').value;

    if (!pertanyaan.trim()) {
        alert('Pertanyaan tidak boleh kosong');
        return;
    }

    document.getElementById('jawaban').innerHTML = '⏳ Memproses...';

    fetch("{{ url('ai-klaim/tanya') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ pertanyaan })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('jawaban').innerText =
            data.jawaban ?? 'AI tidak memberikan jawaban';
    })
    .catch(() => {
        document.getElementById('jawaban').innerText =
            'Terjadi kesalahan saat menghubungi AI';
    });
}
</script>
@endsection