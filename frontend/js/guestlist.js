function ticket_master_delete_guest(guest) {
  if (bixxs_events_count_guests() < 2) {
    alert("Es muss mindestens ein Gast erfasst werden.");
    return;
  }
  document.getElementById(guest).remove();
  ticket_master_rename_guests();
}

function ticket_master_add_guest() {
  let guest_selection = document.getElementById("mlx_guest_selection");

  let max_guests = parseInt(guest_selection.dataset.maxGuests);

  let available_tickets = 0;
  let timepickerInput = document.getElementById("ticket_master_timepicker");

  console.log("Available TICKETS:!!");
  console.log(window.available_tickets_arr);

  if (timepickerInput) {
    let selectedTime = timepickerInput.value;
    console.log("TIME");
    console.log(selectedTime);

    if (selectedTime && window.available_tickets_arr[selectedTime]) {
      available_tickets = window.available_tickets_arr[selectedTime];
      console.log("Tickets Available");
      console.log(available_tickets);
    } else {
      return;
    }
  } else {
    return;
  }

  let actual_guest_count = bixxs_events_count_guests();
  if (actual_guest_count >= max_guests) {
    alert("Maximale Anzahl erreicht");
    return;
  }

  if (actual_guest_count >= available_tickets) {
    alert(
      "Nicht genügend Tickets verfügbar. Bitte wählen Sie einen anderen Tag aus."
    );
    return;
  }

  let next_guest_number = ticket_master_get_next_guest_number();
  let guests = document.querySelectorAll("#mlx_guest_selection > div");
  let last_guest = guests[guests.length - 1];
  let new_guest = last_guest.cloneNode(true);
  new_guest.id = "mlx_guest_" + next_guest_number;
  let value = new_guest.innerHTML;
  let old_number = ticket_master_get_next_guest_number() - 1;
  let re = new RegExp("([\\[,_])(" + old_number + ")", "g");
  new_guest.innerHTML = new_guest.innerHTML.replace(
    re,
    "$1" + ticket_master_get_next_guest_number()
  );

  // Open details
  new_guest.innerHTML = new_guest.innerHTML.replace(
    /<details(.+)?>/g,
    "<details open>"
  );

  // Remove values
  new_guest.innerHTML = new_guest.innerHTML.replace(
    /(<input.+)value="(.+?)"/g,
    '$1value=""'
  );

  guest_selection.appendChild(new_guest);

  ticket_master_rename_guests();
}

function bixxs_events_count_guests() {
  let guest_count = document.querySelectorAll(
    "#mlx_guest_selection>div"
  ).length;

  return parseInt(guest_count);
}

function ticket_master_rename_guests() {
  let guests = document.querySelectorAll("#mlx_guest_selection > div h2");

  let i = 1;
  for (let guest of guests) {
    guest.innerHTML = "Gast " + i;
    i++;
  }

  // Save it in Data
  document.getElementById("mlx_guest_selection").dataset.guests = i - 1;
}

function ticket_master_get_next_guest_number() {
  let guests = document.querySelectorAll("#mlx_guest_selection > div");

  let last_guest = guests[guests.length - 1];
  let guest_id = last_guest.id.split("_");

  return parseInt(guest_id[guest_id.length - 1]) + 1;
}
