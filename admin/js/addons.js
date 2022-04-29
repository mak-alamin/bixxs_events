document.addEventListener("DOMContentLoaded", function (event) {
  let addonSelections = document.querySelectorAll(
    ".bixxs_events_addons_selection"
  );

  for (const addonSelection of addonSelections) {
    addonSelection.addEventListener("change", (event) => {
      let value = event.target.value;

      let selectorId = event.target.id;
      const regex = /\[(\d+)\]/;

      let id = selectorId.match(regex)[1];

      let optionsGroup = document.getElementById("bixxs_events_options_" + id);

      let price_person = document.getElementById(
        "bixxs_events_field[" + id + "][price_person]"
      );
      let price_event = document.getElementById(
        "bixxs_events_field[" + id + "][price_event]"
      );
      price_person.readOnly = false;

      price_event.readOnly = false;
      if (value === "dd" || value === "mc") {
        // show options
        optionsGroup.classList.remove("bixxs-events-hidden");
        price_person.readOnly = true;

        price_event.readOnly = true;
      } else {
        // hide options
        optionsGroup.classList.add("bixxs-events-hidden");
      }

      // adjust label for number field
      let price_person_label = price_person.parentNode.childNodes[1];
      if (value == "number") {
        price_person_label.innerText = "Preis pro Kundenauswahl";
      } else {
        price_person_label.innerText = "Preis pro Person";
      }
    });
  }

  let template = document.getElementById("bixxs_events_template_addon");

  document.getElementById("bixxs_events_add_addon").onclick = () => {
    console.log("Addon Testing...");

    let clone = template.cloneNode(true);

    // insert template after last field

    let addon_fields = document.querySelectorAll(
      ".bixxs_events_addon_field_wrapper"
    );
    clone.classList.add("bixxs_events_addon_field_wrapper");
    clone.classList.remove("bixxs-events-hidden");
    clone.id = "";

    let last_addon_field = addon_fields[addon_fields.length - 1];

    let next_number = 1;

    if (last_addon_field)
      next_number = parseInt(last_addon_field.dataset.number) + 1;

    clone.dataset.number = next_number.toString();
    clone.innerHTML = clone.innerHTML.replace(/\[0/g, "[" + next_number);
    clone.innerHTML = clone.innerHTML.replace(/_0/g, "_" + next_number);
    clone.innerHTML = clone.innerHTML.replace(/Feld 0/g, "Feld " + next_number);

    document.getElementById("bixxs_events_add_addon").before(clone);

    // Add eventlistener
    document
      .getElementById("bixxs_events_field[" + next_number + "][selection]")
      .addEventListener("change", (event) => {
        let value = event.target.value;

        let selectorId = event.target.id;
        const regex = /\[(\d+)\]/;

        let id = selectorId.match(regex)[1];

        let optionsGroup = document.getElementById(
          "bixxs_events_options_" + id
        );

        let price_person = document.getElementById(
          "bixxs_events_field[" + id + "][price_person]"
        );
        let price_event = document.getElementById(
          "bixxs_events_field[" + id + "][price_event]"
        );
        price_person.readOnly = false;

        price_event.readOnly = false;
        if (value === "dd" || value === "mc") {
          // show options
          optionsGroup.classList.remove("bixxs-events-hidden");
          price_person.readOnly = true;

          price_event.readOnly = true;
        } else {
          // hide options
          optionsGroup.classList.add("bixxs-events-hidden");
        }

        // adjust label for number field
        let price_person_label = price_person.parentNode.childNodes[1];
        if (value == "number") {
          price_person_label.innerText = "Preis pro Kundenauswahl";
        } else {
          price_person_label.innerText = "Preis pro Person";
        }
      });
  };
});
