/**
 * Geolocation callback handler.
 * @author mirteond 
 */
$(function() {
    $.geolocator.geolocate({
        callback: function(data) {
            form = $("#frmlocationsForm-from");
            if (data.latitude == null || data.client !== undefined) {
                if (form.val() == "" && data.client != null) {
                    $("#frmlocationsForm-from").val(data.client.address.city + ", " + data.client.address.country)
                    $("#frmlocationsForm-to").focus();
                } else {
                    $("#frmlocationsForm-from").focus();
                }
            } else {
                $.post("?do=location", {
                    latitude: data.latitude,
                    longitude: data.longitude
                }, function(gData, textStatus) {
                    if (gData.status == 'OK') {
                        if (form.val() == "") {
                            $("#frmlocationsForm-from").val(gData['location']);
                        }
						
                        $("#frmlocationsForm-to").focus();
                    } else {
                        $("#frmlocationsForm-from").focus();
                    }
                }, "json");
            }
        }
    });

});

/**
 * Find directions handler.
 * @author mirteond 
 */
$("#frmlocationsForm-okFindDirections").live("click", function(event) {
    event.preventDefault();
	
    from = $("#frmlocationsForm-from");
    to = $("#frmlocationsForm-to");
    if (nette.validateForm(this)) {
        button = $(this);
        if (!showSpinner(event)) {
            button.val('working...').attr('disabled', 'disabled');
        }
        $.post("?do=directions", {
            from: from.val(),
            to: to.val()
        }, function(data, textStatus) {
            if (data.status == 'OK') {
                text = $('<p>The trip will take <strong>' + formatDuration(data['duration']) + '</strong> to complete and its distance is <strong>' + formatDistance(data['distance']) + '.</strong></p>');
                ol = $('<ol>');
                $.each(data.steps, function(i, el) {
                    ol.append($('<li>').html(el['instructions'] + ' (' + formatDistance(el['distance']) + ')'));
                });
            } else {
                text = $('<p>Can not find the destination or the trip can not be finished.</p>');
            }
			
            textEl = $("#directions-text");
            textEl.text('');
            textEl.append(text);
            textEl.append(ol);

            button.val('Find directions').removeAttr('disabled');
        });
    }
});

//@author Petr Valeš
//@version 20.10.2010
$(function() {
    // coordinates where map will center
    var from = new google.maps.LatLng(41.850033, -87.6500523);
    var map = createMap(from);
    var directionsDisplay = createPanel(map);

    // coordinates of star and end of trip
    var from = $("#frmlocationsForm-from").val();
    var to = $("#frmlocationsForm-to").val();
    showTrip(from, to, directionsDisplay);

});

//@author Petr Valeš
//@version 20.10.2010
function createMap(from)  {
    var myOptions = {
        zoom:7,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: from
    }
    $("#map_canvas").css({
        width: "80%",
        height: "500px",
        float: "left"
    });
    var element = $("#map_canvas");
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    return map;
};

//@author Petr Valeš
//@version 20.10.2010
function createPanel(map)   {
    var directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map);
    $("#panel").css({
        width: "20%",
        height: "500px",
        float: "right"
    });
    directionsDisplay.setPanel(document.getElementById("panel"));
    return directionsDisplay;
}

//@author Petr Valeš
//@version 20.10.2010
function showTrip(from, to, directionsDisplay) {
    var directionsService = new google.maps.DirectionsService();
    var request = {
        origin:from,
        destination:to,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
        }
    });
}


// AJAX spinner appending and hiding
$(function () {
    $('<div id="ajax-spinner"></div>').appendTo("body").ajaxStop(function () {
        $(this).hide().css({
            position: "fixed",
            left: "50%",
            top: "50%"
        });
    }).hide();
});



function showSpinner(event) {
    if (event !== undefined && event.pageX && event.pageY) {
        $("#ajax-spinner").show().css({
            position: "absolute",
            left: event.pageX,
            top: event.pageY
        });
        return true;
    }
    return false;
};

function formatDistance(value) {
    if ((value / 1000) > 1) {
        return Math.round(value / 1000) + ' kilometers';
    }
    return value + ' meters';
};

function formatDuration(value) {
    seconds = value;
    minutes = Math.round(seconds / 60);
    hours = Math.floor(parseFloat(minutes) / 60);
    if (minutes < 1) {
        return seconds + ' seconds';
    }
    if (hours < 1) {
        return minutes + ' minutes';
    }
    return hours + ' hours and ' + (minutes%60) + ' minutes';
};

