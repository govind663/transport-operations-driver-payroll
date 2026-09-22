$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Travel Request Change
    |--------------------------------------------------------------------------
    */

    function handleTravelRequestChange(isInitialLoad = false) {

        const travelRequestSelect =
            $('#travel_request_id');

        const selectedOption =
            travelRequestSelect.find('option:selected');


        /*
        |--------------------------------------------------------------------------
        | No Travel Request Selected
        |--------------------------------------------------------------------------
        */

        if (!travelRequestSelect.val()) {

            $('#travel-request-preview').hide();

            $('#preview-request-no').text('-');
            $('#preview-passenger-name').text('-');
            $('#preview-pickup').text('-');
            $('#preview-drop').text('-');


            /*
            |--------------------------------------------------------------------------
            | Clear Auto-Filled Fields
            | Only on manual change, not initial page load
            |--------------------------------------------------------------------------
            */

            if (!isInitialLoad) {

                $('#reporting_time').val('');
                $('#reporting_location').val('');

            }

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Travel Request Preview
        |--------------------------------------------------------------------------
        */

        const requestNo =
            selectedOption.attr('data-request-no') || '-';

        const passenger =
            selectedOption.attr('data-passenger') || '-';

        const pickup =
            selectedOption.attr('data-pickup') || '-';

        const drop =
            selectedOption.attr('data-drop') || '-';


        $('#preview-request-no')
            .text(requestNo);

        $('#preview-passenger-name')
            .text(passenger);

        $('#preview-pickup')
            .text(pickup);

        $('#preview-drop')
            .text(drop);


        $('#travel-request-preview')
            .show();


        /*
        |--------------------------------------------------------------------------
        | Pickup Time
        |--------------------------------------------------------------------------
        */

        let pickupTime =
            selectedOption.attr('data-pickup-time') || '';


        /*
        |--------------------------------------------------------------------------
        | Normalize HH:MM:SS / HH:MM
        |--------------------------------------------------------------------------
        */

        pickupTime =
            pickupTime.trim();

        if (pickupTime.length >= 5) {

            pickupTime =
                pickupTime.substring(0, 5);

        }


        /*
        |--------------------------------------------------------------------------
        | Auto Fill Reporting Time
        |--------------------------------------------------------------------------
        | On initial page load:
        | Preserve old validation value if already present.
        |
        | On manual Travel Request change:
        | Always update from selected Travel Request.
        |--------------------------------------------------------------------------
        */

        const currentReportingTime =
            $('#reporting_time').val();


        if (
            pickupTime &&
            (
                !isInitialLoad ||
                !currentReportingTime
            )
        ) {

            $('#reporting_time')
                .val(pickupTime);

        }


        /*
        |--------------------------------------------------------------------------
        | Pickup Location
        |--------------------------------------------------------------------------
        */

        const pickupLocation =
            selectedOption.attr('data-pickup-location') || '';


        /*
        |--------------------------------------------------------------------------
        | Auto Fill Reporting Location
        |--------------------------------------------------------------------------
        | On initial page load:
        | Preserve old validation value if already present.
        |
        | On manual Travel Request change:
        | Always update from selected Travel Request.
        |--------------------------------------------------------------------------
        */

        const currentReportingLocation =
            $('#reporting_location').val();


        if (
            pickupLocation &&
            (
                !isInitialLoad ||
                !currentReportingLocation
            )
        ) {

            $('#reporting_location')
                .val(pickupLocation);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Travel Request Event
    |--------------------------------------------------------------------------
    */

    $('#travel_request_id').on(
        'change',
        function () {

            handleTravelRequestChange(false);

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Driver Selection Preview
    |--------------------------------------------------------------------------
    */

    $('#driver_id').on('change', function () {

        const selectedText =
            $(this)
                .find('option:selected')
                .text()
                .trim();


        if (!this.value) {

            $('#driver-preview')
                .hide();

            $('#driver-preview-text')
                .text('-');

            return;
        }


        $('#driver-preview-text')
            .text(selectedText);

        $('#driver-preview')
            .show();

    });


    /*
    |--------------------------------------------------------------------------
    | Vehicle Selection Preview
    |--------------------------------------------------------------------------
    */

    $('#vehicle_id').on('change', function () {

        const selectedText =
            $(this)
                .find('option:selected')
                .text()
                .trim();


        if (!this.value) {

            $('#vehicle-preview')
                .hide();

            $('#vehicle-preview-text')
                .text('-');

            return;
        }


        $('#vehicle-preview-text')
            .text(selectedText);

        $('#vehicle-preview')
            .show();

    });


    /*
    |--------------------------------------------------------------------------
    | Reporting Location Formatting
    |--------------------------------------------------------------------------
    */

    $('#reporting_location').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });


    /*
    |--------------------------------------------------------------------------
    | Remarks Formatting
    |--------------------------------------------------------------------------
    */

    $('#remarks').on('blur', function () {

        this.value = this.value
            .replace(/\s+/g, ' ')
            .trim();

    });


    /*
    |--------------------------------------------------------------------------
    | Trigger Existing Selections
    |--------------------------------------------------------------------------
    */

    handleTravelRequestChange(true);

    $('#driver_id').trigger('change');

    $('#vehicle_id').trigger('change');


    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    $('#dutyAssignmentForm').on('submit', function () {

        /*
        |--------------------------------------------------------------------------
        | Reporting Location
        |--------------------------------------------------------------------------
        */

        $('#reporting_location').val(

            $('#reporting_location')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Remarks
        |--------------------------------------------------------------------------
        */

        $('#remarks').val(

            $('#remarks')
                .val()
                .replace(/\s+/g, ' ')
                .trim()

        );


        /*
        |--------------------------------------------------------------------------
        | Prevent Double Submit
        |--------------------------------------------------------------------------
        */

        const button =
            $('#saveDutyAssignmentBtn');


        button
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Saving...'
            );

    });

});