document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const dutySlipSelect = document.getElementById('duty_slip_id');
    const driverIdInput = document.getElementById('driver_id');
    const driverNameInput = document.getElementById('driver_name');
    const dutySlipInfo = document.getElementById('duty-slip-info');
    const infoSlipNo = document.getElementById('info-slip-no');
    const infoStatus = document.getElementById('info-status');
    const infoDriver = document.getElementById('info-driver');
    const infoVehicle = document.getElementById('info-vehicle');
    const infoTravelRequest = document.getElementById('info-travel-request');
    const workDate = document.getElementById('work_date');
    const openingMeter = document.getElementById('opening_meter');
    const closingMeter = document.getElementById('closing_meter');
    const totalKm = document.getElementById('total_km');
    const totalHours = document.getElementById('total_hours');
    const overtimeHours = document.getElementById('overtime_hours');
    const baseAmount = document.getElementById('base_amount');
    const extraKmAmount = document.getElementById('extra_km_amount');
    const overtimeAmount = document.getElementById('overtime_amount');
    const allowanceAmount = document.getElementById('allowance_amount');
    const expenseAmount = document.getElementById('expense_amount');
    const otherAmount = document.getElementById('other_amount');
    const totalAmount = document.getElementById('total_amount');
    const sheetNo = document.getElementById('sheet_no');
    const remarks = document.getElementById('remarks');
    const status = document.getElementById('status');
    const form = document.getElementById('working-sheet-form');
    const submitButton = document.getElementById('save-working-sheet');
    const allowanceRecordsBody = document.getElementById('allowance-records-body');
    const expenseRecordsBody = document.getElementById('expense-records-body');
    const allowanceApprovedTotal = document.getElementById('allowance-approved-total');
    const expenseApprovedTotal = document.getElementById('expense-approved-total');
    let submitting = false;

    /*
    |--------------------------------------------------------------------------
    | HELPER - NUMERIC VALUE
    |--------------------------------------------------------------------------
    */

    function numericValue(input) {
        if (!input || String(input.value).trim() === '') {
            return null;
        }
        const value = Number.parseFloat(input.value);
        return Number.isFinite(value) ? value : null;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER - DISPLAY TEXT
    |--------------------------------------------------------------------------
    */

    function setText(element, value, fallback) {
        if (element) {
            element.textContent = value || fallback || '-';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ALLOWANCE / EXPENSE DETAIL TABLE HELPERS
    |--------------------------------------------------------------------------
    | Each Duty Slip option receives its matching detailed records from the
    | controller. All statuses appear in the tables; only approved rows are
    | counted towards the Allowance Amount and Expense Amount fields.
    |--------------------------------------------------------------------------
    */

    function readRecordsFromOption(option, datasetKey) {
        if (!option || !option.value) {
            return [];
        }

        const rawValue = option.dataset[datasetKey] || '[]';

        try {
            const records = JSON.parse(rawValue);
            return Array.isArray(records) ? records : [];
        } catch (error) {
            console.error('Unable to read Duty Slip record details:', error);
            return [];
        }
    }

    function formatRecordNumber(value) {
        const number = Number.parseFloat(value);
        return Number.isFinite(number) ? number.toFixed(2) : '0.00';
    }

    function createTableCell(row, value, className) {
        const cell = document.createElement('td');
        cell.textContent = value === null || value === undefined || value === ''
            ? '-'
            : String(value);

        if (className) {
            cell.className = className;
        }

        row.appendChild(cell);
        return cell;
    }

    function getStatusBadgeClass(statusValue) {
        switch (String(statusValue || '').toLowerCase()) {
            case 'approved':
                return 'badge badge-success';
            case 'rejected':
                return 'badge badge-danger';
            case 'paid':
                return 'badge badge-primary';
            case 'cancelled':
                return 'badge badge-secondary';
            case 'pending':
            default:
                return 'badge badge-warning';
        }
    }

    function renderRecordRows(tbody, records, recordType) {
        if (!tbody) {
            return;
        }

        tbody.replaceChildren();

        if (!records.length) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            cell.colSpan = 7;
            cell.className = 'text-center text-muted';
            cell.textContent = 'No ' + recordType.toLowerCase() + ' records found for this Duty Slip.';
            row.appendChild(cell);
            tbody.appendChild(row);
            return;
        }

        records.forEach(function (record, index) {
            const row = document.createElement('tr');
            createTableCell(row, index + 1);
            createTableCell(row, record.label || (recordType + ' #' + (record.id || '')));
            createTableCell(row, formatRecordNumber(record.quantity));
            createTableCell(row, formatRecordNumber(record.rate));
            createTableCell(row, formatRecordNumber(record.amount));

            const statusCell = document.createElement('td');
            const statusBadge = document.createElement('span');
            const statusValue = String(record.status || 'pending');
            statusBadge.className = getStatusBadgeClass(statusValue);
            statusBadge.textContent = statusValue.charAt(0).toUpperCase() + statusValue.slice(1);
            statusCell.appendChild(statusBadge);
            row.appendChild(statusCell);

            let detailText = record.remarks || '';
            if (recordType === 'Expense' && record.document_file) {
                detailText = detailText
                    ? detailText + ' | Document: ' + record.document_file
                    : 'Document: ' + record.document_file;
            }
            createTableCell(row, detailText, 'record-remarks');

            tbody.appendChild(row);
        });
    }

    function getApprovedTotal(records) {
        return records.reduce(function (total, record) {
            const isApproved = String(record.status || '').toLowerCase() === 'approved';
            const amount = Number.parseFloat(record.amount);

            return total + (isApproved && Number.isFinite(amount) && amount >= 0 ? amount : 0);
        }, 0);
    }

    function renderAllowanceExpenseTables(option) {
        const selectedOption = option && option.value ? option : null;
        const allowanceRecords = readRecordsFromOption(
            selectedOption,
            'allowanceRecords'
        );
        const expenseRecords = readRecordsFromOption(
            selectedOption,
            'expenseRecords'
        );

        renderRecordRows(
            allowanceRecordsBody,
            allowanceRecords,
            'Allowance'
        );
        renderRecordRows(
            expenseRecordsBody,
            expenseRecords,
            'Expense'
        );

        const allowanceTotal = getApprovedTotal(allowanceRecords);
        const expenseTotal = getApprovedTotal(expenseRecords);

        if (allowanceApprovedTotal) {
            allowanceApprovedTotal.textContent = allowanceTotal.toFixed(2);
        }

        if (expenseApprovedTotal) {
            expenseApprovedTotal.textContent = expenseTotal.toFixed(2);
        }

        if (allowanceAmount) {
            allowanceAmount.value = allowanceTotal.toFixed(2);
        }

        if (expenseAmount) {
            expenseAmount.value = expenseTotal.toFixed(2);
        }

        calculateTotalAmount();
    }

    /*
    |--------------------------------------------------------------------------
    | GET SELECTED DUTY SLIP OPTION
    |--------------------------------------------------------------------------
    */

    function getSelectedDutySlipOption() {
        if (!dutySlipSelect || dutySlipSelect.selectedIndex < 0) {
            return null;
        }
        return dutySlipSelect.options[dutySlipSelect.selectedIndex] || null;
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DATE FOR HTML DATE INPUT
    |--------------------------------------------------------------------------
    | HTML input[type=date] requires YYYY-MM-DD. Support this format and the
    | older DD-MM-YYYY format so a Duty Slip date can still populate correctly.
    |--------------------------------------------------------------------------
    */

    function normalizeDateForInput(value) {
        if (!value) {
            return '';
        }

        const dateValue = String(value).trim();

        if (/^\d{4}-\d{2}-\d{2}$/.test(dateValue)) {
            return dateValue;
        }

        const dayFirstMatch = dateValue.match(/^(\d{2})-(\d{2})-(\d{4})$/);

        if (dayFirstMatch) {
            return dayFirstMatch[3] + '-' + dayFirstMatch[2] + '-' + dayFirstMatch[1];
        }

        return '';
    }

    /*
    |--------------------------------------------------------------------------
    | DUTY SLIP INFORMATION
    |--------------------------------------------------------------------------
    */

    function updateDutySlipDetails() {
        if (!dutySlipSelect) {
            return;
        }
        const selectedOption = getSelectedDutySlipOption();

        /*
        |--------------------------------------------------------------------------
        | NO DUTY SLIP SELECTED
        |--------------------------------------------------------------------------
        */

        if (!selectedOption || !selectedOption.value) {
            if (driverIdInput) {
                driverIdInput.value = '';
            }
            if (driverNameInput) {
                driverNameInput.value = '';
            }
            if (dutySlipInfo) {
                dutySlipInfo.style.display = 'none';
            }
            setText(infoSlipNo, '-', '-');
            setText(infoStatus, '-', '-');
            setText(infoDriver, '-', '-');
            setText(infoVehicle, '-', '-');
            setText(infoTravelRequest, '-', '-');
            renderAllowanceExpenseTables(null);
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | GET SELECTED DUTY SLIP DATA
        |--------------------------------------------------------------------------
        */

        const driverId = selectedOption.dataset.driverId || '';
        const driver = selectedOption.dataset.driver || '';
        const slipNo = selectedOption.dataset.slipNo || selectedOption.value;
        const slipStatus = selectedOption.dataset.status || '-';
        const vehicle = selectedOption.dataset.vehicle || '';
        const travelRequest = selectedOption.dataset.travelRequest || '-';

        /*
        |--------------------------------------------------------------------------
        | SET DRIVER
        |--------------------------------------------------------------------------
        */

        if (driverIdInput) {
            driverIdInput.value = driverId;
        }
        if (driverNameInput) {
            driverNameInput.value = driver || 'Driver not assigned';
        }

        /*
        |--------------------------------------------------------------------------
        | DISPLAY DUTY SLIP DETAILS
        |--------------------------------------------------------------------------
        */

        setText(infoSlipNo, slipNo, '-');
        setText(infoStatus, slipStatus, '-');
        setText(infoDriver, driver, 'Not Assigned');
        setText(infoVehicle, vehicle, 'Not Assigned');
        setText(infoTravelRequest, travelRequest, '-');
        if (dutySlipInfo) {
            dutySlipInfo.style.display = 'block';
        }

        renderAllowanceExpenseTables(selectedOption);
    }

    /*
    |--------------------------------------------------------------------------
    | WORK DATE AND TOTAL HOURS - DUTY SLIP DATA
    |--------------------------------------------------------------------------
    | The Blade option supplies duty_date, start_time and end_time as data
    | attributes. Total Hours is calculated as the duration between the two
    | timestamps. Overtime is not sourced from duty_slips because the supplied
    | database schema has no overtime_hours column.
    |--------------------------------------------------------------------------
    */

    function calculateDutyDuration(selectedOption) {
        if (!selectedOption) {
            return '';
        }
        const startTime = selectedOption.dataset.startTime || '';
        const endTime = selectedOption.dataset.endTime || '';
        if (!startTime || !endTime) {
            return '';
        }
        const startTimestamp = Date.parse(startTime);
        const endTimestamp = Date.parse(endTime);
        if (
            !Number.isFinite(startTimestamp) ||
            !Number.isFinite(endTimestamp) ||
            endTimestamp < startTimestamp
        ) {
            return '';
        }
        const differenceInMilliseconds = endTimestamp - startTimestamp;
        const differenceInHours = differenceInMilliseconds / 3600000;
        return differenceInHours.toFixed(2);
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL KM CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateTotalKm(savedTotalKm, preserveWhenIncomplete) {
        if (!openingMeter || !closingMeter || !totalKm) {
            return true;
        }
        const opening = numericValue(openingMeter);
        const closing = numericValue(closingMeter);

        /*
        |--------------------------------------------------------------------------
        | BOTH VALUES REQUIRED
        |--------------------------------------------------------------------------
        */

        if (opening === null || closing === null) {
            closingMeter.classList.remove('is-invalid');
            if (savedTotalKm !== undefined && savedTotalKm !== null && savedTotalKm !== '') {
                totalKm.value = savedTotalKm;
            } else if (!preserveWhenIncomplete) {
                totalKm.value = '';
            }
            return true;
        }

        /*
        |--------------------------------------------------------------------------
        | CLOSING METER VALIDATION
        |--------------------------------------------------------------------------
        */

        if (closing < opening) {
            totalKm.value = '';
            closingMeter.classList.add('is-invalid');
            return false;
        }
        closingMeter.classList.remove('is-invalid');

        /*
        |--------------------------------------------------------------------------
        | CALCULATE TOTAL KM
        |--------------------------------------------------------------------------
        */

        totalKm.value = (closing - opening).toFixed(2);
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO-FILL WORKING DETAILS FROM DUTY SLIP
    |--------------------------------------------------------------------------
    | overwrite = true  : user deliberately changed the Duty Slip.
    | overwrite = false : initial page load; preserve old() values where present.
    |--------------------------------------------------------------------------
    */

    function populateWorkDetailsFromDutySlip(selectedOption, overwrite) {
        if (!selectedOption || !selectedOption.value) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | READ DUTY SLIP VALUES
        |--------------------------------------------------------------------------
        */

        const sourceDate = normalizeDateForInput(selectedOption.dataset.date || '');
        const sourceOpeningMeter = selectedOption.dataset.openingMeter || '';
        const sourceClosingMeter = selectedOption.dataset.closingMeter || '';
        const sourceTotalKm = selectedOption.dataset.totalKm || '';
        const sourceTotalHours = calculateDutyDuration(selectedOption);
        const sourceAllowanceTotal = selectedOption.dataset.allowanceTotal || '0.00';
        const sourceExpenseTotal = selectedOption.dataset.expenseTotal || '0.00';

        /*
        |--------------------------------------------------------------------------
        | WORK DATE
        |--------------------------------------------------------------------------
        */

        if (workDate && sourceDate && (overwrite || !workDate.value)) {
            workDate.value = sourceDate;
        }

        /*
        |--------------------------------------------------------------------------
        | OPENING METER
        |--------------------------------------------------------------------------
        */

        if (openingMeter && (overwrite || !openingMeter.value.trim())) {
            openingMeter.value = sourceOpeningMeter;
        }

        /*
        |--------------------------------------------------------------------------
        | CLOSING METER
        |--------------------------------------------------------------------------
        */

        if (closingMeter && (overwrite || !closingMeter.value.trim())) {
            closingMeter.value = sourceClosingMeter;
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL KM
        |--------------------------------------------------------------------------
        */

        if (totalKm && (overwrite || !totalKm.value.trim())) {
            calculateTotalKm(sourceTotalKm, false);
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL HOURS
        |--------------------------------------------------------------------------
        */

        if (totalHours && (overwrite || !totalHours.value.trim())) {
            totalHours.value = sourceTotalHours;
        }

        /*
        |--------------------------------------------------------------------------
        | OVERTIME HOURS
        |--------------------------------------------------------------------------
        | There is no overtime_hours column in duty_slips. Keep the existing
        | manually entered value; initialise an empty value to zero only when a
        | different Duty Slip is selected.
        |--------------------------------------------------------------------------
        */

        if (overtimeHours && overwrite && !overtimeHours.value.trim()) {
            overtimeHours.value = '0.00';
        }

        /*
        |--------------------------------------------------------------------------
        | APPROVED ALLOWANCE AND EXPENSE TOTALS
        |--------------------------------------------------------------------------
        | The totals are aggregated server-side for this exact Duty Slip ID.
        | Only approved allowances/expenses are included, matching the DutySlip
        | model's approvedDriverAllowances / approvedDriverExpenses relationships.
        |--------------------------------------------------------------------------
        */
        if (allowanceAmount && (overwrite || !allowanceAmount.value.trim())) {
            allowanceAmount.value = sourceAllowanceTotal;
        }
        if (expenseAmount && (overwrite || !expenseAmount.value.trim())) {
            expenseAmount.value = sourceExpenseTotal;
        }
        calculateTotalAmount();
    }

    /*
    |--------------------------------------------------------------------------
    | DUTY SLIP CHANGE EVENT
    |--------------------------------------------------------------------------
    */

    function handleDutySlipChange() {
        updateDutySlipDetails();
        const selectedOption = getSelectedDutySlipOption();

        /*
        |--------------------------------------------------------------------------
        | NO DUTY SLIP SELECTED
        |--------------------------------------------------------------------------
        */

        if (!selectedOption || !selectedOption.value) {
            if (openingMeter) {
                openingMeter.value = '';
            }
            if (closingMeter) {
                closingMeter.value = '';
                closingMeter.classList.remove('is-invalid');
            }
            if (totalKm) {
                totalKm.value = '';
            }
            if (totalHours) {
                totalHours.value = '';
            }
            if (overtimeHours) {
                overtimeHours.value = '0.00';
            }
            if (allowanceAmount) allowanceAmount.value = '0.00';
            if (expenseAmount) expenseAmount.value = '0.00';
            calculateTotalAmount();
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | POPULATE VALUES FOR NEWLY SELECTED DUTY SLIP
        |--------------------------------------------------------------------------
        */

        populateWorkDetailsFromDutySlip(selectedOption, true);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER DUTY SLIP CHANGE EVENT
    |--------------------------------------------------------------------------
    */

    if (dutySlipSelect) {
        // Support Select2 and normal select elements.
        if (window.jQuery) {
            window.jQuery(dutySlipSelect)
                .off('change.workingSheet')
                .on('change.workingSheet', handleDutySlipChange);
        } else {
            dutySlipSelect.addEventListener(
                'change',
                handleDutySlipChange
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | WORK DATE CHANGE - FIND THIS DRIVER'S DUTY SLIP FOR THAT DATE
    |--------------------------------------------------------------------------
    | Driver is derived from the selected Duty Slip. When the date changes,
    | search the rendered Duty Slip options for the same driver/date pair.
    | Each option carries approved allowance and expense sums for its own slip.
    |--------------------------------------------------------------------------
    */

    function handleWorkDateChange() {
        if (!workDate || !dutySlipSelect) return;

        const requestedDate = normalizeDateForInput(workDate.value || '');
        if (!requestedDate) return;

        const currentOption = getSelectedDutySlipOption();
        const driverId =
            (driverIdInput && driverIdInput.value) ||
            (currentOption && currentOption.dataset.driverId) ||
            '';

        // The form does not have a separate Driver selector, so the user must
        // choose a Duty Slip first to identify which driver's history to match.
        if (!driverId) return;

        const matches = Array.from(dutySlipSelect.options).filter(function (option) {
            return option.value &&
                String(option.dataset.driverId || '') === String(driverId) &&
                normalizeDateForInput(option.dataset.date || '') === requestedDate;
        });

        if (!matches.length) {
            // Do not leave records or financial totals from a different date/slip on the form.
            renderAllowanceExpenseTables(null);
            if (allowanceAmount) allowanceAmount.value = '0.00';
            if (expenseAmount) expenseAmount.value = '0.00';
            calculateTotalAmount();
            window.alert(
                'No Duty Slip was found for this driver on the selected Work Date. Please choose a date with a Duty Slip or select the correct Duty Slip.'
            );
            return;
        }

        // If the current slip already matches, retain it; otherwise choose the
        // newest matching option (the controller orders options by descending ID).
        const matchedOption = matches.find(function (option) {
            return currentOption && option.value === currentOption.value;
        }) || matches[0];

        if (dutySlipSelect.value !== matchedOption.value) {
            dutySlipSelect.value = matchedOption.value;
            if (window.jQuery) {
                window.jQuery(dutySlipSelect).trigger('change');
            } else {
                dutySlipSelect.dispatchEvent(new Event('change', { bubbles: true }));
            }
        } else {
            // Refill aggregates for the already selected matching slip.
            populateWorkDetailsFromDutySlip(matchedOption, true);
            updateDutySlipDetails();
        }
    }

    if (workDate) {
        workDate.addEventListener('change', handleWorkDateChange);
    }

    /*
    |--------------------------------------------------------------------------
    | OPENING METER EVENTS
    |--------------------------------------------------------------------------
    */

    if (openingMeter) {
        openingMeter.addEventListener(
            'input',
            function () {
                calculateTotalKm('', false);
            }
        );
        openingMeter.addEventListener(
            'change',
            function () {
                calculateTotalKm('', false);
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CLOSING METER EVENTS
    |--------------------------------------------------------------------------
    */

    if (closingMeter) {
        closingMeter.addEventListener(
            'input',
            function () {
                calculateTotalKm('', false);
            }
        );
        closingMeter.addEventListener(
            'change',
            function () {
                calculateTotalKm('', false);
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL AMOUNT CALCULATION
    |--------------------------------------------------------------------------
    */

    function calculateTotalAmount() {
        if (!totalAmount) {
            return;
        }
        const toAmount = function (input) {
            const value = numericValue(input);
            return value !== null && value >= 0 ? value : 0;
        };
        const total =
            toAmount(baseAmount) +
            toAmount(extraKmAmount) +
            toAmount(overtimeAmount) +
            toAmount(allowanceAmount) +
            toAmount(expenseAmount) +
            toAmount(otherAmount);
        totalAmount.value = total.toFixed(2);
    }

    /*
    |--------------------------------------------------------------------------
    | AMOUNT FIELD EVENTS
    |--------------------------------------------------------------------------
    */

    [
        baseAmount,
        extraKmAmount,
        overtimeAmount,
        allowanceAmount,
        expenseAmount,
        otherAmount
    ].forEach(function (input) {
        if (!input) {
            return;
        }
        input.addEventListener(
            'input',
            calculateTotalAmount
        );
        input.addEventListener(
            'change',
            calculateTotalAmount
        );
    });

    /*
    |--------------------------------------------------------------------------
    | NUMERIC INPUT CLEANUP
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.decimal-input, .amount-input')
        .forEach(function (input) {
            input.addEventListener(
                'input',
                function () {
                    let value = this.value;
                    // Remove characters other than digits and decimal points.
                    value = value.replace(/[^0-9.]/g, '');
                    // Allow only one decimal point.
                    const parts = value.split('.');
                    if (parts.length > 2) {
                        value =
                            parts[0] +
                            '.' +
                            parts.slice(1).join('');
                    }
                    this.value = value;
                }
            );
        });

    /*
    |--------------------------------------------------------------------------
    | WORKING SHEET NUMBER FORMATTING
    |--------------------------------------------------------------------------
    */

    if (sheetNo) {
        sheetNo.addEventListener(
            'blur',
            function () {
                this.value = this.value
                    .trim()
                    .toUpperCase();
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REMARKS FORMATTING
    |--------------------------------------------------------------------------
    */

    if (remarks) {
        remarks.addEventListener(
            'blur',
            function () {
                this.value = this.value.trim();
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD - DUTY SLIP INFORMATION
    |--------------------------------------------------------------------------
    */

    updateDutySlipDetails();

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD - FILL EMPTY WORKING DETAILS
    |--------------------------------------------------------------------------
    | Do not replace values supplied by old() after a validation failure.
    |--------------------------------------------------------------------------
    */

    const initiallySelectedOption = getSelectedDutySlipOption();
    if (initiallySelectedOption && initiallySelectedOption.value) {
        populateWorkDetailsFromDutySlip(
            initiallySelectedOption,
            false
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD - TOTAL KM
    |--------------------------------------------------------------------------
    */

    calculateTotalKm('', true);

    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD - TOTAL AMOUNT
    |--------------------------------------------------------------------------
    */

    calculateTotalAmount();

    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT VALIDATION
    |--------------------------------------------------------------------------
    */

    if (form) {
        form.addEventListener(
            'submit',
            function (event) {

                /*
                |------------------------------------------------------------------
                | PREVENT DOUBLE SUBMISSION
                |------------------------------------------------------------------
                */

                if (submitting) {
                    event.preventDefault();
                    return;
                }

                /*
                |------------------------------------------------------------------
                | CLEAN SHEET NUMBER
                |------------------------------------------------------------------
                */

                if (sheetNo) {
                    sheetNo.value = sheetNo.value
                        .trim()
                        .toUpperCase();
                }

                /*
                |------------------------------------------------------------------
                | CLEAN REMARKS
                |------------------------------------------------------------------
                */

                if (remarks) {
                    remarks.value = remarks.value.trim();
                }

                /*
                |------------------------------------------------------------------
                | VALIDATE SHEET NUMBER
                |------------------------------------------------------------------
                */

                if (!sheetNo || !sheetNo.value.trim()) {
                    event.preventDefault();
                    window.alert('Please enter the Working Sheet Number.');
                    if (sheetNo) {
                        sheetNo.focus();
                    }
                    return;
                }

                /*
                |------------------------------------------------------------------
                | VALIDATE WORK DATE
                |------------------------------------------------------------------
                */

                if (!workDate || !workDate.value) {
                    event.preventDefault();
                    window.alert('Please select the Work Date.');
                    if (workDate) {
                        workDate.focus();
                    }
                    return;
                }

                /*
                |------------------------------------------------------------------
                | VALIDATE STATUS
                |------------------------------------------------------------------
                */

                if (!status || !status.value) {
                    event.preventDefault();
                    window.alert('Please select the Working Sheet Status.');
                    if (status) {
                        status.focus();
                    }
                    return;
                }

                /*
                |------------------------------------------------------------------
                | VALIDATE DUTY SLIP
                |------------------------------------------------------------------
                */

                if (!dutySlipSelect || !dutySlipSelect.value) {
                    event.preventDefault();
                    window.alert('Please select a duty slip.');
                    if (dutySlipSelect) {
                        dutySlipSelect.focus();
                    }
                    return;
                }

                /*
                |------------------------------------------------------------------
                | VALIDATE DRIVER
                |------------------------------------------------------------------
                */

                if (!driverIdInput || !driverIdInput.value) {
                    event.preventDefault();
                    window.alert(
                        'Selected Duty Slip does not have an assigned driver.'
                    );
                    if (dutySlipSelect) {
                        dutySlipSelect.focus();
                    }
                    return;
                }

                /*
                |------------------------------------------------------------------
                | VALIDATE DUTY SLIP DATE MATCHES WORK DATE
                |------------------------------------------------------------------
                */

                const selectedDutySlipOption = getSelectedDutySlipOption();
                const selectedSlipDate = selectedDutySlipOption
                    ? normalizeDateForInput(selectedDutySlipOption.dataset.date || '')
                    : '';
                const selectedWorkDate = normalizeDateForInput(workDate ? workDate.value : '');

                if (selectedSlipDate && selectedWorkDate && selectedSlipDate !== selectedWorkDate) {
                    event.preventDefault();
                    window.alert(
                        'The selected Duty Slip does not belong to the selected Work Date. Please select the matching Duty Slip.'
                    );
                    if (workDate) workDate.focus();
                    return;
                }

                /*
                |------------------------------------------------------------------
                | VALIDATE METER READINGS
                |------------------------------------------------------------------
                */

                const opening = numericValue(openingMeter);
                const closing = numericValue(closingMeter);
                if (
                    opening !== null &&
                    closing !== null &&
                    closing < opening
                ) {
                    event.preventDefault();
                    if (closingMeter) {
                        closingMeter.classList.add('is-invalid');
                        closingMeter.focus();
                    }
                    window.alert(
                        'Closing meter must be greater than or equal to opening meter.'
                    );
                    return;
                }

                /*
                |------------------------------------------------------------------
                | RECALCULATE TOTAL KM
                |------------------------------------------------------------------
                */

                calculateTotalKm('', true);

                /*
                |------------------------------------------------------------------
                | RECALCULATE TOTAL AMOUNT
                |------------------------------------------------------------------
                */

                calculateTotalAmount();

                /*
                |------------------------------------------------------------------
                | PREVENT DOUBLE SUBMISSION
                |------------------------------------------------------------------
                */

                submitting = true;
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<i class="fa fa-spinner fa-spin"></i> Saving Working Sheet...';
                }
            }
        );
    }
});
