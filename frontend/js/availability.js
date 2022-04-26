jQuery(document).ready(function ($) {
  let disabledDays = [];

  function getDayName(selectedDay) {
    let day = "donnerstag";
    switch (selectedDay) {
      case 0:
        day = "freitag";
        break;
      case 1:
        day = "samstag";
        break;
      case 2:
        day = "sonntag";
        break;
      case 3:
        day = "montag";
        break;
      case 4:
        day = "dienstag";
        break;
      case 5:
        day = "mittwoch";
        break;
      default:
        day = "donnerstag";
        break;
    }

    return day;
  }

  let check_change = function (inputDate) {
    let datetimePicker = this;
    const bixxs_events_calendar = jQuery("#bixxs_events_datetimepicker");
    const bixxs_events_notice = jQuery("#bixxs_events_notice");
    let guest_count = parseInt(
      jQuery("#mlx_guest_selection").attr("data-guests")
    );
    let date = bixxs_events_calendar.val();

    let product_id = bixxs_events_calendar.attr("data-product");
    let old_value = bixxs_events_calendar.attr("data-old-value");

    const timePickerWrap = $("#reserve_timeslot_wrap");
    const ticket_master_timepicker = $("#ticket_master_timepicker");

    if (date === old_value) {
      return;
    }

    bixxs_events_calendar.attr("data-old-value", date);

    let data = {
      action: "bixxs_events_availability",
      date: date,
      product_id: product_id,
    };

    jQuery.ajax({
      url: bixxs_events_availability.ajaxurl,
      type: "POST",
      data: data,
      success: function (response) {
        date = bixxs_events_calendar.val();
        // console.log(response);
        bixxs_events_notice.text("");

        let options = "";

        if (
          typeof response.available_tickets !== "undefined" &&
          response.available_tickets &&
          typeof response.timeslots !== "undefined" &&
          response.timeslots
        ) {
          let available_tickets_arr = [];
          response.timeslots.forEach(function (time, index) {
            console.log(Object.keys(response.available_tickets));

            if (
              Object.keys(response.available_tickets).indexOf(
                index.toString()
              ) > -1
            ) {
              available_tickets_arr[time] = response.available_tickets[index];
              options +=
                '<option value="' +
                time +
                '">' +
                time +
                "  Verfügbare Tickets: " +
                response.available_tickets[index] +
                "</option>";
            }
          });
        } else {
          let date_arr = date.split(".");

          let d = new Date(date_arr[2], date_arr[1], date_arr[0]);

          let dayName = getDayName(d.getDay());
          let selectedDay = response.timeslots[dayName];

          if (undefined === selectedDay || selectedDay.length <= 0) {
            options += "<option>No Timeslot founds</option>";
          } else {
            selectedDay.forEach(function (time, index) {
              options +=
                '<option value="' +
                time +
                '">' +
                time +
                "  Verfügbare Tickets: " +
                response.tickets[dayName][index] +
                "</option>";
            });
          }
        }

        ticket_master_timepicker.find("option").not("[data-default]").remove();

        ticket_master_timepicker.append(options);
        timePickerWrap.css("display", "block");
      },
      error: function (err) {
        console.log(err);
      },
    });
  };

  const calendar = jQuery("#bixxs_events_datetimepicker");

  let start = calendar.data("start");
  let end = calendar.data("end");

  let [DD, MM, YYYY] = start.split(".");
  MM = parseInt(MM) - 1;
  let startDate = new Date(YYYY, MM, DD);
  let today = new Date();

  start = start ? start : false;
  end = end ? end : false;

  if (startDate < today) start = 0;

  calendar.datetimepicker({
    format: "DD.MM.YYYY",
    timepicker: false,
    formatDate: "DD.MM.YYYY",
    minDate: start,
    maxDate: end,
    onChangeDateTime: check_change,
  });
});
