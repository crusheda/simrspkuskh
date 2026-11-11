@php
    $NOMOR = $list['KUNJUNGAN'];
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h5>Upload File</h5></div>
            <div class="card-body">
                <form id="upload-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label" for="nama_tambahan">Nama File <a class="text-danger">*</a></label>
                        <input type="text" class="form-control" name="nama_tambahan" id="nama_tambahan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="file">Pilih File <a class="text-danger">*</a></label>
                        <input type="file" class="form-control" name="file" id="file" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5>Uploaded Files</h5></div>
            <div class="card-body" id="file-list">
                <p>Tidak ada file diunggah.</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mt-3" id="preview-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" id="preview-title">Preview Dokumen</h5>
                {{-- <button type="button" class="btn btn-sm btn-secondary" id="close-preview">Tutup Preview</button> --}}
            </div>
            <div class="card-body p-0">
                <iframe id="document-preview" title="Preview dokumen" srcdoc="<!doctype html><html lang='id'><meta charset='utf-8'><body style='font:16px Arial,sans-serif;color:#666;display:grid;place-items:center;height:95vh;margin:0;text-align:center'><p>Pilih dokumen pada daftar file untuk melihat preview.</p></body></html>" style="width:100%; height:70vh; border:0;" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const nomor = @json($NOMOR);
    const fileList = document.getElementById('file-list');
    const previewCard = document.getElementById('preview-card');
    const previewFrame = document.getElementById('document-preview');
    const previewTitle = document.getElementById('preview-title');

    // filename dari Laravel berisi path relatif, misalnya files/upload/dokumen.pdf.
    function fileUrl(filename) {
        return '/storage/' + String(filename).split('/').map(encodeURIComponent).join('/');
    }

    function showPreview(file) {
        previewTitle.textContent = 'Preview: ' + (file.nama_tambahan || file.title || 'Dokumen');
        previewFrame.src = fileUrl(file.filename);
        // previewCard.hidden = false;
    }

    function renderFiles(files) {
        fileList.replaceChildren();
        if (!files.length) {
            const empty = document.createElement('p');
            empty.textContent = 'Tidak ada file diunggah.';
            fileList.appendChild(empty);
            return;
        }

        const list = document.createElement('ul');
        list.className = 'list-group';
        files.forEach(function (file) {
            const item = document.createElement('li');
            item.className = 'list-group-item d-flex justify-content-between align-items-center gap-2';

            const details = document.createElement('div');
            const title = document.createElement('div');
            title.textContent = file.title || 'Dokumen';
            const name = document.createElement('small');
            name.innerHTML = '<i></i>';
            name.firstElementChild.textContent = 'Nama Tambahan: ' + (file.nama_tambahan || '-');
            details.append(title, document.createElement('br'), name);

            const actions = document.createElement('div');
            actions.className = 'd-flex gap-2 flex-shrink-0';
            const previewButton = document.createElement('button');
            previewButton.type = 'button';
            previewButton.className = 'btn btn-info btn-sm preview-file';
            previewButton.textContent = 'Preview';
            previewButton.dataset.id = file.id;
            previewButton.dataset.filename = file.filename || '';
            previewButton.dataset.title = file.title || '';
            previewButton.dataset.namaTambahan = file.nama_tambahan || '';

            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.className = 'btn btn-danger btn-sm delete-file';
            deleteButton.textContent = 'Hapus';
            deleteButton.dataset.id = file.id;
            actions.append(previewButton, deleteButton);
            item.append(details, actions);
            list.appendChild(item);
        });
        fileList.appendChild(list);
    }

    function loadFileList() {
        return fetch(`/api/emr/file-upload/${encodeURIComponent(nomor)}/list`)
            .then(function (response) {
                if (!response.ok) throw new Error('Daftar file gagal dimuat.');
                return response.json();
            })
            .then(renderFiles)
            .catch(function (error) { alert(error.message); });
    }

    document.getElementById('upload-form').addEventListener('submit', function (event) {
        event.preventDefault();
        fetch(`/api/emr/file-upload/${encodeURIComponent(nomor)}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': @json(csrf_token()) },
            body: new FormData(this)
        })
        .then(function (response) {
            if (!response.ok) throw new Error('Upload gagal. Periksa ukuran dan format file.');
            return response.json();
        })
        .then(function (data) {
            if (!data.success) throw new Error('Upload gagal.');
            document.getElementById('upload-form').reset();
            return loadFileList();
        })
        .catch(function (error) { alert(error.message); });
    });

    fileList.addEventListener('click', function (event) {
        const previewButton = event.target.closest('.preview-file');
        if (previewButton) {
            showPreview({
                filename: previewButton.dataset.filename,
                title: previewButton.dataset.title,
                nama_tambahan: previewButton.dataset.namaTambahan
            });
            return;
        }

        const deleteButton = event.target.closest('.delete-file');
        if (!deleteButton || !confirm('Yakin ingin menghapus file ini?')) return;
        fetch(`/api/emr/file-upload/${encodeURIComponent(nomor)}/${encodeURIComponent(deleteButton.dataset.id)}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': @json(csrf_token()) }
        })
        .then(function (response) {
            if (!response.ok) throw new Error('Gagal menghapus file.');
            return response.json();
        })
        .then(function (data) {
            if (!data.success) throw new Error('Gagal menghapus file.');
            return loadFileList();
        })
        .catch(function (error) { alert(error.message); });
    });

    // document.getElementById('close-preview').addEventListener('click', function () {
    //     previewFrame.src = 'about:blank';
    //     previewCard.hidden = true;
    // });

    loadFileList();
});
</script>
