jQuery(document).ready(function($) {
    let disabledDays = [];

    let check_change =  function (inputDate){
        let datetimePicker = this;
        const bixxs_events_calendar = jQuery('#bixxs_events_datetimepicker');
        const bixxs_events_notice = jQuery('#bixxs_events_notice');
        let guest_count = parseInt(jQuery('#mlx_guest_selection').attr('data-guests'));
        let date = bixxs_events_calendar.val();
        let product_id = bixxs_events_calendar.attr('data-product');
        let old_value = bixxs_events_calendar.attr('data-old-value');


        if (date === old_value)
            return;

        bixxs_events_calendar.attr('data-old-value', date);

        let data = {
            'action': 'bixxs_events_availability',
            'date': date,
            'product_id': product_id,
        };

        jQuery.ajax({
            url: bixxs_events_availability.ajaxurl,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response == 0){

                    // Disabel days in Calendar
                    disabledDays.push(date);
                    datetimePicker.setOptions({
                            disabledDates: disabledDays,
                        }
                    )

                    // set notice
                    bixxs_events_notice.text('An dem gewünschten Tag sind leider keine Tickets verfügbar.');
                    setTimeout(function (){
                        bixxs_events_notice.text('');
                    }, 5000)

                    // set calendar to old value
                    bixxs_events_calendar.val(old_value);
                } else if ((response - guest_count) < 0 ) {
                    // set notice
                    bixxs_events_notice.text('An dem gewählten Tag sind nur ' + response + ' Tickets verfügbar');
                    setTimeout(function (){
                        bixxs_events_notice.text('');
                    }, 5000)

                    // set calendar to old value
                    bixxs_events_calendar.val(old_value);
                } else {
                    bixxs_events_notice.text('');
                    jQuery('#bixxs_events_available_tickets').text('Verfügbare Tickets: ' + response);
                    jQuery('#bixxs_events_available_tickets').attr('data-available', response);
                }
            },
        });
    };

    const calendar = jQuery('#bixxs_events_datetimepicker');

    let start = calendar.data('start');
    let end = calendar.data('end');

    let [DD, MM, YYYY] = start.split('.');
    MM = parseInt(MM) -1;
    let startDate = new Date(YYYY, MM, DD);
    let today = new Date();

    start = start ? start : false;
    end = end ? end : false;

    if (startDate < today)
        start = 0;

    calendar.datetimepicker({
        format:'DD.MM.YYYY',
        timepicker:false,
        formatDate:'DD.MM.YYYY',
        minDate: start,
        maxDate: end,
        onChangeDateTime: check_change,
    });


});