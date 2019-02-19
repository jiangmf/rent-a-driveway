// Get the label given a jquery object
function getLabel(field) {
    return $("label[for='" + field.attr('id') + "']");
}

// Describes the validation rules and error messages different validators 
var validators = {
    emptyString: function(field) {
        // check if field value is empty string
        if (field.val() == "") {
            return "Please enter your " + getLabel(field).text().toLowerCase() + ".";
        }
        return true;
    },
    email: function(field) {
        // check if field value matches email regex
        if (!/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/.test(field.val())) {
            return "Please enter a valid email address.";
        }
        return true;
    },
    phone: function(field) {
        // check if field value matches phone number format
        if (!/^\d{3}(-?)\d{3}(-?)\d{4}$/.test(field.val())) {
            return "Please enter a valid phone number.";
        }
        return true;
    },
    repeatpassword: function(field) {
        // check if repeat password is the same as password
        if (field.val() !== $("#password").val()) {
            return "Your passwords do not match.";
        }
        return true;
    },
    longlat: function(field) {
        // check if longitude and latitude values are properly formatted
        if (!/^-?[0-9]{1,3}(?:\.[0-9]{1,20})?$/.test(field.val())) {
            return "Please enter a valid " + getLabel(field).text().toLowerCase() + ".";
        }
        return true;
    },

}

// Describes which validators should be run for each field
var validatorMap = {
    // Registration Form
    'firstname': [validators.emptyString],
    'lastname': [validators.emptyString],
    'email': [validators.emptyString, validators.email],
    'password': [validators.emptyString],
    'repeatpassword': [validators.emptyString, validators.repeatpassword],
    'phone': [validators.emptyString, validators.phone],
    'terms': [validators.terms],
    // Submission Form
    'title': [validators.emptyString],
    'longitude': [validators.emptyString, validators.longlat],
    'latitude': [validators.emptyString, validators.longlat],
    'description': [validators.emptyString],

}
// Function to run when registration, submission, and login forms are submitted
function validate(form) {
    var errors = {};
    // Run specified validators for each field in the form
    form.find("[name]").each(function() {
        var name = $(this).attr('name')
        if (name in validatorMap) {
            for (var i = validatorMap[name].length - 1; i >= 0; i--) {
                var result = validatorMap[name][i]($(this));
                // If there's an error, add it to the errors list, which will be
                // used to display the errors later
                if (result !== true) {
                    errors[name] = result;
                }
            }
        }
    })
    // if no errors, submit
    if ($.isEmptyObject(errors)) {
        return true;
    } else {
        // For each field, display the error message that's stored and make the label red
        form.find("[name]").each(function() {
            var name = $(this).attr('name');
            if (name in errors) {
                getLabel($(this)).addClass("error");
                $(this).next("span.error").remove();
                $(this).after("<span class='error'>" + errors[name] + "</span>")
            } else {
                // Remove the error message if it previous exists
                getLabel($(this)).removeClass("error");
                $(this).next("span.error").remove();
            }
        });
        event.preventDefault();
    }
}
// HTML5 get location
function getLocation(callback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(callback);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
if (typeof mapboxgl !== 'undefined') {
    mapboxgl.accessToken = 'pk.eyJ1IjoiaWNlYW5kZWxlIiwiYSI6ImNqb2F1dXFoazF3Ynozdm5sZDBtcW1xbnQifQ.MvnPlcX-tgVTqx-Vd-is-w';
}
// Map utility functions
// Move map to marker location
function flyToMarker(map, currentFeature) {
    map.flyTo({
        center: [currentFeature.geometry.coordinates[0] + 0.003, currentFeature.geometry.coordinates[1]],
        zoom: 15
    });
}
// Generate Pop up window for marker
function createPopUp(map, currentFeature) {
    var popUps = document.getElementsByClassName('mapboxgl-popup');
    // Check if there is already a popup on the map and if so, remove it
    if (popUps[0]) popUps[0].remove();

    var popup = new mapboxgl.Popup({ closeOnClick: false, anchor: 'left', offset: [20, 0] })
        .setLngLat(currentFeature.geometry.coordinates)
        .setHTML(`<div class="tile">
                <a href="parking.php"><img src="${currentFeature.properties.img}" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php?id=${currentFeature.properties.id}">${currentFeature.properties.title}</a> - 
                    <span class="price">${currentFeature.properties.price}</span></h3>
                    <p class="location">${currentFeature.properties.address} (${+currentFeature.properties.distance.toFixed(2)}km away)</p>
                    <p class="rating">${getStars(currentFeature.properties.rating)}</p>
                </div>
            </div>`)
        .addTo(map);
}
// Generate results map from geojson data
function generateResultsMap() {
    var long = + new URL(window.location.href).searchParams.get("longitude");
    var lat = + new URL(window.location.href).searchParams.get("latitude");
    console.log(long, lat);
    var map = new mapboxgl.Map({
        // container id specified in the HTML
        container: 'results-map',
        // style URL
        style: 'mapbox://styles/mapbox/light-v9',
        // initial position in [lon, lat] format
        center: [
            long, lat
        ],
        // initial zoom
        zoom: 13
    });
    map.on('load', function(e) {
        // Add the data to your map as a layer
        map.addSource('places', {
            type: 'geojson',
            data: data
        });

    });
    data.features.forEach(function(marker) {
        // Create a div element for the marker
        var el = document.createElement('div');
        // Add a class called 'marker' to each div
        el.className = 'marker';
        // By default the image for your custom marker will be anchored
        // by its center. Adjust the position accordingly
        // Create the custom markers, set their position, and add to map
        new mapboxgl.Marker(el, { offset: [0, -13] })
            .setLngLat(marker.geometry.coordinates)
            .addTo(map);
        el.addEventListener('click', function(e) {
            // 1. Fly to the point
            flyToMarker(map, marker);
            // 2. Close all other popups and display popup for clicked store
            createPopUp(map, marker);
        });
    });
}

// Generate results map from geojson data
function generateParkingMap() {
    var map = new mapboxgl.Map({
        // container id specified in the HTML
        container: 'parking-map',
        // style URL
        style: 'mapbox://styles/mapbox/light-v9',
        // initial position in [lon, lat] format
        center: data.features[0].geometry.coordinates,
        // initial zoom
        zoom: 14
    });
    map.on('load', function(e) {
        // Add the data to your map as a layer
        map.addSource('places', {
            type: 'geojson',
            data: data
        });

    });
    data.features.forEach(function(marker) {
        // Create a div element for the marker
        var el = document.createElement('div');
        // Add a class called 'marker' to each div
        el.className = 'marker';
        // By default the image for your custom marker will be anchored
        // by its center. Adjust the position accordingly
        // Create the custom markers, set their position, and add to map
        new mapboxgl.Marker(el, { offset: [0, -13] })
            .setLngLat(marker.geometry.coordinates)
            .addTo(map);
    });
}
// Given a number, get the HTML containing font awesome stars
function getStars(rating) {
    var full = "<i class='fas fa-star'></i>".repeat(Math.floor(rating));
    var half = rating % 1 == 0 ? "" : "<i class='fas fa-star-half-alt'></i>";
    var empty = "<i class='far fa-star'></i>".repeat(Math.floor(5 - rating));
    return full + half + empty;
}
// Generate results list from geojson data
function generateResultsList() {
    for (var i = 0; i < data.features.length; i++) {
        var properties = data.features[i].properties;
        $("#results-list").append(`
            <div class="tile">
                <a href="parking.php"><img src="${properties.img}" alt=""></a>
                <div class="tile-content">
                    <h3><a href="parking.php?id=${properties.id}">${properties.title}</a> - 
                    <span class="price">${properties.price}</span></h3>
                    <p class="location">${properties.address} (${+properties.distance.toFixed(2)}km away)</p>
                    <p class="rating">${getStars(properties.rating)}</p>
                    <p class="description">${properties.description}</p>
                </div>
            </div>`)
    }

    $("h2 em").html(data.features.length);
    var params = ['location', 'longitude', 'latitude', 'distance', 'title', 'rating', 'fromPrice', 'toPrice'];
    for (var i=0; i<params.length; i++) {
        $("#" + params[i]).val(new URL(window.location.href).searchParams.get(params[i]));
    }
}

function generateParkingDetails() {
    var details = data.features[0].properties;
    $("h1").text(details.title);
    $("#listing-details .rating").html(getStars(details.rating));
    $(".fas.fa-map-marker-alt").after(" " + details.address);
    $(".fas.fa-user").after(" " + details.user.first_name + " " + details.user.last_name);
    $(".far.fa-envelope").after(" " + details.user.email);
    $(".fas.fa-mobile-alt").after(" " + details.user.phone);
    $(".description").after(" " + details.description);
    for (var i = 0; i < details.reviews.length; i++) {
        var review = details.reviews[i];
        $("#comments-list").append(`<div class="tile">
                <div class="tile-content">
                    <h4><span class="rating">${getStars(review.rating)}</span> ${review.title}</h4>
                    <p>${review.description}</p>
                </div>
            </div>`)
    }
    $("form .rating").on("click", "i", function(){
        var i = $(this).index() + 1;
        $("#rating").val(i);
        $("form .rating").html(getStars(i));
    })
}

// call back functions to populate the fields with geolocation data
function searchFormLocation(position) {
    $("#location").val(position.coords.longitude.toFixed(7) + ", " + position.coords.latitude.toFixed(7)).trigger('input');
}

function submissionFormLocation(position) {
    $("#longitude").val(position.coords.longitude.toFixed(7));
    $("#latitude").val(position.coords.latitude.toFixed(7));
}

$(function() {
    // attach validate function to submit event
    $("#registration-form, #submission-form, #login-form").submit(function(event) {
        validate($(this), event);
    });
    // display and hide message
    $("#notification").css("opacity", 1)
    setTimeout(function() { $("#notification").css("opacity", 0) }, 3000)

    $("#location").on("input", function(){
        $("#longitude").val();
        $("#longitude").val(+$(this).val().split(",")[0]);
        $("#latitude").val(+$(this).val().split(",")[1]);
    })
})