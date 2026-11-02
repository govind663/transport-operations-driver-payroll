$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | CHECK DATATABLE AVAILABILITY
    |--------------------------------------------------------------------------
    */

    if (!$.fn.DataTable) {
        console.error('DataTables library is not loaded.');
        return;
    }

    if (!$.fn.dataTable.Buttons) {
        console.error('DataTables Buttons library is not loaded.');
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | SELECT DATATABLES
    |--------------------------------------------------------------------------
    */

    var $tables = $('.data-table-export1');

    if (!$tables.length) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK EXPORT DEPENDENCIES
    |--------------------------------------------------------------------------
    */

    var availableButtons = $.fn.dataTable.ext.buttons || {};

    var hasExcel =
        typeof JSZip !== 'undefined' &&
        typeof availableButtons.excelHtml5 !== 'undefined';

    var hasPDF =
        typeof pdfMake !== 'undefined' &&
        typeof availableButtons.pdfHtml5 !== 'undefined';


    if (!hasExcel) {
        console.error(
            'Excel export unavailable. Check JSZip and buttons.html5.min.js loading order.'
        );
    }

    if (!hasPDF) {
        console.warn(
            'PDF export unavailable. Check pdfMake and buttons.html5.min.js.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE EACH DATATABLE
    |--------------------------------------------------------------------------
    */

    $tables.each(function () {

        var tableElement = this;
        var $table = $(tableElement);

        /*
        |--------------------------------------------------------------------------
        | PREVENT DATATABLE REINITIALISATION
        |--------------------------------------------------------------------------
        */

        if ($.fn.DataTable.isDataTable(tableElement)) {
            $table.DataTable().destroy();
        }


        /*
        |--------------------------------------------------------------------------
        | EXPORT TITLE
        |--------------------------------------------------------------------------
        */

        var exportTitle = $table.data('title') || 'Export Data';

        var exportFileName = String(exportTitle)
            .replace(/[\\/:*?"<>|]+/g, '_')
            .trim();


        /*
        |--------------------------------------------------------------------------
        | EXPORT BUTTONS
        |--------------------------------------------------------------------------
        */

        var exportButtons = [];


        /*
        |--------------------------------------------------------------------------
        | COPY BUTTON
        |--------------------------------------------------------------------------
        */

        if (availableButtons.copyHtml5) {

            exportButtons.push({

                extend: 'copyHtml5',

                text: 'Copy',

                className: 'btn btn-primary btn-sm',

                exportOptions: {
                    columns: ':not(.no-export)',
                    orthogonal: 'export'
                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | EXCEL BUTTON (.XLSX)
        |--------------------------------------------------------------------------
        */

        if (hasExcel) {

            exportButtons.push({

                extend: 'excelHtml5',

                text: 'Excel',

                className: 'btn btn-success btn-sm',

                title: exportTitle,

                filename: exportFileName,

                exportOptions: {
                    columns: ':not(.no-export)',
                    orthogonal: 'export'
                },

                autoFilter: true,

                sheetName: 'Data'

            });

        }


        /*
        |--------------------------------------------------------------------------
        | PDF BUTTON
        |--------------------------------------------------------------------------
        */

        if (hasPDF) {

            exportButtons.push({

                extend: 'pdfHtml5',

                text: 'PDF',

                className: 'btn btn-danger btn-sm',

                orientation: 'landscape',

                pageSize: 'A4',

                title: exportTitle,

                filename: exportFileName,

                exportOptions: {
                    columns: ':not(.no-export)',
                    orthogonal: 'export'
                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | PRINT BUTTON
        |--------------------------------------------------------------------------
        */

        if (availableButtons.print) {

            exportButtons.push({

                extend: 'print',

                text: 'Print',

                className: 'btn btn-dark btn-sm',

                exportOptions: {
                    columns: ':not(.no-export)',
                    orthogonal: 'export'
                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | DATATABLE INITIALIZATION
        |--------------------------------------------------------------------------
        */

        $table.DataTable({

            scrollCollapse: true,

            autoWidth: false,

            responsive: true,

            columnDefs: [

                {
                    targets: 'datatable-nosort',
                    orderable: false
                }

            ],

            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All']
            ],

            pageLength: 10,


            /*
            |--------------------------------------------------------------------------
            | LANGUAGE + PAGINATION
            |--------------------------------------------------------------------------
            */

            language: {

                info: 'Showing _START_ to _END_ of _TOTAL_ entries',

                lengthMenu: 'Show _MENU_ entries',

                search: '',

                searchPlaceholder: '🔍 Search here...',

                paginate: {
                    previous: 'Previous',
                    next: 'Next'
                }

            },


            /*
            |--------------------------------------------------------------------------
            | DATATABLE LAYOUT
            |--------------------------------------------------------------------------
            */

            dom:

                "<'row mb-3 align-items-center'<'col-md-6 d-flex flex-wrap gap-2'B><'col-md-6 d-flex justify-content-end'f>>" +

                "<'row'<'col-12'tr>>" +

                "<'row mt-3 align-items-center'<'col-md-6'i><'col-md-6 d-flex justify-content-end'p>>",


            /*
            |--------------------------------------------------------------------------
            | EXPORT BUTTON CONFIGURATION
            |--------------------------------------------------------------------------
            */

            buttons: exportButtons

        });

    });

});
