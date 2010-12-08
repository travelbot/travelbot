var travelbot = {
    map: null,
    directionsRenderer: null,
    markers: new Array(),
    center: null,
    zoom: null,
	
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

    clearMap: function() {
        map = new google.maps.Map(document.getElementById("map"), {
            zoom:7,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: new google.maps.LatLng(50.093847, 14.413261)
        });
        travelbot.map = map;
    },

    showTrip: function(path) {
        polylineOptions = {
            clickable: true,
            geodesic:	true,
            map: travelbot.map,
            path: path,
            strokeColor: "#00009F",
            strokeOpacity: 0.6,
            strokeWeight: 4,
            zIndex: 10
        };
        new google.maps.Polyline(polylineOptions);

        travelbot.addMarker(polylineOptions.path.getAt(0).lat(), polylineOptions.path.getAt(0).lng(), "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|0000DF|000000");
        last = polylineOptions.path.getLength() - 1;
        travelbot.addMarker(polylineOptions.path.getAt(last).lat(), polylineOptions.path.getAt(last).lng(), "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=B|0000DF|000000");

        travelbot.center = polylineOptions.path.getAt(0);
        travelbot.centerMap();
    },

    centerMap: function() {
        travelbot.map.panTo(travelbot.center);
        travelbot.map.setZoom(8);
    },

    loadTrip: function(from, to) {
        path = new google.maps.MVCArray();
        $.each($('#directions .step'), function(i, el) {
            step = $(el);
            var points;
            points = decodeLine(step.attr('data-polyline'));
            for(j=0; j < points.length; j++) {
                point = new google.maps.LatLng(points[j][0], points[j][1], true);
                path.push(point);
            }
        });
        travelbot.showTrip(path);
    },

    addMarker: function(latitude, longitude, icon) {
        return new google.maps.Marker({
            position: new google.maps.LatLng(latitude, longitude),
            icon: icon,
            map: travelbot.map
        });
    },
	
    showPoi: function(latitude, longitude, icon, name, address, types) {
        var marker = travelbot.addMarker(latitude, longitude, icon);
        travelbot.markers.push(marker);
        infoWindow = new google.maps.InfoWindow();
		
		
        // add a listener to open the tooltip when a user clicks on one of the markers
        google.maps.event.addListener(marker, 'click', function() {
            infoWindow.setContent('<b>'+name+'</b><br />'+address+'<br />'+types);
            infoWindow.open(travelbot.map, marker);
        });
    },
	
    loadPois: function(location) {
        $.post(basePath + "/ajax/?do=pois", {
            location: location
        }, function(data, textStatus) {
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
            $.post(basePath + "/ajax/?do=events", {
                location: arrival,
                tripId: $('#tripid').text()
            }, function(data, textStatus) {
                if (textStatus == 'success') {
                    events.html(data['events']);
                    travelbot.showEvents($(data['events']).children('.event'));
                    $('#frmeventsForm-tripId').val();
                }
            });
        } else {
            travelbot.showEvents($('.event'));
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
        tripId = $("#tripid");
        travelbot.loadTrip(departure, arrival);
		
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
            travelbot.loadTrip(departure, arrival);
        }));
		
        article = $('#article');
        article.before(createUnwrapLink('Article', function(event) {
            if (article.children().size() == 0) {
                showSpinner(event);
                $.post(basePath + "/ajax/?do=article", {
                    location: arrival
                }, function(data, textStatus) {
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
        flights.before(createUnwrapLink('Flights', function(event) {
            if (flights.children().size() == 0) {
                showSpinner(event);
                $.post(basePath + "/ajax/?do=flights", {
                    departure: departure,
                    arrival: arrival
                }, function(data, textStatus) {
                    if (textStatus == 'success') {
                        flights.html(data['flights']);
                    }
                });
            }
			
            flights.show();
        }, function() {
            flights.hide();
        }));

        hotels = $('#hotels');
        hotels.before(createUnwrapLink('Hotels', function(hotel) {
            if (hotels.children().size() == 0) {
                showSpinner(hotel);
                $.post(basePath + "/ajax/?do=hotels", {
                    arrival: arrival
                }, function(data, textStatus) {
                    if (textStatus == 'success') {
                        hotels.html(data['hotels']);
                    }
                });
            }
            hotels.show();
        }, function() {
            hotels.hide();
        }));
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
        //        travelbot.loadTrip($('span.trip.departure').text(), $('span.trip.arrival').text());
        travelbot.centerMap();
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

    travelbot.clearMap();
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
            //            showMap = false;
        	
            panel = $("#content");
        	
            if (data.status == 'OK') {
                ol = $('<ol id="directions">');
                mvcArrayPath = new google.maps.MVCArray();
                $.each(data.steps, function(i, el) {
                    ol.append($('<li>').html(el['instructions'] + ' (' + formatDistance(el['distance']) + ')'));

                    var points;
                    points = decodeLine(el['polyline']);
                    for(i=0; i < points.length; i++) {
                        point = new google.maps.LatLng(points[i][0], points[i][1], true);
                        mvcArrayPath.push(point);
                    }

                });
                
                travelbot.showTrip(mvcArrayPath);

                panel.html('');
                panel.append($('<p>The trip will take <strong>' + formatDuration(data['duration']) + '</strong> to complete and its distance is <strong>' + formatDistance(data['distance']) + '.</strong></p>'));
                panel.append($("<p>If you're satisfied with the trip, click on <strong>Save trip</strong> for further modifications.</p>"));
                
                ol.hide();
                panel.append(createUnwrapLink('Directions', function() {
                    ol.show();
                }, function() {
                    ol.hide();
                }));
				
                panel.append(ol);
                
                $("#frmlocationsForm-okSubmit").removeAttr('disabled');
            } else {
                panel.html('<p>Error occured. Please try again.</p>');
            }

            button.val('Find directions').removeAttr('disabled');
        });
    }
});

$("form.ajax").live("submit",function (e) {
	showSpinner(e);
	$(this).ajaxSubmit(e);
});

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
}

function formatDistance(value) {
    if ((value / 1000) > 1) {
        return Math.round(value / 1000) + ' kilometers';
    }
    return value + '&nbsp;meters';
}

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
}

function decodeLine (encoded) {
    var len = encoded.length;
    var index = 0;
    var array = [];
    var lat = 0;
    var lng = 0;

    while (index < len) {
        var b;
        var shift = 0;
        var result = 0;
        do {
            b = encoded.charCodeAt(index++) - 63;
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);
        var dlat = ((result & 1) ? ~(result >> 1) : (result >> 1));
        lat += dlat;

        shift = 0;
        result = 0;
        do {
            b = encoded.charCodeAt(index++) - 63;
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);
        var dlng = ((result & 1) ? ~(result >> 1) : (result >> 1));
        lng += dlng;

        array.push([lat * 1e-5, lng * 1e-5]);
    }

    return array;
}

