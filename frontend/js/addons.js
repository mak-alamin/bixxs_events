document.addEventListener("DOMContentLoaded", function(){
    bixxs_events_calculate_sumary();
});

function bixxs_events_calculate_sumary(){

    let guest_count = bixxs_events_count_guests();

    let person_wrapper = document.getElementById('bixxs_events_price_person');
    let addon_wrapper = document.getElementById('bixxs_events_addons_summary');

    let price_person = parseFloat(person_wrapper.dataset.pricePerson);
    let events_label = '';

    // empty addons
    addon_wrapper.innerHTML = '';


    // Set price person

    if (guest_count === 1){
        events_label = person_wrapper.dataset.nameSingular;
    } else {
        events_label = person_wrapper.dataset.namePlural;
    }

    let price = guest_count * price_person;
    let label = '';

    let total = price;

    price = price.toLocaleString('de-DE', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 2
    });

    person_wrapper.innerText = guest_count + ' x ' + events_label + ' ' + price;


    // get price event
    total += parseFloat(document.getElementById('bixxs_events_price_event').dataset.priceEvent);



    // addon fields

    // text field, text long field, number field
    let fields = document.querySelectorAll('.bixxs_events_addons_wrapper > input[type=text], .bixxs_events_addons_wrapper > textarea, .bixxs_events_addons_wrapper > input[type=number]');

    for(let field of fields){
        if (field.value === '')
            continue;

        if (field.type === 'number' && field.value === '0')
            continue;

        // price per event
        if(field.dataset.price !== "0"){

            price = parseFloat(field.dataset.price);
            total += price;

            price = price.toLocaleString('de-DE', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            });

            label = field.parentNode.firstChild.innerText;

            addon_wrapper.innerHTML += '<div>' + label + ': ' + price + '</div>';
        }

        // price per Person
        if (field.dataset.pprice !== "0"){

            let amount = 0;
            if (field.type === 'number'){
                amount = parseInt(field.value)
                price = parseFloat(field.dataset.pprice) * amount;
                console.log(price);
                console.log(amount);
            }else {
                price = parseFloat(field.dataset.pprice) * guest_count;
            }

            total += price;

            price = price.toLocaleString('de-DE', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            });

            label = field.parentNode.firstChild.innerText;
            if (amount){
                addon_wrapper.innerHTML += '<div>' + amount + ' x ' + label + ': ' + price + '</div>';
            }else {
                addon_wrapper.innerHTML += '<div>' + guest_count + ' x ' + label + ': ' + price + '</div>';
            }
        }
    }


    // multiple choice
    fields = document.querySelectorAll('.bixxs_events_addons_wrapper > input[type=checkbox]');

    for(let field of fields) {

        if ( ! field.checked)
            continue;

        // price per event
        if(field.dataset.price !== "0"){
            price = parseFloat(field.dataset.price);
            total += price;

            price = price.toLocaleString('de-DE', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            });

            label = field.parentNode.firstChild.innerText + ' - ' + field.value;
            addon_wrapper.innerHTML += '<div>' + label + ': ' + price + '</div>';
        }

        // price per Person
        if (field.dataset.pprice !== "0"){
            price = parseFloat(field.dataset.pprice) * guest_count;
            total += price;

            price = price.toLocaleString('de-DE', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            });

            label = field.parentNode.firstChild.innerText + ' - ' + field.value;

            addon_wrapper.innerHTML += '<div>' + guest_count + ' x ' + label + ': ' + price + '</div>';
        }

    }

    // dropdown

    fields = document.querySelectorAll('.bixxs_events_addons_wrapper > select');

    for(let field of fields) {

        let option = field.selectedOptions[0];

        // Price per event
        if (option.dataset.price !== '0'){
            price = parseFloat(option.dataset.price);

            total += price;

            price = price.toLocaleString('de-DE', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            });

            label = field.parentNode.firstChild.innerText + ' - ' + option.innerText;

            addon_wrapper.innerHTML += '<div>' + label + ': ' + price + '</div>';
        }

        // price per Person
        if (option.dataset.pprice !== "0"){
            price = parseFloat(option.dataset.pprice) * guest_count;
            total += price;

            price = price.toLocaleString('de-DE', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2
            });

            label = field.parentNode.firstChild.innerText + ' - ' + option.innerText;

            addon_wrapper.innerHTML += '<div>' + guest_count + ' x ' + label + ': ' + price + '</div>';
        }
    }

    let total_sum_wrapper = document.getElementById('bixxs_events_summary');
    total = total.toLocaleString('de-DE', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 2
    });

    total_sum_wrapper.innerText = 'Summe: ' + total;



}
