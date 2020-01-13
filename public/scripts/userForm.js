/**
 * DOM Ready
 */
$(function () {
    const form = $('.userForm'), inputs = $('.userForm input');
    form.submit(function (event) {
        event.preventDefault();
        inputs.each(validateFormInput);
        const data = aggregateFormData();
        for(let i in data) {
            if(!data[i]) {
                return false;
            }
        }
        const street = data.street.replace(/ /g, "+"), city = data.city.replace(/ /g, "+"),
              country = data.country.replace(/ /g, "+"),
              url = "https://maps.googleapis.com/maps/api/geocode/json?address=" +
              street + ",+" + city + ",+" + country + "&key=AIzaSyAgp110dBvOenjg4D7WxHZIW3LDQxPSjmo";
        $.ajax({
            url: url
        }).done(function (response) {
            if(response.status !== 'OK') {
                alert(response.error_message);
                return false;
            }
            data.lat = response.results[0].geometry.location.lat;
            data.lng = response.results[0].geometry.location.lng;
            $.post(
                "index.php",
                data
            ).done(function (response) {
                if(response === "\"OK\"") {
                    initMap(data.lat, data.lng, 15);
                    let rows  = '';
                    for(let i in data) {
                        rows += "<th>" + data[i] + "</th>";
                    }
                    $("table").append("<tr>" + rows + "</tr>");
                    alert('User added.');
                } else {
                    alert('Adding user failed.');
                }
            });
        });
    });
});

/**
 * Validate input value
 * @returns {boolean}
 */
function validateFormInput() {
    const input = $(this), label = $(this).siblings();
    if(input.val()) {
        input.removeClass("validationError");
        label.removeClass("labelError");
    } else {
        input.addClass("validationError");
        label.addClass("labelError");
        return false;
    }
}

/**
 * Get form data
 * @returns {{firstName: string | jQuery, lastName: string | jQuery, country: string | jQuery, city: string | jQuery, street: string | jQuery}}
 */
function aggregateFormData() {
     return {
        firstName: $('.firstName input').val().trim(),
        lastName: $('.lastName input').val().trim(),
        street: $('.street input').val().trim(),
        city: $('.city input').val().trim(),
        country: $('.country input').val().trim()
    };
}

/**
 * Initialize google maps
 * @param lat
 * @param lng
 * @param zoom
 */
function initMap(lat = -25.344, lng = 131.036, zoom = 4) {
    // The location of Uluru
    const uluru = { lat: lat, lng: lng };
    // The map, default centered at Uluru
    const map = new google.maps.Map(
        document.getElementById('map'), { zoom: zoom, center: uluru });
    // The marker, default positioned at Uluru
    const marker = new google.maps.Marker({ position: uluru, map: map });
}

