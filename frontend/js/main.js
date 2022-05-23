jQuery(document).ready(function () {
  let bixxs_employee_meta = jQuery("ul.wc-item-meta li .wc-item-meta-label");

  jQuery.each(bixxs_employee_meta, function (i, meta) {
    if (jQuery(meta).text().includes("bixxs_events_item_employee")) {
      jQuery(meta).closest("li").hide();
    }
  });

  // jQuery.datetimepicker.setDateFormatter({
  //   parseDate: function (date, format) {
  //       var d = moment(date, format);
  //       return d.isValid() ? d.toDate() : false;
  //   },

  //   formatDate: function (date, format) {
  //       return moment(date).format(format);
  //   },
  // });

  jQuery.datetimepicker.setDateFormatter("moment");
});
