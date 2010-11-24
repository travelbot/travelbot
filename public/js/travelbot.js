var travelbot = {
	map: null,
	directionsRenderer: null,
	markers: new Array(),
	
	getMap: function() {
		if (travelbot.map == null) {
			map = new google.maps.Map(document.getElementById("map"), {
		        zoom:7,
		        mapTypeId: google.maps.MapTypeId.ROADMAP,
		        center: new google.maps.LatLng(50.093847, 14.413261)
	    	});
	    	travelbot.map = map;
    	}
    	return travelbot.map;
	},
	
	getDirectionsRenderer: function() {
		if (travelbot.directionsRenderer == null) {
	    	directionsRenderer = new google.maps.DirectionsRenderer();
		    directionsRenderer.setMap(travelbot.getMap());
		    travelbot.directionsRenderer = directionsRenderer;
	    }
	    return travelbot.directionsRenderer;
	},
	
	showTrip: function(from, to) {
	    service = new google.maps.DirectionsService();
	    service.route({
	        origin:from,
	        destination:to,
	        travelMode: google.maps.DirectionsTravelMode.DRIVING
	    }, function(response, status) {
	        if (status == google.maps.DirectionsStatus.OK) {
	        	travelbot.getDirectionsRenderer().setMap(null);
	        	travelbot.getDirectionsRenderer().setMap(travelbot.map);
	            travelbot.getDirectionsRenderer().setDirections(response);
	        }
	    });
	},
	
	showPoi: function(latitude, longitude, icon, name, address, types) {
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(latitude, longitude),
			icon: icon,
			map: travelbot.map
		});
		travelbot.markers.push(marker);
		infoWindow = new google.maps.InfoWindow();
		
		
		// add a listener to open the tooltip when a user clicks on one of the markers
		google.maps.event.addListener(marker, 'click', function() {
			infoWindow.setContent('<b>'+name+'</b><br />'+address+'<br />'+types);
			infoWindow.open(travelbot.map, marker);
		});
	},
	
	loadPois: function(location) {
		$.post(basePath + "/ajax/?do=pois", { location: location }, function(data, textStatus) {
			if (data['status'] != 'FAIL') {
				travelbot.getMap().panTo(new google.maps.LatLng(data['latitude'], data['longitude']));
				travelbot.getMap().setZoom(15);
				$.each(data['pois'], function(i, el) {
					travelbot.showPoi(el.latitude, el.longitude, el.icon, '<a href="' + el.url + '">' + el.name + '</a>', el.address, el.types);
				});
				return data['pois'];
			} else {
				
			}
		}, "json");
	},
	
	clearPois: function() {
		for (i in travelbot.markers) {
			travelbot.markers[i].setMap(null);
		}
	},
	
	loadEvents: function(arrival, event) {
		events = $('#events');
		if (events.children().size() == 0) {
			showSpinner(event);
			$.post(basePath + "/ajax/?do=events", {location: arrival}, function(data, textStatus) {
				if (textStatus == 'success') {
					events.html(data['events']);
					travelbot.showEvents($(data['events']));
				}
			});
		} else {
			travelbot.showEvents(events.children('.event'));
		}
	},
	
	//showEvents on the map
	showEvents: function(events) {
		$.each(events, function(i, el) {
			event = $(el);
			if (i == 0) {
				travelbot.getMap().panTo(new google.maps.LatLng(event.attr('data-latitude'), event.attr('data-longitude')));
				travelbot.getMap().setZoom(12);
			}
			travelbot.showPoi(parseFloat(event.attr('data-latitude')) + (Math.random()/100), parseFloat(event.attr('data-longitude')) + (Math.random()/100), null, event.attr('data-title'), event.attr('data-venue'), event.attr('data-date'));
		});
	}
}

$(function() {
	travelbot.getMap();
	
	if (isTripPage) {
		departure = $('span.trip.departure').text();
		arrival = $('span.trip.arrival').text();
		travelbot.showTrip(departure, arrival);
		
		directions = $('#directions');
		directions.hide();
		directions.before(createUnwrapLink('Directions', function() {
			directions.show();
		}, function() {
			directions.hide();
		}));
		
		$("#showing-pois").hide();
		
		var shownEvents = false;
		
		events = $('#events');
		events.before(createUnwrapLink('Events', function(event) {
			$('#events').show();
			travelbot.clearPois();
			travelbot.loadEvents(arrival, event);
		}, function() {
			$('#events').hide();
			travelbot.clearPois();
			travelbot.showTrip(departure, arrival);
		}));
		
		article = $('#article');
		article.before(createUnwrapLink('Article', function(event) {
			if (article.children().size() == 0) {
				showSpinner(event);
				$.post(basePath + "/ajax/?do=article", {location: arrival}, function(data, textStatus) {
					if (textStatus == 'success') {
						article.html(data['article']);
					}
				}, 'json');
			} else {
				article.show();
			}
		}, function() {
			article.hide();
		}));
		
		flights = $('#flights');
		flights.before(createUnwrapLink('Flights', flights));
	} else {
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
                $.post(basePath + "/ajax/?do=location", {
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
	}
});

$("#pois-link").click(function(event) {
	event.preventDefault();
	link = $(this);
	
	if (link.hasClass('plus')) {
		showSpinner(event);
		travelbot.loadPois($('span.trip.arrival').text());
		$("#showing-pois").show();
		link.removeClass('plus');
		link.addClass('minus');
	} else {
		travelbot.clearPois();
		travelbot.showTrip($('span.trip.departure').text(), $('span.trip.arrival').text());
		$("#showing-pois").hide();
		link.removeClass('minus');
		link.addClass('plus');
	}
});

$("#frmlocationsForm-from, #frmlocationsForm-to").keyup(function() {
	$("#frmlocationsForm-okSubmit").attr('disabled', 'disabled');
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
        $.post(basePath + "/ajax/?do=directions", {
            from: from.val(),
            to: to.val()
        }, function(data, textStatus) {
        	showMap = false;
        	
        	panel = $("#content");
        	
            if (data.status == 'OK') {
                ol = $('<ol>');
                $.each(data.steps, function(i, el) {
                    ol.append($('<li>').html(el['instructions'] + ' (' + formatDistance(el['distance']) + ')'));
                });
                
                panel.html('');
                panel.append($('<p>The trip will take <strong>' + formatDuration(data['duration']) + '</strong> to complete and its distance is <strong>' + formatDistance(data['distance']) + '.</strong></p>'));
                panel.append($("<p>If you're satisfied with the trip, click on <strong>Save trip</strong> for further modifications.</p>"));
                
                panel.append(createUnwrapLink('Directions', ol));
                panel.append(ol);
                
                travelbot.showTrip(from.val(), to.val());
				$("#frmlocationsForm-okSubmit").removeAttr('disabled');
            } else {
                panel.html('<p>Error occured. Please try again.</p>');
            }

            button.val('Find directions').removeAttr('disabled');
        });
    }
});

// --------------------------------------
// --------------------------------------
// --------------------------------------


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

function createUnwrapLink(label, callback, undoCallback) {
	return $('<a class="unwrap plus" href="#">' + label + '</a>').click(function(event) {
		event.preventDefault();
		link = $(this);
		if (link.hasClass('plus')) {
			link.removeClass('plus');
			link.addClass('minus');
			callback(event);
		} else {
			link.removeClass('minus');
			link.addClass('plus');
			undoCallback(event);
		}
	});
}


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
    return value + '&nbsp;meters';
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
    return hours + '&nbsp;hours and ' + (minutes%60) + '&nbsp;minutes';
};

