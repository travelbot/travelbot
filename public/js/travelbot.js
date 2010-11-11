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
        	showMap = false;
            if (data.status == 'OK') {
                text = $('<p>The trip will take <strong>' + formatDuration(data['duration']) + '</strong> to complete and its distance is <strong>' + formatDistance(data['distance']) + '.</strong></p>');
                ol = $('<ol>');
                $.each(data.steps, function(i, el) {
                    ol.append($('<li>').html(el['instructions'] + ' (' + formatDistance(el['distance']) + ')'));
                });
                showMap = true;
            } else {
                text = $('<p>Can not find the destination or the trip can not be finished.</p>');
            }
			
            textEl = $("#directions-text");
            textEl.text('');
            textEl.append(text);
            textEl.append(ol);

            button.val('Find directions').removeAttr('disabled');
            
            if (showMap) showTrip(from.val(), to.val(), getDirectionsDisplay());
        });
    }
});

//@author Petr Valeš
//@version 20.10.2010
$(function() {
	$("#directions-text ol").hide();
	directionsLink = $("<a>").attr('href', '#').text("Click to show directions.").click(function(event) {
		event.preventDefault();
		$("#directions-text ol").show();
		$(this).hide();
	});
	$("#directions-text").append($("<p>").append(directionsLink));
	
	poisLink = $("<a>").attr('href', '#').text("Click to show POIs in the destination.").click(function(event) {
		event.preventDefault();
		showPOI($("#trip-to").text());
	});
	$("#directions-text").append($("<p>").append(poisLink));
	
	showTrip($("#trip-from").text(), $("#trip-to").text(), getDirectionsDisplay());
});

function getDirectionsDisplay() {
	// coordinates where map will center
    from = new google.maps.LatLng(50.093847, 14.413261);
    map = createMap(from);
    return createPanel(map);
};

//@author Petr Valeš
//@version 20.10.2010
function createMap(from)  {
    var myOptions = {
        zoom:7,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: from
    }
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
    //directionsDisplay.setPanel(document.getElementById("panel"));
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


function showPOI(location) {
	$.post("?do=pois", { location: location }, function(data, textStatus) {
		if (data['status'] != 'FAIL') {
			map.panTo(new google.maps.LatLng(data['latitude'], data['longitude']));
			map.setZoom(15);
			$.each(data['pois'], function(i, el) {
				showMarker(el.latitude, el.longitude, el.icon, '<a href="' + el.url + '">' + el.name + '</a>', el.address, el.types);
			});
		} else {
			
		}
	}, "json");
};

function showMarker(lat, lng, icon, name, address, types) {
      // create a new LatLng point for the marker
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat,lng),
        icon: icon,
        map: map
      });
      // create the tooltip and its text
      infoWindow = new google.maps.InfoWindow();

      // add a listener to open the tooltip when a user clicks on one of the markers
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html='<b>'+name+'</b><br />'+address+'<br />'+types);
        infoWindow.open(map, marker);
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

