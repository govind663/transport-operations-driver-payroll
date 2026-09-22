$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Travel Request Handler
    |--------------------------------------------------------------------------
    */

    function handleTravelRequestChange(isInitialLoad = false) {

        const $travelRequest =
            $('#travel_request_id');

        const $selectedOption =
            $travelRequest.find('option:selected');


        /*
        |--------------------------------------------------------------------------
        | No Travel Request Selected
        |--------------------------------------------------------------------------
        */

        if (!$travelRequest.val()) {

            $('#travel-request-preview').hide();

            $('#preview-request-no').text('-');
            $('#preview-passenger-name').text('-');
            $('#preview-pickup').text('-');
            $('#preview-drop').text('-');


            /*
            |--------------------------------------------------------------------------
            | Clear Auto-Filled Fields
            |--------------------------------------------------------------------------
            | Do not clear old input during initial page load.
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
        | Travel Request Preview Data
        |--------------------------------------------------------------------------
        */

        const requestNo =
            $selectedOption.attr('data-request-no') || '-';

        const passenger =
            $selectedOption.attr('data-passenger') || '-';

        const pickup =
            $selectedOption.attr('data-pickup') || '-';

        const drop =
            $selectedOption.attr('data-drop') || '-';


        /*
        |--------------------------------------------------------------------------
        | Update Travel Request Preview
        |--------------------------------------------------------------------------
        */

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
        | Pickup Time -> Reporting Time
        |--------------------------------------------------------------------------
        */

        const pickupTime =
            (
                $selectedOption.attr('data-pickup-time') || ''
            ).trim();


        /*
        |--------------------------------------------------------------------------
        | Initial Page Load
        |--------------------------------------------------------------------------
        | Preserve existing old() value.
        |--------------------------------------------------------------------------
        */

        const currentReportingTime =
            $('#reporting_time').val();


        if (isInitialLoad) {

            if (
                !currentReportingTime &&
                pickupTime
            ) {

                $('#reporting_time')
                    .val(pickupTime);

            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Manual Travel Request Change
            |--------------------------------------------------------------------------
            | Always use selected Travel Request pickup time.
            |--------------------------------------------------------------------------
            */

            $('#reporting_time')
                .val(pickupTime);

        }


        /*
        |--------------------------------------------------------------------------
        | Pickup Location -> Reporting Location
        |--------------------------------------------------------------------------
        */

        const pickupLocation =
            (
                $selectedOption.attr('data-pickup-location') || ''
            ).trim();


        /*
        |--------------------------------------------------------------------------
        | Initial Page Load
        |--------------------------------------------------------------------------
        | Preserve existing old() value.
        |--------------------------------------------------------------------------
        */

        const currentReportingLocation =
            $('#reporting_location').val();


        if (isInitialLoad) {

            if (
                !currentReportingLocation &&
                pickupLocation
            ) {

                $('#reporting_location')
                    .val(pickupLocation);

            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Manual Travel Request Change
            |--------------------------------------------------------------------------
            | Always use selected Travel Request pickup location.
            |--------------------------------------------------------------------------
            */

            $('#reporting_location')
                .val(pickupLocation);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Travel Request Change Event
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
    | Initial Travel Request Load
    |--------------------------------------------------------------------------
    */

    handleTravelRequestChange(true);


    /*
    |--------------------------------------------------------------------------
    | Initial Driver Preview
    |--------------------------------------------------------------------------
    */

    $('#driver_id').trigger('change');


    /*
    |--------------------------------------------------------------------------
    | Initial Vehicle Preview
    |--------------------------------------------------------------------------
    */

    $('#vehicle_id').trigger('change');


    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    $('#dutyAssignmentForm').on('submit', function () {

        /*
        |--------------------------------------------------------------------------
        | Normalize Reporting Location
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
        | Normalize Remarks
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

        const $button =
            $('#saveDutyAssignmentBtn');


        if ($button.prop('disabled')) {

            return false;

        }


        $button
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Saving...'
            );

    });

});