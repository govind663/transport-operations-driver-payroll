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
            | Clear fields only after manual change
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
            $selectedOption.attr('data-request-no') || '-';

        const passenger =
            $selectedOption.attr('data-passenger') || '-';

        const pickup =
            $selectedOption.attr('data-pickup') || '-';

        const drop =
            $selectedOption.attr('data-drop') || '-';


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

        let pickupTime =
            $selectedOption.attr('data-pickup-time') || '';

        pickupTime =
            pickupTime.trim();


        /*
        |--------------------------------------------------------------------------
        | Normalize HH:MM:SS -> HH:MM
        |--------------------------------------------------------------------------
        */

        if (pickupTime.length >= 5) {

            pickupTime =
                pickupTime.substring(0, 5);

        }


        /*
        |--------------------------------------------------------------------------
        | Initial Load
        |--------------------------------------------------------------------------
        | Preserve existing Duty Assignment value.
        | If empty, use Travel Request pickup time.
        |--------------------------------------------------------------------------
        */

        const currentReportingTime =
            $('#reporting_time').val().trim();


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
            | Always update from selected Travel Request.
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
        | Initial Load
        |--------------------------------------------------------------------------
        | Preserve existing Duty Assignment value.
        | If empty, use Travel Request pickup location.
        |--------------------------------------------------------------------------
        */

        const currentReportingLocation =
            $('#reporting_location').val().trim();


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
            | Always update from selected Travel Request.
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

        const $submitButton =
            $('#updateDutyAssignmentBtn');


        if ($submitButton.prop('disabled')) {

            return false;

        }


        $submitButton
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> Updating...'
            );

    });

});