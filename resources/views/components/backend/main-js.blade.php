{{-- ================= CORE (NO DEFER) ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/core.js') }}"></script>


{{-- ================= PLUGINS ================= --}}
<script src="{{ asset('backend/assets/src/plugins/jquery-steps/jquery.steps.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>


{{-- ================= DATATABLE CORE ================= --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>


{{-- ================= DATATABLE BUTTONS ================= --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>


{{-- ================= EXPORT DEPENDENCIES ================= --}}

{{-- Required for Excel (.xlsx) --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/jszip.min.js') }}"></script>

{{-- Required for PDF --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/vfs_fonts.js') }}"></script>


{{-- ================= EXPORT BUTTONS ================= --}}
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/assets/src/plugins/datatables/js/buttons.print.min.js') }}"></script>


{{-- ================= TEMPLATE ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/script.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/scripts/process.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/scripts/layout-settings.js') }}"></script>


{{-- ================= DATATABLE INITIALIZATION ================= --}}
<script src="{{ asset('backend/assets/vendors/scripts/datatable-setting.js') }}"></script>
<script src="{{ asset('backend/assets/vendors/scripts/steps-setting.js') }}"></script>


{{-- ================= TOASTR GLOBAL NOTIFICATIONS ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

    // Ensure Toastr library is loaded.
    if (typeof toastr === 'undefined') {
        console.error('Toastr library is not loaded.');
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | TOASTR CONFIGURATION
    |--------------------------------------------------------------------------
    */

    toastr.options = {
        positionClass: "toast-top-right",
        closeButton: true,
        progressBar: true,
        preventDuplicates: true,
        newestOnTop: true,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "6000",
        extendedTimeOut: "1000",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        escapeHtml: true
    };


    /*
    |--------------------------------------------------------------------------
    | SUCCESS MESSAGE
    |--------------------------------------------------------------------------
    */

    @if (Session::has('message'))

        toastr.success(
            @json(session('message')),
            'Success'
        );

    @endif


    /*
    |--------------------------------------------------------------------------
    | SESSION ERROR MESSAGE
    |--------------------------------------------------------------------------
    */

    @if (Session::has('error'))

        toastr.error(
            @json(session('error')),
            'Unable to Complete Request'
        );

    /*
    |--------------------------------------------------------------------------
    | VALIDATION ERROR MESSAGE
    |--------------------------------------------------------------------------
    |
    | Handles Laravel ValidationException errors.
    | Only the first validation message is displayed.
    | Avoids showing the same error twice when session('error')
    | is already available.
    |
    */

    @elseif ($errors->any())

        toastr.error(
            @json($errors->first()),
            'Please Check Your Details'
        );

    @endif


    /*
    |--------------------------------------------------------------------------
    | INFORMATION MESSAGE
    |--------------------------------------------------------------------------
    */

    @if (Session::has('info'))

        toastr.info(
            @json(session('info')),
            'Information'
        );

    @endif


    /*
    |--------------------------------------------------------------------------
    | WARNING MESSAGE
    |--------------------------------------------------------------------------
    */

    @if (Session::has('warning'))

        toastr.warning(
            @json(session('warning')),
            'Warning'
        );

    @endif

});
</script>
