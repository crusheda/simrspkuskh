{{-- BOOTSTRAP --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" data-navigate-once></script>

{{-- SIGNATURE PAD --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js" data-navigate-once></script>

{{-- NOTIFIKASI KLAIM --}}
<script src="{{ asset('assets/v2/js/notifikasi/klaim.js') }}" data-navigate-once></script>

{{-- TAGIFY --}}
<script src="{{ asset('assets/v2/libs/tagify/tagify.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/js/plugins/tagify.js') }}" data-navigate-once></script>

{{-- FLATPICKR --}}
<script src="{{ asset('assets/v2/libs/flatpickr/flatpickr.min.js') }}" data-navigate-once></script>

{{-- SIMPLE DATATABLES --}}
<script src="{{ asset('js/plugins/simple-datatables.js') }}" data-navigate-once></script>

{{-- JQUERY DATATABLES --}}
<script src="{{ asset('assets/v2/libs/datatable/js/jquery.dataTables.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/dataTables.bootstrap5.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/dataTables.buttons.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/buttons.html5.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/buttons.colVis.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/buttons.print.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/pdfmake.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/vfs_fonts.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/xlsx.full.min.js') }}" data-navigate-once></script>

<script src="{{ asset('assets/v2/libs/datatable/js/jszip.min.js') }}" data-navigate-once></script>

{{-- CHOICES --}}
<script src="{{ asset('js/plugins/choices.min.js') }}" data-navigate-once></script>

{{-- CKEDITOR --}}
<script src="{{ asset('js/plugins/ckeditor/classic/ckeditor.js') }}" data-navigate-once></script>

{{-- SWEETALERT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js" data-navigate-once></script>

{{-- SELECT2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" data-navigate-once></script>

{{-- MOMENT --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js" data-navigate-once>
</script>

{{-- IZITOAST --}}
<script src="{{ asset('js/iziToast.js') }}" data-navigate-once></script>

{{-- DAYJS --}}
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js" data-navigate-once></script>

<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js" data-navigate-once></script>

<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/localizedFormat.js" data-navigate-once></script>

<script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/id.js" data-navigate-once></script>

{{-- AUTOCOMPLETE --}}
<script src="{{ asset('assets/v2/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}" data-navigate-once></script>

{{-- FORM HELPER --}}
<script src="{{ asset('assets/v2/js/form-helper.js') }}" data-navigate-once></script>

{{-- SIRMED V3 --}}
<script src="{{ asset('js/v3/app.js') }}" data-navigate-once></script>
<script src="{{ asset('js/v3/components/datatables.js') }}" data-navigate-once></script>
<script src="{{ asset('js/v3/components/buttons.js') }}" data-navigate-once></script>
<script src="{{ asset('js/v3/pages/notifikasi-klaim.js') }}" data-navigate-once></script>

{{-- INISIALISASI DAYJS --}}
<script data-navigate-once>
    dayjs.extend(dayjs_plugin_relativeTime);
    dayjs.extend(dayjs_plugin_localizedFormat);
    dayjs.locale('id');
</script>

