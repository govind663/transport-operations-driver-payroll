{{-- ================= CORE (NO DEFER) ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/core.js') }}"></script>

{{-- ================= PLUGINS ================= --}}
<script src="{{ asset('backend/assets/src/plugins/jquery-steps/jquery.steps.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>

{{-- ================= DATATABLE ================= --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>

{{-- ================= DATATABLE BUTTONS ================= --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/vfs_fonts.js') }}"></script>

{{-- ================= TEMPLATE ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/script.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/scripts/process.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/scripts/layout-settings.js') }}"></script>

{{-- ================= INIT (LAST) ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/datatable-setting.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/scripts/steps-setting.js') }}"></script>

{{-- ================= TOASTR CLEAN ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Common Toastr Config (single place)
    toastr.options = {
        positionClass: "toast-top-right",
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "4000",
        extendedTimeOut: "800",
        showMethod: "fadeIn",
        hideMethod: "fadeOut"
    };

    // Laravel Flash Messages
    @if(Session::has('message'))
        toastr.success("{{ session('message') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(Session::has('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if(Session::has('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif

});
</script>