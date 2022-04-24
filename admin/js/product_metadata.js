jQuery(document).ready(function () {
  (function ($) {
    let dateTimeOptions = {
      format: "DD.MM.YYYY", // Time Format: h:mm a
      formatDate: "DD.MM.YYYY",
      timepicker: false,
    };
    jQuery("#bixxs_events_start_time").datetimepicker(dateTimeOptions);
    jQuery("#bixxs_events_end_time").datetimepicker(dateTimeOptions);

    $(document).on("click", ".ui-tab .tab-title", function () {
      if ($(this).parents(".ui-tab").hasClass("animating")) {
        return;
      }

      $(this).parents(".ui-tab").addClass("animating");
      $(this)
        .parents(".ui-tabs")
        .find(".ui-tab:not(.animating) .tab-content")
        .slideUp(300);

      let $tabContent = $(this).siblings(".tab-content");
      $tabContent.slideToggle(300, function () {
        $(this).parents(".ui-tab").removeClass("animating");
      });
    });

    $("#start_time").datetimepicker(dateTimeOptions);
    $("#end_time").datetimepicker(dateTimeOptions);

    $(".ticketmaster-add-timeslots").on("click", function () {
      let day_key = $(this).attr("data-day");

      let timeslotHTML = '<div class="time-slot-wrap">';
      timeslotHTML +=
        '<input type="number" step="1" min="0" max="23" class="hour_time_input" placeholder="HH">';
      timeslotHTML +=
        '<input type="number" step="5" min="0" max="59" class="minute_time_input" placeholder="mm">';
      timeslotHTML +=
        '<input type="number" name="timeslots_selection[tickets][' +
        day_key +
        '][]" step="1" min="1"  class="" placeholder="Tickets">';
      timeslotHTML +=
        '<input type="hidden" name="timeslots_selection[timeslots][' +
        day_key +
        '][]">';
      timeslotHTML +=
        '<button type="button" class="button button-outline-secondary remove-time-slot"> <i class="dashicons dashicons-no"></i> </button>';
      timeslotHTML += "</div>";

      $(".timeslots-wrap-" + day_key).append(timeslotHTML);
    });

    $(document).on("click", ".remove-time-slot", function () {
      $(this).parents(".time-slot-wrap").remove();
    });

    $(document).on(
      "input change",
      '.time-slot-wrap input[type="number"]',
      function () {
        if ($(this).val().toString().length == 1) {
          $(this).val("0" + parseInt($(this).val(), 10));
        } else if ($(this).val().toString().length > 2) {
          $(this).val($(this).val().toString().substr(1));
        }

        let $timeSlot = $(this).parents(".time-slot-wrap");
        let hour = $timeSlot.find(".hour_time_input").val();
        let minute = $timeSlot.find(".minute_time_input").val();

        if (minute.toString().length === 1) {
          minute = "0" + minute;
        }
        if (hour.toString().length === 1) {
          hour = "0" + hour;
        }

        let timeText = hour + ":" + minute;

        $(this)
          .parents(".time-slot-wrap")
          .find('input[type="hidden"]')
          .val(timeText);
      }
    );
  })(jQuery);
});
