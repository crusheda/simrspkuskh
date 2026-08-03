<script src="{{ asset('v2/libs/global/global.min.js') }}"></script>
<script src="{{ asset('v2/libs/sortable/Sortable.min.js') }}"></script>
<script src="{{ asset('v2/libs/chartjs/chart.js') }}"></script>
<script src="{{ asset('v2/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('v2/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('v2/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('v2/js/dashboard/dashboard.js') }}"></script>
{{-- <script src="{{ asset('v2/js/plugins/todolist.js') }}"></script> --}}
<script src="{{ asset('v2/libs/tagify/tagify.js') }}"></script>
<script src="{{ asset('v2/js/plugins/tagify.js') }}"></script>
<script src="{{ asset('v2/js/appSettings.js') }}"></script>
<script src="{{ asset('v2/js/main.js') }}"></script>
<script src="{{ asset('js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('js/plugins/ckeditor/classic/ckeditor.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script src="{{ asset('js/plugins/simple-datatables.js') }}"></script>
<script src="{{ asset('js/iziToast.js') }}"></script>

{{-- DAYJS --}}
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/localizedFormat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/id.js"></script>
<script>
    dayjs.extend(dayjs_plugin_relativeTime);
    dayjs.extend(dayjs_plugin_localizedFormat);
    dayjs.locale('id');
</script>

{{-- AUTOCOMPLETE --}}
<script src="{{ asset('v2/libs/@tarekraafat/autocomplete.js/autoComplete.min.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.9/dist/autoComplete.min.js"></script> --}}
