/**
 * Working Sheet edit form behaviour.
 * File: public/backend/assets/js/working-sheets/edit.js
 *
 * Duty Slip data attributes are provided by:
 * resources/views/backend/working-sheets/edit.blade.php
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('working-sheet-form');
        if (!form) return;

        // ---------------------------------------------------------------------
        // ELEMENTS
        // ---------------------------------------------------------------------
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
        const baseAmountHelp = document.getElementById('base-amount-help');
        const extraKmAmount = document.getElementById('extra_km_amount');
        const overtimeAmount = document.getElementById('overtime_amount');
        const allowanceAmount = document.getElementById('allowance_amount');
        const expenseAmount = document.getElementById('expense_amount');
        const otherAmount = document.getElementById('other_amount');

        const amountInputs = [
            baseAmount,
            extraKmAmount,
            overtimeAmount,
            allowanceAmount,
            expenseAmount,
            otherAmount
        ].filter(Boolean);

        const totalAmount = document.getElementById('total_amount');
        const sheetNo = document.getElementById('sheet_no');
        const remarks = document.getElementById('remarks');
        const submitButton = document.getElementById('update-working-sheet');
        const allowanceRecordsBody = document.getElementById('allowance-records-body');
        const expenseRecordsBody = document.getElementById('expense-records-body');
        const allowanceApprovedTotal = document.getElementById('allowance-approved-total');
        const expenseApprovedTotal = document.getElementById('expense-approved-total');

        let submitting = false;

        // ---------------------------------------------------------------------
        // ORIGINAL SAVED SNAPSHOT IDENTITY
        // ---------------------------------------------------------------------
        // The saved Base Amount is a historical snapshot. Do not overwrite it
        // merely because this edit form is opened after a driver's salary has
        // changed. Recalculate only when the resolved driver or Work Date is
        // different from the values stored on the existing Working Sheet.
        // These original values are supplied by the edit Blade form.
        const originalDriverId = String(
            form.dataset.originalDriverId || (driverIdInput ? driverIdInput.value : '') || ''
        );

        const originalWorkDate = normalizeDateForInput(
            form.dataset.originalWorkDate || (workDate ? workDate.value : '')
        );

        const originalBaseAmountValue = Number.parseFloat(
            form.dataset.originalBaseAmount || (baseAmount ? baseAmount.value : '0')
        );

        const originalBaseAmount = Number.isFinite(originalBaseAmountValue) && originalBaseAmountValue >= 0
            ? originalBaseAmountValue
            : 0;

        // ---------------------------------------------------------------------
        // HELPERS
        // ---------------------------------------------------------------------
        function setText(element, value, fallback) {
            if (element) {
                element.textContent = value || fallback || '-';
            }
        }

        function numericValue(input) {
            if (!input || String(input.value).trim() === '') {
                return null;
            }

            const value = Number.parseFloat(input.value);
            return Number.isFinite(value) ? value : null;
        }

        function hasValue(input) {
            return Boolean(input) && String(input.value).trim() !== '';
        }

        function getSelectedOption() {
            if (!dutySlipSelect || dutySlipSelect.selectedIndex < 0) {
                return null;
            }

            return dutySlipSelect.options[dutySlipSelect.selectedIndex] || null;
        }

        function removeInvalidMeterState() {
            if (closingMeter) {
                closingMeter.classList.remove('is-invalid');
            }
        }

        function normalizeDateForInput(value) {
            if (!value) return '';

            const dateValue = String(value).trim();

            // Already in the date-input format.
            if (/^\d{4}-\d{2}-\d{2}$/.test(dateValue)) {
                return dateValue;
            }

            // Convert DD-MM-YYYY or DD/MM/YYYY to YYYY-MM-DD.
            const dmy = dateValue.match(/^(\d{2})[-/](\d{2})[-/](\d{4})$/);
            if (dmy) {
                return dmy[3] + '-' + dmy[2] + '-' + dmy[1];
            }

            // If the source is a datetime, retain its date portion.
            const isoDateTime = dateValue.match(/^(\d{4}-\d{2}-\d{2})[T ]/);
            if (isoDateTime) {
                return isoDateTime[1];
            }

            return '';
        }

        // ---------------------------------------------------------------------
        // TOTAL AMOUNT
        // ---------------------------------------------------------------------
        function calculateTotalAmount() {
            if (!totalAmount) return;

            const sum = amountInputs.reduce(function (total, input) {
                const value = numericValue(input);
                return total + (value !== null && value >= 0 ? value : 0);
            }, 0);

            totalAmount.value = sum.toFixed(2);
        }

        // ---------------------------------------------------------------------
        // DAILY BASE AMOUNT - HISTORICAL SNAPSHOT RULE
        // ---------------------------------------------------------------------
        /**
         * Calculate daily Base Amount using:
         * monthly_gross_salary / actual calendar days in selected Work Date month.
         *
         * Existing saved snapshot is retained when the original driver and date
         * are unchanged. It is recalculated only when either identity changes.
         */
        function updateDailyBaseAmount() {
            if (!baseAmount) return;

            const selectedOption = getSelectedOption();
            const selectedDriverId = String(
                (selectedOption && selectedOption.dataset.driverId) ||
                (driverIdInput && driverIdInput.value) ||
                ''
            );
            const selectedWorkDate = normalizeDateForInput(workDate ? workDate.value : '');

            const driverChanged = selectedDriverId !== originalDriverId;
            const workDateChanged = selectedWorkDate !== originalWorkDate;
            const identityChanged = driverChanged || workDateChanged;

            // Preserve the stored historical snapshot on ordinary edits.
            if (!identityChanged) {
                baseAmount.value = originalBaseAmount.toFixed(2);

                if (baseAmountHelp) {
                    baseAmountHelp.textContent =
                        'Saved daily Base Amount snapshot: ₹' + originalBaseAmount.toFixed(2) +
                        '. It remains unchanged unless the Driver or Work Date changes.';
                }

                calculateTotalAmount();
                return true;
            }

            if (!selectedOption || !selectedOption.value || !selectedDriverId || !selectedWorkDate) {
                baseAmount.value = '0.00';

                if (baseAmountHelp) {
                    baseAmountHelp.textContent =
                        'Select a valid Duty Slip and Work Date to calculate the daily Base Amount.';
                }

                calculateTotalAmount();
                return false;
            }

            const monthlyGrossSalary = Number.parseFloat(
                selectedOption.dataset.monthlyGrossSalary || '0'
            );

            if (!Number.isFinite(monthlyGrossSalary) || monthlyGrossSalary <= 0) {
                baseAmount.value = '0.00';

                if (baseAmountHelp) {
                    baseAmountHelp.textContent =
                        'Monthly gross salary is not configured for the selected driver.';
                }

                calculateTotalAmount();
                return false;
            }

            const dateParts = selectedWorkDate.split('-').map(Number);
            const year = dateParts[0];
            const month = dateParts[1];

            if (!year || !month || month < 1 || month > 12) {
                baseAmount.value = '0.00';

                if (baseAmountHelp) {
                    baseAmountHelp.textContent = 'Please select a valid Work Date.';
                }

                calculateTotalAmount();
                return false;
            }

            // new Date(year, month, 0) returns the final day of the selected month.
            const daysInMonth = new Date(year, month, 0).getDate();

            if (!daysInMonth) {
                baseAmount.value = '0.00';
                if (baseAmountHelp) {
                    baseAmountHelp.textContent = 'Unable to determine calendar days for the selected month.';
                }
                calculateTotalAmount();
                return false;
            }

            const dailyAmount = Number((monthlyGrossSalary / daysInMonth).toFixed(2));
            baseAmount.value = dailyAmount.toFixed(2);

            if (baseAmountHelp) {
                baseAmountHelp.textContent =
                    '₹' + monthlyGrossSalary.toFixed(2) +
                    ' monthly gross salary ÷ ' + daysInMonth +
                    ' calendar days = ₹' + dailyAmount.toFixed(2) + ' per day.';
            }

            calculateTotalAmount();
            return true;
        }

        // ---------------------------------------------------------------------
        // ALLOWANCE / EXPENSE RECORD TABLES
        // ---------------------------------------------------------------------
        function readRecordsFromOption(option, datasetKey) {
            if (!option || !option.value) return [];

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

            if (className) cell.className = className;
            row.appendChild(cell);
            return cell;
        }

        function getStatusBadgeClass(statusValue) {
            switch (String(statusValue || '').toLowerCase()) {
                case 'approved': return 'badge badge-success';
                case 'rejected': return 'badge badge-danger';
                case 'paid': return 'badge badge-primary';
                case 'cancelled': return 'badge badge-secondary';
                case 'pending':
                default: return 'badge badge-warning';
            }
        }

        function renderRecordRows(tbody, records, recordType) {
            if (!tbody) return;

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
                const approved = String(record.status || '').toLowerCase() === 'approved';
                const amount = Number.parseFloat(record.amount);
                return total + (approved && Number.isFinite(amount) && amount >= 0 ? amount : 0);
            }, 0);
        }

        function renderAllowanceExpenseTables(option) {
            const selectedOption = option && option.value ? option : null;
            const allowanceRecords = readRecordsFromOption(selectedOption, 'allowanceRecords');
            const expenseRecords = readRecordsFromOption(selectedOption, 'expenseRecords');

            renderRecordRows(allowanceRecordsBody, allowanceRecords, 'Allowance');
            renderRecordRows(expenseRecordsBody, expenseRecords, 'Expense');

            const allowanceTotal = getApprovedTotal(allowanceRecords);
            const expenseTotal = getApprovedTotal(expenseRecords);

            if (allowanceApprovedTotal) allowanceApprovedTotal.textContent = allowanceTotal.toFixed(2);
            if (expenseApprovedTotal) expenseApprovedTotal.textContent = expenseTotal.toFixed(2);

            // Business rule: amount fields are calculated totals, not manual inputs.
            if (allowanceAmount) allowanceAmount.value = allowanceTotal.toFixed(2);
            if (expenseAmount) expenseAmount.value = expenseTotal.toFixed(2);

            calculateTotalAmount();
        }

        // ---------------------------------------------------------------------
        // TOTAL KM
        // ---------------------------------------------------------------------
        /**
         * Calculate Total KM from opening/closing meter readings.
         * If either reading is missing, use fallbackTotalKm when provided.
         * preserveIncompleteValue keeps an existing Total KM when no fallback
         * exists (useful for older records that have no meter readings).
         */
        function calculateTotalKm(options) {
            const settings = Object.assign({
                preserveIncompleteValue: false,
                fallbackTotalKm: ''
            }, options || {});

            if (!openingMeter || !closingMeter || !totalKm) {
                return true;
            }

            const opening = numericValue(openingMeter);
            const closing = numericValue(closingMeter);

            if (opening === null || closing === null) {
                removeInvalidMeterState();

                if (settings.fallbackTotalKm !== '' && settings.fallbackTotalKm !== null && settings.fallbackTotalKm !== undefined) {
                    totalKm.value = settings.fallbackTotalKm;
                } else if (!settings.preserveIncompleteValue) {
                    totalKm.value = '';
                }

                return true;
            }

            if (closing < opening) {
                totalKm.value = '';
                closingMeter.classList.add('is-invalid');
                return false;
            }

            closingMeter.classList.remove('is-invalid');
            totalKm.value = (closing - opening).toFixed(2);
            return true;
        }

        // ---------------------------------------------------------------------
        // TOTAL HOURS
        // ---------------------------------------------------------------------
        /** Calculate duty duration in hours using Duty Slip timestamps. */
        function getDurationHours(selectedOption) {
            if (!selectedOption) return '';

            const startValue = selectedOption.dataset.startTime || '';
            const endValue = selectedOption.dataset.endTime || '';

            if (!startValue || !endValue) return '';

            const start = Date.parse(startValue);
            const end = Date.parse(endValue);

            if (!Number.isFinite(start) || !Number.isFinite(end) || end < start) {
                return '';
            }

            return ((end - start) / 3600000).toFixed(2);
        }

        // ---------------------------------------------------------------------
        // AUTO-FILL WORKING DETAILS FROM DUTY SLIP
        // ---------------------------------------------------------------------
        /**
         * overwrite=true replaces linked values when the user changes Duty Slip.
         * overwrite=false only fills empty inputs during initial page load.
         */
        function populateWorkDetailsFromDutySlip(selectedOption, overwrite) {
            if (!selectedOption || !selectedOption.value) return;

            const sourceDate = selectedOption.dataset.date || selectedOption.dataset.dutyDate || '';
            const sourceOpeningMeter = selectedOption.dataset.openingMeter || '';
            const sourceClosingMeter = selectedOption.dataset.closingMeter || '';
            const sourceTotalKm = selectedOption.dataset.totalKm || '';
            const sourceTotalHours = selectedOption.dataset.totalHours || getDurationHours(selectedOption);
            const sourceOvertimeHours = selectedOption.dataset.overtimeHours || '';
            const sourceAllowanceTotal = selectedOption.dataset.allowanceTotal || '0.00';
            const sourceExpenseTotal = selectedOption.dataset.expenseTotal || '0.00';

            const shouldFill = function (input) {
                return Boolean(input) && (overwrite || !hasValue(input));
            };

            if (workDate && shouldFill(workDate) && sourceDate) {
                // Date inputs require YYYY-MM-DD.
                workDate.value = normalizeDateForInput(sourceDate);
            }

            if (openingMeter && shouldFill(openingMeter)) {
                openingMeter.value = sourceOpeningMeter;
            }

            if (closingMeter && shouldFill(closingMeter)) {
                closingMeter.value = sourceClosingMeter;
            }

            if (totalKm && shouldFill(totalKm)) {
                calculateTotalKm({
                    preserveIncompleteValue: false,
                    fallbackTotalKm: sourceTotalKm
                });
            } else if (totalKm && numericValue(openingMeter) !== null && numericValue(closingMeter) !== null) {
                // Keep Total KM synchronized with meter readings even if a saved
                // value was present on initial load.
                calculateTotalKm({ preserveIncompleteValue: true });
            }

            if (totalHours && shouldFill(totalHours)) {
                // Clear stale values when a newly selected Duty Slip has no times.
                totalHours.value = sourceTotalHours;
            }

            // The provided duty_slips schema has no overtime_hours column.
            // Do not infer a payroll rule; reset on a new Duty Slip only.
            if (overtimeHours && overwrite) {
                overtimeHours.value = sourceOvertimeHours !== '' ? sourceOvertimeHours : '0.00';
            } else if (overtimeHours && !hasValue(overtimeHours) && sourceOvertimeHours !== '') {
                overtimeHours.value = sourceOvertimeHours;
            }

            // Aggregate totals are preloaded for this exact Duty Slip ID.
            // Include approved allowance/expense rows only, as defined by the
            // DutySlip approvedDriverAllowances/approvedDriverExpenses relations.
            if (allowanceAmount && shouldFill(allowanceAmount)) {
                allowanceAmount.value = sourceAllowanceTotal;
            }
            if (expenseAmount && shouldFill(expenseAmount)) {
                expenseAmount.value = sourceExpenseTotal;
            }

            calculateTotalAmount();
        }

        // ---------------------------------------------------------------------
        // DUTY SLIP SUMMARY AND DRIVER
        // ---------------------------------------------------------------------
        function loadDutySlipInfo() {
            const selected = getSelectedOption();

            if (!selected || !selected.value) {
                if (driverIdInput) driverIdInput.value = '';
                if (driverNameInput) driverNameInput.value = '';
                if (dutySlipInfo) dutySlipInfo.style.display = 'none';

                setText(infoSlipNo, '-', '-');
                setText(infoStatus, '-', '-');
                setText(infoDriver, '-', '-');
                setText(infoVehicle, '-', '-');
                setText(infoTravelRequest, '-', '-');
                renderAllowanceExpenseTables(null);
                return;
            }

            const driverId = selected.dataset.driverId || '';
            const driverName = selected.dataset.driver || '';
            const slipNo = selected.dataset.slipNo || selected.value;
            const slipStatus = selected.dataset.status || '-';
            const vehicle = selected.dataset.vehicle || '';
            const travelRequest = selected.dataset.travelRequest || '-';

            if (driverIdInput) driverIdInput.value = driverId;
            if (driverNameInput) driverNameInput.value = driverName || 'Driver not assigned';

            setText(infoSlipNo, slipNo, '-');
            setText(infoStatus, slipStatus, '-');
            setText(infoDriver, driverName, 'Not Assigned');
            setText(infoVehicle, vehicle, 'Not Assigned');
            setText(infoTravelRequest, travelRequest, '-');

            if (dutySlipInfo) dutySlipInfo.style.display = 'block';

            renderAllowanceExpenseTables(selected);
        }

        // ---------------------------------------------------------------------
        // DUTY SLIP CHANGE EVENT
        // ---------------------------------------------------------------------
        function handleDutySlipChange() {
            const selected = getSelectedOption();
            loadDutySlipInfo();

            if (!selected || !selected.value) {
                // The user selected the placeholder; clear fields linked to a slip.
                if (workDate) workDate.value = '';
                if (openingMeter) openingMeter.value = '';
                if (closingMeter) closingMeter.value = '';
                if (totalKm) totalKm.value = '';
                if (totalHours) totalHours.value = '';
                if (overtimeHours) overtimeHours.value = '';
                if (allowanceAmount) allowanceAmount.value = '0.00';
                if (expenseAmount) expenseAmount.value = '0.00';
                if (baseAmount) baseAmount.value = '0.00';
                if (baseAmountHelp) {
                    baseAmountHelp.textContent = 'Select a Duty Slip and Work Date to calculate daily Base Amount.';
                }
                calculateTotalAmount();
                removeInvalidMeterState();
                return;
            }

            populateWorkDetailsFromDutySlip(selected, true);

            // Recalculate only if the selected driver or resulting Work Date
            // differs from the original Working Sheet identity.
            updateDailyBaseAmount();
        }

        if (dutySlipSelect) {
            // Select2 triggers the jQuery event; native select is also supported.
            if (window.jQuery) {
                window.jQuery(dutySlipSelect)
                    .off('change.workingSheetEdit')
                    .on('change.workingSheetEdit', handleDutySlipChange);
            } else {
                dutySlipSelect.addEventListener('change', handleDutySlipChange);
            }
        }

        // ---------------------------------------------------------------------
        // WORK DATE CHANGE - FIND THE SAME DRIVER'S DUTY SLIP FOR THAT DATE
        // ---------------------------------------------------------------------
        function handleWorkDateChange() {
            if (!workDate || !dutySlipSelect) return;

            const requestedDate = normalizeDateForInput(workDate.value || '');
            if (!requestedDate) {
                updateDailyBaseAmount();
                return;
            }

            const currentOption = getSelectedOption();
            const driverId =
                (driverIdInput && driverIdInput.value) ||
                (currentOption && currentOption.dataset.driverId) ||
                '';

            // The Driver is derived from the Duty Slip; without a selected slip
            // there is no driver ID available for this date lookup.
            if (!driverId) {
                updateDailyBaseAmount();
                return;
            }

            const matches = Array.from(dutySlipSelect.options).filter(function (option) {
                return option.value &&
                    String(option.dataset.driverId || '') === String(driverId) &&
                    normalizeDateForInput(option.dataset.date || option.dataset.dutyDate || '') === requestedDate;
            });

            if (!matches.length) {
                // Never carry records or totals from an unrelated Duty Slip/date.
                renderAllowanceExpenseTables(null);
                if (allowanceAmount) allowanceAmount.value = '0.00';
                if (expenseAmount) expenseAmount.value = '0.00';

                // The selected driver's salary can still be previewed for the
                // chosen month, but the form's existing date/slip validation
                // will prevent saving until a matching Duty Slip is selected.
                updateDailyBaseAmount();
                calculateTotalAmount();

                window.alert(
                    'No Duty Slip was found for this driver on the selected Work Date. Please choose a date with a Duty Slip or select the correct Duty Slip.'
                );
                return;
            }

            // Preserve the current slip if it is already the correct match.
            // Otherwise pick the first match; the controller orders slips by
            // descending ID, so this is the latest matching Duty Slip.
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
                populateWorkDetailsFromDutySlip(matchedOption, true);
                loadDutySlipInfo();
                updateDailyBaseAmount();
            }
        }

        if (workDate) {
            workDate.addEventListener('change', handleWorkDateChange);
        }

        // ---------------------------------------------------------------------
        // TOTAL AMOUNT EVENTS
        // ---------------------------------------------------------------------
        amountInputs.forEach(function (input) {
            input.addEventListener('input', calculateTotalAmount);
            input.addEventListener('change', calculateTotalAmount);
        });

        // ---------------------------------------------------------------------
        // NUMERIC INPUT CLEANUP
        // ---------------------------------------------------------------------
        document.querySelectorAll('.decimal-input, .amount-input').forEach(function (input) {
            input.addEventListener('input', function () {
                // Base Amount is an automatically calculated readonly snapshot.
                if (input === baseAmount && baseAmount.readOnly) {
                    return;
                }

                let value = this.value.replace(/[^0-9.]/g, '');
                const firstDot = value.indexOf('.');

                if (firstDot !== -1) {
                    value = value.slice(0, firstDot + 1) +
                        value.slice(firstDot + 1).replace(/\./g, '');
                }

                this.value = value;
            });
        });

        // ---------------------------------------------------------------------
        // FIELD FORMATTING
        // ---------------------------------------------------------------------
        if (sheetNo) {
            sheetNo.addEventListener('blur', function () {
                this.value = this.value.trim().toUpperCase();
            });
        }

        if (remarks) {
            remarks.addEventListener('blur', function () {
                this.value = this.value.trim();
            });
        }

        // ---------------------------------------------------------------------
        // METER EVENTS
        // ---------------------------------------------------------------------
        function handleMeterInput() {
            calculateTotalKm({ preserveIncompleteValue: false });
        }

        if (openingMeter) {
            openingMeter.addEventListener('input', handleMeterInput);
            openingMeter.addEventListener('change', handleMeterInput);
        }

        if (closingMeter) {
            closingMeter.addEventListener('input', handleMeterInput);
            closingMeter.addEventListener('change', handleMeterInput);
        }

        // ---------------------------------------------------------------------
        // INITIAL LOAD
        // ---------------------------------------------------------------------
        loadDutySlipInfo();

        const initiallySelected = getSelectedOption();
        const hasOldInput = form.dataset.hasOldInput === '1';

        if (initiallySelected && initiallySelected.value && !hasOldInput) {
            // Preserve saved values, filling only empty work fields on initial load.
            populateWorkDetailsFromDutySlip(initiallySelected, false);
        }

        // Calculate from meters when possible; otherwise preserve the saved total.
        calculateTotalKm({ preserveIncompleteValue: true });

        // Keep the original Base Amount snapshot on an ordinary edit-page load.
        // If old input contains a changed driver/date after validation failed,
        // calculateDailyBaseAmount will detect that difference and preview the
        // correct new amount without altering the backend's historical record.
        updateDailyBaseAmount();
        calculateTotalAmount();

        // ---------------------------------------------------------------------
        // FORM SUBMIT VALIDATION
        // ---------------------------------------------------------------------
        form.addEventListener('submit', function (event) {
            if (submitting) {
                event.preventDefault();
                return;
            }

            if (sheetNo) sheetNo.value = sheetNo.value.trim().toUpperCase();
            if (remarks) remarks.value = remarks.value.trim();

            if (!dutySlipSelect || !dutySlipSelect.value) {
                event.preventDefault();
                window.alert('Please select a Duty Slip.');
                if (dutySlipSelect) dutySlipSelect.focus();
                return;
            }

            if (!driverIdInput || !driverIdInput.value) {
                event.preventDefault();
                window.alert('The selected Duty Slip does not have an assigned driver.');
                if (dutySlipSelect) dutySlipSelect.focus();
                return;
            }

            const selectedDutySlipOption = getSelectedOption();
            const selectedSlipDate = selectedDutySlipOption
                ? normalizeDateForInput(selectedDutySlipOption.dataset.date || selectedDutySlipOption.dataset.dutyDate || '')
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

            if (!calculateTotalKm({ preserveIncompleteValue: true })) {
                event.preventDefault();
                if (closingMeter) {
                    closingMeter.classList.add('is-invalid');
                    closingMeter.focus();
                }
                window.alert('Closing meter must be greater than or equal to opening meter.');
                return;
            }

            // Final UI calculation. The backend remains authoritative and
            // independently calculates/snapshots Base Amount before saving.
            updateDailyBaseAmount();
            calculateTotalAmount();

            submitting = true;
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating Working Sheet...';
            }
        });
    });
})();
