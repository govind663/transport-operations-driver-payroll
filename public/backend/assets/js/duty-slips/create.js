(function ($) {
    'use strict';

    $(document).ready(function () {

        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECK
        |--------------------------------------------------------------------------
        */

        if (typeof $ === 'undefined') {
            return;
        }

        const $form = $('#duty-slip-form');

        if (!$form.length) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | HELPERS
        |--------------------------------------------------------------------------
        */

        function numberValue(value) {
            const number = parseFloat(value);

            if (Number.isNaN(number) || number < 0) {
                return 0;
            }

            return number;
        }

        function formatAmount(value) {
            return numberValue(value).toFixed(2);
        }

        function escapeHtml(value) {
            return $('<div>')
                .text(value ?? '')
                .html();
        }

        /*
        |--------------------------------------------------------------------------
        | SELECT2
        |--------------------------------------------------------------------------
        */

        function initializeSelect2(scope) {
            if (!$.fn.select2) {
                return;
            }

            const $scope = scope
                ? $(scope)
                : $(document);

            $scope
                .find('.custom-select2')
                .addBack('.custom-select2')
                .each(function () {

                    const $select = $(this);

                    if (
                        !$select.hasClass(
                            'select2-hidden-accessible'
                        )
                    ) {
                        $select.select2({
                            width: '100%'
                        });
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | FILE PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewDutySlipFile(inputId, previewId) {

            const input =
                document.getElementById(inputId);

            const preview =
                document.getElementById(previewId);

            if (!input || !preview) {
                return;
            }

            preview.innerHTML = '';

            if (
                !input.files ||
                !input.files[0]
            ) {
                return;
            }

            const file = input.files[0];

            const extension =
                file.name
                    .split('.')
                    .pop()
                    .toLowerCase();

            const allowedExtensions = [
                'pdf',
                'jpg',
                'jpeg',
                'png'
            ];

            if (
                !allowedExtensions.includes(extension)
            ) {
                alert(
                    'Please select a valid PDF, JPG, JPEG, or PNG file.'
                );

                input.value = '';

                return;
            }

            const maxSize =
                5 * 1024 * 1024;

            if (file.size > maxSize) {

                alert(
                    'File size must not exceed 5 MB.'
                );

                input.value = '';

                return;
            }

            const fileSize =
                (
                    file.size /
                    1024 /
                    1024
                ).toFixed(2);

            const documentLabel =
                inputId === 'duty_slip_front_file'
                    ? 'Duty Slip Front'
                    : 'Duty Slip Back';

            const safeFileName =
                escapeHtml(file.name);

            /*
            |--------------------------------------------------------------------------
            | PDF
            |--------------------------------------------------------------------------
            */

            if (extension === 'pdf') {

                const fileUrl =
                    URL.createObjectURL(file);

                preview.innerHTML = `
                    <div
                        class="alert alert-light border d-flex align-items-center"
                        style="
                            border-radius:10px;
                            padding:12px 15px;
                            max-width:450px;
                        "
                    >

                        <div
                            class="mr-3"
                            style="
                                min-width:45px;
                                text-align:center;
                            "
                        >
                            <i
                                class="fa fa-file-pdf-o text-danger"
                                style="font-size:36px;"
                            ></i>
                        </div>

                        <div>

                            <strong
                                class="d-block"
                                style="word-break:break-word;"
                            >
                                ${safeFileName}
                            </strong>

                            <small class="text-muted">
                                ${escapeHtml(documentLabel)}
                                &nbsp;•&nbsp;
                                PDF
                                &nbsp;•&nbsp;
                                ${fileSize} MB
                            </small>

                            <div class="mt-2">

                                <a
                                    href="${fileUrl}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-primary"
                                >
                                    <i class="fa fa-eye"></i>
                                    Preview PDF
                                </a>

                            </div>

                        </div>

                    </div>
                `;

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | IMAGE
            |--------------------------------------------------------------------------
            */

            const reader =
                new FileReader();

            reader.onload =
                function (event) {

                    preview.innerHTML = `
                        <div>

                            <img
                                src="${event.target.result}"
                                alt="${escapeHtml(documentLabel)} Preview"
                                class="img-thumbnail"
                                style="
                                    width:220px;
                                    max-height:220px;
                                    object-fit:contain;
                                    border-radius:10px;
                                    border:2px solid #dee2e6;
                                    box-shadow:0 2px 10px rgba(0,0,0,.15);
                                    background:#fff;
                                "
                            >

                            <div class="mt-2">

                                <strong
                                    class="d-block"
                                    style="word-break:break-word;"
                                >
                                    ${safeFileName}
                                </strong>

                                <small class="text-muted">
                                    ${escapeHtml(documentLabel)}
                                    &nbsp;•&nbsp;
                                    Image
                                    &nbsp;•&nbsp;
                                    ${fileSize} MB
                                </small>

                            </div>

                        </div>
                    `;
                };

            reader.readAsDataURL(file);
        }

        /*
        |--------------------------------------------------------------------------
        | FILE EVENTS
        |--------------------------------------------------------------------------
        */

        $('#duty_slip_front_file').on(
            'change',
            function () {
                previewDutySlipFile(
                    'duty_slip_front_file',
                    'duty-slip-front-file-preview'
                );
            }
        );

        $('#duty_slip_back_file').on(
            'change',
            function () {
                previewDutySlipFile(
                    'duty_slip_back_file',
                    'duty-slip-back-file-preview'
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | PASSENGER MOBILE
        |--------------------------------------------------------------------------
        */

        $('#passenger_mobile').on(
            'input',
            function () {

                this.value =
                    String($(this).val())
                        .replace(/[^0-9]/g, '')
                        .slice(0, 10);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | NUMBER INPUT SANITIZATION
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#opening_km, #closing_km',
            function () {

                if (this.value === '') {
                    return;
                }

                const value =
                    parseFloat(this.value);

                if (
                    Number.isNaN(value) ||
                    value < 0
                ) {
                    this.value = '0';
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | TOTAL KM
        |--------------------------------------------------------------------------
        */

        function calculateTotalKm() {

            const openingRaw =
                $('#opening_km').val();

            const closingRaw =
                $('#closing_km').val();

            if (
                openingRaw === '' ||
                closingRaw === ''
            ) {

                $('#total_km').val('0.00');

                recalculatePerKmAllowances();

                calculateFinancialSummary();

                return;
            }

            const opening =
                parseFloat(openingRaw);

            const closing =
                parseFloat(closingRaw);

            if (
                Number.isNaN(opening) ||
                Number.isNaN(closing) ||
                closing < opening
            ) {

                $('#total_km').val('0.00');

                recalculatePerKmAllowances();

                calculateFinancialSummary();

                return;
            }

            const totalKm =
                closing - opening;

            $('#total_km').val(
                formatAmount(totalKm)
            );

            recalculatePerKmAllowances();

            calculateFinancialSummary();
        }

        /*
        |--------------------------------------------------------------------------
        | KM EVENTS
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#opening_km, #closing_km',
            function () {
                calculateTotalKm();
            }
        );

        $('#closing_km').on(
            'change',
            function () {

                const opening =
                    parseFloat(
                        $('#opening_km').val()
                    );

                const closing =
                    parseFloat(
                        $('#closing_km').val()
                    );

                if (
                    !Number.isNaN(opening) &&
                    !Number.isNaN(closing) &&
                    closing < opening
                ) {

                    alert(
                        'Closing KM cannot be less than Opening KM.'
                    );

                    $(this).val('');

                    calculateTotalKm();
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | ALLOWANCE RATE
        |--------------------------------------------------------------------------
        */

        function setAllowanceRate($row) {

            const $selectedOption =
                $row.find(
                    '.allowance-select option:selected'
                );

            const rate =
                numberValue(
                    $selectedOption.attr(
                        'data-rate'
                    )
                );

            const calculationType =
                $selectedOption.attr(
                    'data-calculation-type'
                );

            $row.find('.allowance-rate')
                .val(
                    formatAmount(rate)
                );

            if (
                calculationType === 'per_km'
            ) {

                const totalKm =
                    numberValue(
                        $('#total_km').val()
                    );

                $row.find('.allowance-quantity')
                    .val(
                        formatAmount(totalKm)
                    );

            } else {

                const currentQuantity =
                    $row.find(
                        '.allowance-quantity'
                    ).val();

                if (
                    currentQuantity === '' ||
                    numberValue(currentQuantity) <= 0
                ) {

                    $row.find(
                        '.allowance-quantity'
                    ).val('1');
                }
            }

            calculateAllowanceRow($row);
        }

        /*
        |--------------------------------------------------------------------------
        | ALLOWANCE CALCULATION
        |--------------------------------------------------------------------------
        */

        function calculateAllowanceRow($row) {

            const quantity =
                numberValue(
                    $row.find(
                        '.allowance-quantity'
                    ).val()
                );

            const rate =
                numberValue(
                    $row.find(
                        '.allowance-rate'
                    ).val()
                );

            const amount =
                quantity * rate;

            $row.find(
                '.allowance-amount'
            ).val(
                formatAmount(amount)
            );

            calculateFinancialSummary();
        }

        /*
        |--------------------------------------------------------------------------
        | RECALCULATE PER KM ALLOWANCES
        |--------------------------------------------------------------------------
        */

        function recalculatePerKmAllowances() {

            $('#allowance-wrapper .allowance-row')
                .each(function () {

                    const $row =
                        $(this);

                    const calculationType =
                        $row.find(
                            '.allowance-select option:selected'
                        ).attr(
                            'data-calculation-type'
                        );

                    if (
                        calculationType === 'per_km'
                    ) {

                        $row.find(
                            '.allowance-quantity'
                        ).val(
                            formatAmount(
                                numberValue(
                                    $('#total_km').val()
                                )
                            )
                        );

                        calculateAllowanceRow(
                            $row
                        );
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | ALLOWANCE SELECT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.allowance-select',
            function () {

                const $row =
                    $(this).closest(
                        '.allowance-row'
                    );

                if (!$(this).val()) {

                    $row.find(
                        '.allowance-rate'
                    ).val('0.00');

                    $row.find(
                        '.allowance-amount'
                    ).val('0.00');

                    calculateFinancialSummary();

                    return;
                }

                setAllowanceRate($row);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | ALLOWANCE QUANTITY
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.allowance-quantity',
            function () {

                calculateAllowanceRow(
                    $(this).closest(
                        '.allowance-row'
                    )
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | ADD ALLOWANCE
        |--------------------------------------------------------------------------
        */

        $('#add-allowance').on(
            'click',
            function () {

                const $wrapper =
                    $('#allowance-wrapper');

                const template =
                    document.getElementById(
                        'allowance-row-template'
                    );

                if (!template) {
                    return;
                }

                const index =
                    getNextAllowanceIndex();

                const html =
                    template.innerHTML
                        .replace(
                            /__INDEX__/g,
                            index
                        );

                $wrapper.append(html);

                const $newRow =
                    $wrapper.find(
                        '.allowance-row:last'
                    );

                initializeSelect2($newRow);

                calculateAllowanceRow(
                    $newRow
                );

                updateAllowanceIndexes();

                calculateFinancialSummary();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | REMOVE ALLOWANCE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.remove-allowance',
            function () {

                const $row =
                    $(this).closest(
                        '.allowance-row'
                    );

                const $rows =
                    $('#allowance-wrapper .allowance-row');

                if (
                    $rows.length <= 1
                ) {

                    $row.find(
                        '.allowance-select'
                    )
                    .val('')
                    .trigger('change');

                    $row.find(
                        '.allowance-quantity'
                    )
                    .val('1');

                    $row.find(
                        '.allowance-rate'
                    )
                    .val('0.00');

                    $row.find(
                        '.allowance-amount'
                    )
                    .val('0.00');

                    $row.find(
                        'input[name*="[remarks]"]'
                    )
                    .val('');

                    calculateFinancialSummary();

                    return;
                }

                $row.remove();

                updateAllowanceIndexes();

                calculateFinancialSummary();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | ALLOWANCE INDEX
        |--------------------------------------------------------------------------
        */

        function getNextAllowanceIndex() {

            let highestIndex = -1;

            $('#allowance-wrapper .allowance-row')
                .each(function () {

                    const index =
                        parseInt(
                            $(this).attr(
                                'data-index'
                            ),
                            10
                        );

                    if (
                        !Number.isNaN(index) &&
                        index > highestIndex
                    ) {
                        highestIndex = index;
                    }
                });

            return highestIndex + 1;
        }

        function updateAllowanceIndexes() {

            $('#allowance-wrapper .allowance-row')
                .each(function (index) {

                    const $row =
                        $(this);

                    $row.attr(
                        'data-index',
                        index
                    );

                    $row.find('[name]')
                        .each(function () {

                            const name =
                                $(this).attr(
                                    'name'
                                );

                            if (!name) {
                                return;
                            }

                            $(this).attr(
                                'name',
                                name.replace(
                                    /driver_allowances\[\d+\]/,
                                    `driver_allowances[${index}]`
                                )
                            );
                        });
                });
        }

        /*
        |--------------------------------------------------------------------------
        | EXPENSE RATE
        |--------------------------------------------------------------------------
        */

        function setExpenseRate($row) {

            const rate =
                numberValue(
                    $row.find(
                        '.expense-select option:selected'
                    ).attr(
                        'data-rate'
                    )
                );

            $row.find(
                '.expense-rate'
            ).val(
                formatAmount(rate)
            );

            calculateExpenseRow($row);
        }

        /*
        |--------------------------------------------------------------------------
        | EXPENSE CALCULATION
        |--------------------------------------------------------------------------
        */

        function calculateExpenseRow($row) {

            const quantity =
                numberValue(
                    $row.find(
                        '.expense-quantity'
                    ).val()
                );

            const rate =
                numberValue(
                    $row.find(
                        '.expense-rate'
                    ).val()
                );

            const amount =
                quantity * rate;

            $row.find(
                '.expense-amount'
            ).val(
                formatAmount(amount)
            );

            calculateFinancialSummary();
        }

        /*
        |--------------------------------------------------------------------------
        | EXPENSE SELECT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.expense-select',
            function () {

                const $row =
                    $(this).closest(
                        '.expense-row'
                    );

                if (!$(this).val()) {

                    $row.find(
                        '.expense-rate'
                    ).val('0.00');

                    $row.find(
                        '.expense-amount'
                    ).val('0.00');

                    calculateFinancialSummary();

                    return;
                }

                setExpenseRate($row);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | EXPENSE QUANTITY
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.expense-quantity',
            function () {

                calculateExpenseRow(
                    $(this).closest(
                        '.expense-row'
                    )
                );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | ADD EXPENSE
        |--------------------------------------------------------------------------
        */

        $('#add-expense').on(
            'click',
            function () {

                const $wrapper =
                    $('#expense-wrapper');

                const template =
                    document.getElementById(
                        'expense-row-template'
                    );

                if (!template) {
                    return;
                }

                const index =
                    getNextExpenseIndex();

                const html =
                    template.innerHTML
                        .replace(
                            /__INDEX__/g,
                            index
                        );

                $wrapper.append(html);

                const $newRow =
                    $wrapper.find(
                        '.expense-row:last'
                    );

                initializeSelect2($newRow);

                calculateExpenseRow(
                    $newRow
                );

                updateExpenseIndexes();

                calculateFinancialSummary();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | REMOVE EXPENSE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'click',
            '.remove-expense',
            function () {

                const $row =
                    $(this).closest(
                        '.expense-row'
                    );

                const $rows =
                    $('#expense-wrapper .expense-row');

                if (
                    $rows.length <= 1
                ) {

                    $row.find(
                        '.expense-select'
                    )
                    .val('')
                    .trigger('change');

                    $row.find(
                        '.expense-quantity'
                    )
                    .val('1');

                    $row.find(
                        '.expense-rate'
                    )
                    .val('0.00');

                    $row.find(
                        '.expense-amount'
                    )
                    .val('0.00');

                    $row.find(
                        'input[name*="[remarks]"]'
                    )
                    .val('');

                    calculateFinancialSummary();

                    return;
                }

                $row.remove();

                updateExpenseIndexes();

                calculateFinancialSummary();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | EXPENSE INDEX
        |--------------------------------------------------------------------------
        */

        function getNextExpenseIndex() {

            let highestIndex = -1;

            $('#expense-wrapper .expense-row')
                .each(function () {

                    const index =
                        parseInt(
                            $(this).attr(
                                'data-index'
                            ),
                            10
                        );

                    if (
                        !Number.isNaN(index) &&
                        index > highestIndex
                    ) {
                        highestIndex = index;
                    }
                });

            return highestIndex + 1;
        }

        function updateExpenseIndexes() {

            $('#expense-wrapper .expense-row')
                .each(function (index) {

                    const $row =
                        $(this);

                    $row.attr(
                        'data-index',
                        index
                    );

                    $row.find('[name]')
                        .each(function () {

                            const name =
                                $(this).attr(
                                    'name'
                                );

                            if (!name) {
                                return;
                            }

                            $(this).attr(
                                'name',
                                name.replace(
                                    /driver_expenses\[\d+\]/,
                                    `driver_expenses[${index}]`
                                )
                            );
                        });
                });
        }

        /*
        |--------------------------------------------------------------------------
        | FINANCIAL SUMMARY
        |--------------------------------------------------------------------------
        */

        function calculateFinancialSummary() {

            let allowanceTotal = 0;
            let expenseTotal = 0;

            $('#allowance-wrapper .allowance-row')
                .each(function () {

                    allowanceTotal +=
                        numberValue(
                            $(this)
                                .find(
                                    '.allowance-amount'
                                )
                                .val()
                        );
                });

            $('#expense-wrapper .expense-row')
                .each(function () {

                    expenseTotal +=
                        numberValue(
                            $(this)
                                .find(
                                    '.expense-amount'
                                )
                                .val()
                        );
                });

            const grandTotal =
                allowanceTotal +
                expenseTotal;

            $('#total-allowance')
                .val(
                    formatAmount(
                        allowanceTotal
                    )
                );

            $('#total-expense')
                .val(
                    formatAmount(
                        expenseTotal
                    )
                );

            $('#grand-total')
                .val(
                    formatAmount(
                        grandTotal
                    )
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TEXT NORMALIZATION
        |--------------------------------------------------------------------------
        */

        $(
            '#pickup_location, ' +
            '#drop_location, ' +
            '#passenger_name, ' +
            '#remarks'
        ).on(
            'blur',
            function () {

                this.value =
                    String($(this).val())
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );

        $(document).on(
            'blur',
            'input[name*="[remarks]"]',
            function () {

                this.value =
                    String($(this).val())
                        .replace(
                            /\s+/g,
                            ' '
                        )
                        .trim();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | REMOVE EMPTY CHILD ROWS
        |--------------------------------------------------------------------------
        */

        function removeEmptyAllowanceRows() {

            $('#allowance-wrapper .allowance-row')
                .each(function () {

                    const $row =
                        $(this);

                    const allowanceId =
                        $row.find(
                            '.allowance-select'
                        ).val();

                    if (!allowanceId) {
                        $row.remove();
                    }
                });

            updateAllowanceIndexes();
        }

        function removeEmptyExpenseRows() {

            $('#expense-wrapper .expense-row')
                .each(function () {

                    const $row =
                        $(this);

                    const expenseId =
                        $row.find(
                            '.expense-select'
                        ).val();

                    if (!expenseId) {
                        $row.remove();
                    }
                });

            updateExpenseIndexes();
        }

        /*
        |--------------------------------------------------------------------------
        | DATETIME VALIDATION
        |--------------------------------------------------------------------------
        */

        function validateDateTime() {

            const startDate =
                $('#start_date').val() ||
                $('#duty_date').val();

            const endDate =
                $('#end_date').val() ||
                $('#duty_date').val();

            const startTime =
                $('#start_time').val();

            const endTime =
                $('#end_time').val();

            if (
                !startDate ||
                !endDate ||
                !startTime ||
                !endTime
            ) {
                return true;
            }

            const start =
                new Date(
                    `${startDate}T${startTime}`
                );

            const end =
                new Date(
                    `${endDate}T${endTime}`
                );

            if (
                Number.isNaN(start.getTime()) ||
                Number.isNaN(end.getTime())
            ) {
                return true;
            }

            if (end < start) {

                alert(
                    'End date and time cannot be before start date and time.'
                );

                $('#end_time').focus();

                return false;
            }

            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | FORM SUBMIT
        |--------------------------------------------------------------------------
        */

        $form.on(
            'submit',
            function (event) {

                /*
                |--------------------------------------------------------------------------
                | Browser Validation
                |--------------------------------------------------------------------------
                */

                if (
                    !this.checkValidity()
                ) {

                    event.preventDefault();

                    this.reportValidity();

                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | Date / Time Validation
                |--------------------------------------------------------------------------
                */

                if (
                    !validateDateTime()
                ) {

                    event.preventDefault();

                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | Validate KM
                |--------------------------------------------------------------------------
                */

                const opening =
                    parseFloat(
                        $('#opening_km').val()
                    );

                const closing =
                    parseFloat(
                        $('#closing_km').val()
                    );

                if (
                    !Number.isNaN(opening) &&
                    !Number.isNaN(closing) &&
                    closing < opening
                ) {

                    event.preventDefault();

                    alert(
                        'Closing KM cannot be less than Opening KM.'
                    );

                    $('#closing_km').focus();

                    return false;
                }

                /*
                |--------------------------------------------------------------------------
                | Remove Empty Child Rows
                |--------------------------------------------------------------------------
                */

                removeEmptyAllowanceRows();

                removeEmptyExpenseRows();

                /*
                |--------------------------------------------------------------------------
                | Final Re-index
                |--------------------------------------------------------------------------
                */

                updateAllowanceIndexes();

                updateExpenseIndexes();

                /*
                |--------------------------------------------------------------------------
                | Final KM Calculation
                |--------------------------------------------------------------------------
                */

                calculateTotalKm();

                /*
                |--------------------------------------------------------------------------
                | Final Allowance Calculation
                |--------------------------------------------------------------------------
                */

                $('#allowance-wrapper .allowance-row')
                    .each(function () {

                        const $row =
                            $(this);

                        if (
                            $row.find(
                                '.allowance-select'
                            ).val()
                        ) {

                            setAllowanceRate(
                                $row
                            );
                        }
                    });

                /*
                |--------------------------------------------------------------------------
                | Final Expense Calculation
                |--------------------------------------------------------------------------
                */

                $('#expense-wrapper .expense-row')
                    .each(function () {

                        const $row =
                            $(this);

                        if (
                            $row.find(
                                '.expense-select'
                            ).val()
                        ) {

                            setExpenseRate(
                                $row
                            );
                        }
                    });

                /*
                |--------------------------------------------------------------------------
                | Final Summary
                |--------------------------------------------------------------------------
                */

                calculateFinancialSummary();

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | No Duty Assignment -> Driver sync.
                | No Duty Assignment -> Vehicle sync.
                | No Driver/Vehicle auto-correction.
                |
                | User's manually selected values are submitted exactly
                | as selected.
                |
                */

                /*
                |--------------------------------------------------------------------------
                | Prevent Duplicate Submission
                |--------------------------------------------------------------------------
                */

                const $submitButton =
                    $('#save-duty-slip');

                if (
                    $submitButton.length
                ) {

                    $submitButton
                        .prop(
                            'disabled',
                            true
                        )
                        .html(
                            '<i class="fa fa-spinner fa-spin"></i> Saving Duty Slip...'
                        );
                }

                return true;
            }
        );

        /*
        |--------------------------------------------------------------------------
        | INITIALIZE SELECT2
        |--------------------------------------------------------------------------
        */

        initializeSelect2(document);

        /*
        |--------------------------------------------------------------------------
        | INITIAL ALLOWANCE CALCULATION
        |--------------------------------------------------------------------------
        */

        $('#allowance-wrapper .allowance-row')
            .each(function () {

                const $row =
                    $(this);

                if (
                    $row.find(
                        '.allowance-select'
                    ).val()
                ) {

                    setAllowanceRate($row);

                } else {

                    calculateAllowanceRow($row);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | INITIAL EXPENSE CALCULATION
        |--------------------------------------------------------------------------
        */

        $('#expense-wrapper .expense-row')
            .each(function () {

                const $row =
                    $(this);

                if (
                    $row.find(
                        '.expense-select'
                    ).val()
                ) {

                    setExpenseRate($row);

                } else {

                    calculateExpenseRow($row);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | INITIAL INDEXES
        |--------------------------------------------------------------------------
        */

        updateAllowanceIndexes();
        updateExpenseIndexes();

        /*
        |--------------------------------------------------------------------------
        | INITIAL TOTAL KM
        |--------------------------------------------------------------------------
        */

        calculateTotalKm();

        /*
        |--------------------------------------------------------------------------
        | INITIAL FINANCIAL SUMMARY
        |--------------------------------------------------------------------------
        */

        calculateFinancialSummary();

    });

})(jQuery);