jQuery(document).ready(function($) {
    jQuery('.bixxs_events_datetimepicker').each( function (){

        let start = jQuery(this).data('start');
        let end = jQuery(this).data('end');

        end = end ? end : false;

        jQuery(this).datetimepicker({
            format:'DD.MM.YYYY',
            timepicker:false,
            formatDate:'DD.MM.YYYY',
            minDate: '',
            maxDate: end,
        });
    });


});