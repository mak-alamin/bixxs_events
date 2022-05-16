(function ($) {
  $(document).ready(function () {
    /**
     * Employee Tickets
     */
    $("#tickets_filter_by").on("change", function () {
      let filter_by = $(this).val();

      if (filter_by == "date") {
        $(".filter_by_date").show();
        $("#filter_by_date").prop("required", true);
      } else {
        $("#filter_by_date").prop("required", false);
        $(".filter_by_date").hide();
      }
    });

    $("[name='bixxs_events_employee_tickets_filter']").on(
      "click",
      function (e) {
        e.preventDefault();
        let loader = '<div class="loader"></div>';

        $(".employee_tickets_data").html(loader);

        let filter_date = $("#filter_by_date").val();
        let employee_id = $("#select_employee_filter").val();

        jQuery.ajax({
          method: "GET",
          url: BixxsEventsData.ajaxUrl,
          data: {
            action: "filter_employee_tickets",
            select_employee_filter: employee_id,
            filter_by_date: filter_date,
          },
          success: function (res) {
            $(".employee_tickets_data").html(res);
          },
          error: function (err) {
            console.log(err);
          },
        });
      }
    );

    /**
     * Employee User Roles
     */
    let create_employee = location.search.includes("action=create_employee");

    if (create_employee) {
      jQuery("select#role option").attr("selected", false);
      jQuery("select#role option[value='bixxs_event_employee']").attr(
        "selected",
        "selected"
      );
    }

    /**
     * Confirm Delete a Ticket
     */
    $("a.info-delete").on("click", function () {
      if (!confirm("SIND SIE SICHER ?")) {
        return false;
      }
    });

    /**
     * Media Image Upload
     */
    $(document).on("click", ".js-image-upload", function (e) {
      e.preventDefault();

      var button = $(".js-image-upload");

      var tm_wp_media = wp.media({
        title: "Select Image",

        library: {
          type: "image",
        },

        button: {
          text: "Upload Image",
        },

        multiple: false,
      });

      tm_wp_media.open();

      tm_wp_media.on("select", function () {
        var attachment = tm_wp_media.state().get("selection").first().toJSON();

        button.siblings("input.image-link-input").val(attachment.url);

        $(".uploaded-logo").attr("src", attachment.url);
      });
    });
  });

  /**
   * Ticket Template Options Changing
   */

  let ticket_options = document.querySelectorAll(
    "select#bixxs_events_event_template option"
  );

  /**
   * Ticket Template On Change
   */
  $("select#bixxs_events_event_template").on("change", function () {
    // console.log(ticket_options);

    ticket_options.forEach((element) => {
      element.removeAttribute("selected");
    });
    this.options[this.selectedIndex].setAttribute("selected", "selected");
  });
})(jQuery);
