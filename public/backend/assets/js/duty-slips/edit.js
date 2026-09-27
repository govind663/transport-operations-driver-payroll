(function ($) {
    'use strict';

    $(document).ready(function () {

        const $form = $('#duty-slip-form');

        if (!$form.length) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CONSTANTS
        |--------------------------------------------------------------------------
        */

        const allowedFileExtensions = [
            'pdf',
            'jpg',
            'jpeg',
            'png'
        ];

        const allowedFileMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png'
        ];

        const maxFileSize = 5 * 1024 * 1024;


        /*
        |--------------------------------------------------------------------------
        | HELPERS
        |--------------------------------------------------------------------------
        */

        function numberValue(value) {

            const number = parseFloat(value);

            return Number.isFinite(number) && number >= 0
                ? number
                : 0;
        }


        function formatAmount(value) {
            return numberValue(value).toFixed(2);
        }


        function escapeHtml(value) {

            return $('<div>')
                .text(value ?? '')
                .html();
        }


        function getFileExtension(fileName) {

            return String(fileName || '')
                .split('.')
                .pop()
                .toLowerCase();
        }


        function validateSelectedFile(file) {

            if (!file) {
                return {
                    valid: true
                };
            }

            const extension =
                getFileExtension(file.name);

            if (
                !allowedFileExtensions.includes(
                    extension
                )
            ) {

                return {
                    valid: false,
                    message:
                        'Please select a valid PDF, JPG, JPEG, or PNG file.'
                };
            }

            if (
                file.type &&
                !allowedFileMimeTypes.includes(
                    file.type
                )
            ) {

                return {
                    valid: false,
                    message:
                        'Selected file type is not supported. Please select a PDF, JPG, JPEG, or PNG file.'
                };
            }

            if (
                file.size > maxFileSize
            ) {

                return {
                    valid: false,
                    message:
                        'File size must not exceed 5 MB.'
                };
            }

            return {
                valid: true
            };
        }


        function clearFileInputPreview(
            input,
            preview
        ) {

            if (preview) {
                preview.innerHTML = '';
            }

            if (input) {
                input.value = '';
            }
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
                            width: '100%',
                            placeholder: 'Select',
                            allowClear: true
                        });
                    }
                });
        }


        /*
        |--------------------------------------------------------------------------
        | DUTY SLIP FILE PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewDutySlipFile(
            inputId,
            previewId
        ) {

            const input =
                document.getElementById(inputId);

            const preview =
                document.getElementById(previewId);

            if (!input || !preview) {
                return;
            }

            if (
                !input.files ||
                !input.files[0]
            ) {
                return;
            }

            const file =
                input.files[0];

            const validation =
                validateSelectedFile(file);

            if (!validation.valid) {

                alert(
                    validation.message
                );

                clearFileInputPreview(
                    input,
                    preview
                );

                return;
            }

            const extension =
                getFileExtension(file.name);

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

            preview.innerHTML = '';

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

                            <small class="text-muted d-block">
                                ${escapeHtml(documentLabel)}
                                &nbsp;•&nbsp;
                                PDF
                                &nbsp;•&nbsp;
                                ${fileSize} MB
                            </small>

                            <a
                                href="${fileUrl}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-sm btn-primary mt-2"
                            >
                                <i class="fa fa-eye"></i>
                                Preview PDF
                            </a>

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
        | EXPENSE DOCUMENT PREVIEW
        |--------------------------------------------------------------------------
        */

        function previewExpenseDocument(input) {

            if (!input) {
                return;
            }

            const $input =
                $(input);

            const $row =
                $input.closest(
                    '.expense-row'
                );

            const preview =
                $row.find(
                    '.expense-document-preview'
                ).get(0);

            if (!preview) {
                return;
            }

            if (
                !input.files ||
                !input.files[0]
            ) {

                preview.innerHTML = '';

                return;
            }

            const file =
                input.files[0];

            const validation =
                validateSelectedFile(file);

            if (!validation.valid) {

                alert(
                    validation.message
                );

                clearFileInputPreview(
                    input,
                    preview
                );

                return;
            }

            const extension =
                getFileExtension(file.name);

            const fileSize =
                (
                    file.size /
                    1024 /
                    1024
                ).toFixed(2);

            const safeFileName =
                escapeHtml(file.name);

            preview.innerHTML = '';

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
                        class="alert alert-light border"
                        style="
                            border-radius:8px;
                            padding:10px 12px;
                            margin-bottom:0;
                        "
                    >

                        <div
                            class="d-flex align-items-start"
                        >

                            <div
                                class="mr-2"
                                style="
                                    min-width:38px;
                                    text-align:center;
                                "
                            >
                                <i
                                    class="fa fa-file-pdf-o text-danger"
                                    style="font-size:30px;"
                                ></i>
                            </div>

                            <div>

                                <strong
                                    class="d-block"
                                    style="word-break:break-word;"
                                >
                                    ${safeFileName}
                                </strong>

                                <small class="text-muted d-block">
                                    PDF
                                    &nbsp;•&nbsp;
                                    ${fileSize} MB
                                </small>

                                <a
                                    href="${fileUrl}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-sm btn-primary mt-2"
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
                                alt="Expense Document Preview"
                                class="img-thumbnail"
                                style="
                                    width:120px;
                                    height:90px;
                                    object-fit:contain;
                                    border-radius:8px;
                                    border:1px solid #dee2e6;
                                    background:#fff;
                                "
                            >

                            <div class="mt-1">

                                <strong
                                    class="d-block"
                                    style="
                                        font-size:12px;
                                        word-break:break-word;
                                    "
                                >
                                    ${safeFileName}
                                </strong>

                                <small class="text-muted">
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


        function validateExpenseDocuments() {

            let isValid = true;

            let firstInvalidInput = null;

            $('.expense-document-input')
                .each(function () {

                    if (
                        !this.files ||
                        !this.files[0]
                    ) {
                        return;
                    }

                    const validation =
                        validateSelectedFile(
                            this.files[0]
                        );

                    if (!validation.valid) {

                        isValid = false;

                        if (!firstInvalidInput) {
                            firstInvalidInput = this;
                        }

                        alert(
                            validation.message
                        );

                        clearFileInputPreview(
                            this,
                            $(this)
                                .closest('.expense-row')
                                .find(
                                    '.expense-document-preview'
                                )
                                .get(0)
                        );
                    }
                });

            if (
                firstInvalidInput
            ) {

                $(firstInvalidInput).focus();
            }

            return isValid;
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


        $(document).on(
            'change',
            '.expense-document-input',
            function () {

                previewExpenseDocument(this);
            }
        );


        /*
        |--------------------------------------------------------------------------
        | PASSENGER / TEXT-LIKE INPUTS
        |--------------------------------------------------------------------------
        */

        $('#remarks').on(
            'blur',
            function () {

                this.value =
                    String($(this).val())
                        .replace(/\s+/g, ' ')
                        .trim();
            }
        );


        $(document).on(
            'blur',
            'input[name*="[remarks]"]',
            function () {

                this.value =
                    String($(this).val())
                        .replace(/\s+/g, ' ')
                        .trim();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | SLIP NUMBER NORMALIZATION
        |--------------------------------------------------------------------------
        */

        $('#slip_no').on(
            'blur',
            function () {

                this.value =
                    String($(this).val())
                        .trim()
                        .toUpperCase()
                        .replace(/\s+/g, '');
            }
        );


        /*
        |--------------------------------------------------------------------------
        | NUMBER INPUT SANITIZATION
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '#opening_km, #closing_km, ' +
            '.allowance-quantity, ' +
            '.expense-quantity, ' +
            '.expense-rate',
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

                /*
                |--------------------------------------------------------------------------
                | Recalculate Expense
                |--------------------------------------------------------------------------
                */

                if (
                    $(this).hasClass(
                        'expense-quantity'
                    ) ||
                    $(this).hasClass(
                        'expense-rate'
                    )
                ) {

                    calculateExpenseRow(
                        $(this).closest(
                            '.expense-row'
                        )
                    );
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

                $('#total_km')
                    .val('0.00');

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
                Number.isNaN(closing)
            ) {

                $('#total_km')
                    .val('0.00');

                recalculatePerKmAllowances();

                calculateFinancialSummary();

                return;
            }

            if (
                closing < opening
            ) {

                $('#total_km')
                    .val('0.00');

                recalculatePerKmAllowances();

                calculateFinancialSummary();

                return;
            }

            const totalKm =
                closing - opening;

            $('#total_km')
                .val(
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
            calculateTotalKm
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
        | ALLOWANCE
        |--------------------------------------------------------------------------
        */

        function setAllowanceRate($row) {

            const $option =
                $row.find(
                    '.allowance-select option:selected'
                );

            const rate =
                numberValue(
                    $option.attr(
                        'data-rate'
                    )
                );

            const calculationType =
                $option.attr(
                    'data-calculation-type'
                );

            $row.find(
                '.allowance-rate'
            ).val(
                formatAmount(rate)
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

            } else {

                const quantity =
                    $row.find(
                        '.allowance-quantity'
                    ).val();

                if (
                    quantity === '' ||
                    numberValue(quantity) <= 0
                ) {

                    $row.find(
                        '.allowance-quantity'
                    ).val('1');
                }
            }

            calculateAllowanceRow($row);
        }


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
                        Number.isInteger(index) &&
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
                                $(this).attr('name');

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


        function updateAllowanceRemoveButtons() {

            const $rows =
                $('#allowance-wrapper .allowance-row');

            $rows
                .find('.remove-allowance')
                .prop(
                    'disabled',
                    $rows.length <= 1
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ADD ALLOWANCE
        |--------------------------------------------------------------------------
        */

        $('#add-allowance').on(
            'click',
            function () {

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
                    template.innerHTML.replace(
                        /__INDEX__/g,
                        index
                    );

                const $wrapper =
                    $('#allowance-wrapper');

                $wrapper.append(html);

                const $row =
                    $wrapper.find(
                        '.allowance-row:last'
                    );

                initializeSelect2($row);

                calculateAllowanceRow($row);

                updateAllowanceIndexes();

                updateAllowanceRemoveButtons();

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

                const $rows =
                    $('#allowance-wrapper .allowance-row');

                const $row =
                    $(this).closest(
                        '.allowance-row'
                    );

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
                    ).val('1');

                    $row.find(
                        '.allowance-rate'
                    ).val('0.00');

                    $row.find(
                        '.allowance-amount'
                    ).val('0.00');

                    $row.find(
                        'input[name*="[remarks]"]'
                    ).val('');

                    /*
                    |--------------------------------------------------------------------------
                    | Existing Child ID
                    |--------------------------------------------------------------------------
                    */

                    $row.find(
                        'input[name*="[id]"]'
                    ).remove();

                    updateAllowanceRemoveButtons();

                    calculateFinancialSummary();

                    return;
                }

                $row.remove();

                updateAllowanceIndexes();

                updateAllowanceRemoveButtons();

                calculateFinancialSummary();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | EXPENSE
        |--------------------------------------------------------------------------
        |
        | Expense Master only provides the Expense Type.
        | Actual Rate is entered manually by the user.
        |
        | Amount = Quantity × Rate
        |
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
        |
        | Selecting Expense Type does NOT change the Rate.
        | Existing/manual Rate remains untouched.
        |
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

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | No rate is fetched from Expense Master.
                |
                */

                calculateExpenseRow(
                    $row
                );
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
        | EXPENSE RATE
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.expense-rate',
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
        | EXPENSE DOCUMENT INPUT
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'change',
            '.expense-document-input',
            function () {

                previewExpenseDocument(this);
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
                        Number.isInteger(index) &&
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
                                $(this).attr('name');

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


        function updateExpenseRemoveButtons() {

            const $rows =
                $('#expense-wrapper .expense-row');

            $rows
                .find('.remove-expense')
                .prop(
                    'disabled',
                    $rows.length <= 1
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ADD EXPENSE
        |--------------------------------------------------------------------------
        */

        $('#add-expense').on(
            'click',
            function () {

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
                    template.innerHTML.replace(
                        /__INDEX__/g,
                        index
                    );

                const $wrapper =
                    $('#expense-wrapper');

                $wrapper.append(html);

                const $row =
                    $wrapper.find(
                        '.expense-row:last'
                    );

                initializeSelect2($row);

                calculateExpenseRow($row);

                updateExpenseIndexes();

                updateExpenseRemoveButtons();

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

                const $rows =
                    $('#expense-wrapper .expense-row');

                const $row =
                    $(this).closest(
                        '.expense-row'
                    );

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
                    ).val('1');

                    $row.find(
                        '.expense-rate'
                    ).val('0.00');

                    $row.find(
                        '.expense-amount'
                    ).val('0.00');

                    $row.find(
                        'input[name*="[remarks]"]'
                    ).val('');

                    /*
                    |--------------------------------------------------------------------------
                    | Existing Child ID
                    |--------------------------------------------------------------------------
                    |
                    | Remove the ID so backend receives no existing
                    | record for this row.
                    |
                    */

                    $row.find(
                        'input[name*="[id]"]'
                    ).remove();


                    /*
                    |--------------------------------------------------------------------------
                    | New Expense Document Input
                    |--------------------------------------------------------------------------
                    */

                    const documentInput =
                        $row.find(
                            '.expense-document-input'
                        ).get(0);

                    const documentPreview =
                        $row.find(
                            '.expense-document-preview'
                        ).get(0);

                    clearFileInputPreview(
                        documentInput,
                        documentPreview
                    );


                    updateExpenseRemoveButtons();

                    calculateFinancialSummary();

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Removing Existing Row
                |--------------------------------------------------------------------------
                |
                | Backend service handles database child deletion
                | and old document cleanup.
                |
                */

                $row.remove();

                updateExpenseIndexes();

                updateExpenseRemoveButtons();

                calculateFinancialSummary();
            }
        );


        /*
        |--------------------------------------------------------------------------
        | REMOVE EMPTY ALLOWANCE ROWS
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

            updateAllowanceRemoveButtons();
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE EMPTY EXPENSE ROWS
        |--------------------------------------------------------------------------
        */

        function removeEmptyExpenseRows() {

            $('#expense-wrapper .expense-row')
                .each(function () {

                    const $row =
                        $(this);

                    const expenseId =
                        $row.find(
                            '.expense-select'
                        ).val();

                    /*
                    |--------------------------------------------------------------------------
                    | Existing rows have Expense ID selected.
                    | New blank rows are removed before submit.
                    |--------------------------------------------------------------------------
                    */

                    if (!expenseId) {

                        $row.remove();
                    }
                });

            updateExpenseIndexes();

            updateExpenseRemoveButtons();
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
        | DATETIME VALIDATION
        |--------------------------------------------------------------------------
        */

        function validateDateTime() {

            const startDate =
                $('#start_date').val();

            const endDate =
                $('#end_date').val();

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
                Number.isNaN(
                    start.getTime()
                ) ||
                Number.isNaN(
                    end.getTime()
                )
            ) {

                return true;
            }

            if (
                end < start
            ) {

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
                | HTML VALIDATION
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
                | DATE / TIME
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
                | KM
                |--------------------------------------------------------------------------
                */

                const openingRaw =
                    $('#opening_km').val();

                const closingRaw =
                    $('#closing_km').val();

                if (
                    openingRaw !== '' &&
                    closingRaw !== ''
                ) {

                    const opening =
                        parseFloat(openingRaw);

                    const closing =
                        parseFloat(closingRaw);

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
                }


                /*
                |--------------------------------------------------------------------------
                | EXPENSE DOCUMENT VALIDATION
                |--------------------------------------------------------------------------
                */

                if (
                    !validateExpenseDocuments()
                ) {

                    event.preventDefault();

                    return false;
                }


                /*
                |--------------------------------------------------------------------------
                | REMOVE EMPTY CHILD ROWS
                |--------------------------------------------------------------------------
                */

                removeEmptyAllowanceRows();

                removeEmptyExpenseRows();


                /*
                |--------------------------------------------------------------------------
                | FINAL INDEXES
                |--------------------------------------------------------------------------
                */

                updateAllowanceIndexes();

                updateExpenseIndexes();


                /*
                |--------------------------------------------------------------------------
                | FINAL CALCULATIONS
                |--------------------------------------------------------------------------
                */

                calculateTotalKm();


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
                        }
                    });


                /*
                |--------------------------------------------------------------------------
                | FINAL EXPENSE CALCULATION
                |--------------------------------------------------------------------------
                |
                | IMPORTANT:
                | Actual Rate entered by user is preserved.
                | Expense Master rate is never applied.
                |
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

                            calculateExpenseRow($row);
                        }
                    });


                calculateFinancialSummary();


                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | No Duty Assignment -> Driver sync.
                | No Duty Assignment -> Vehicle sync.
                | No Duty Assignment -> Vehicle Type sync.
                |
                | User-selected values remain unchanged.
                |
                */


                /*
                |--------------------------------------------------------------------------
                | PREVENT DUPLICATE SUBMISSION
                |--------------------------------------------------------------------------
                */

                const $button =
                    $('#update-duty-slip-btn');

                if (
                    $button.length
                ) {

                    $button
                        .prop(
                            'disabled',
                            true
                        )
                        .html(
                            '<i class="fa fa-spinner fa-spin"></i> Updating Duty Slip...'
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
        | INITIALIZE EXISTING ALLOWANCES
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
        | INITIALIZE EXISTING EXPENSES
        |--------------------------------------------------------------------------
        |
        | Existing saved Rate is preserved.
        | No Expense Master Rate is applied.
        |
        */

        $('#expense-wrapper .expense-row')
            .each(function () {

                const $row =
                    $(this);

                calculateExpenseRow($row);
            });


        /*
        |--------------------------------------------------------------------------
        | INITIALIZATION
        |--------------------------------------------------------------------------
        */

        updateAllowanceIndexes();

        updateExpenseIndexes();

        updateAllowanceRemoveButtons();

        updateExpenseRemoveButtons();

        calculateTotalKm();

        calculateFinancialSummary();

    });

})(jQuery);