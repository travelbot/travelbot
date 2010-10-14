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
				$.post("?do=location", {latitude: data.latitude, longitude: data.longitude}, function(gData, textStatus) {
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

$("#frmlocationsForm-okFindDirections").live("click", function(event) {
	event.preventDefault();
	
	from = $("#frmlocationsForm-from");
	to = $("#frmlocationsForm-to");
	if (nette.validateForm(this)) {
		button = $(this);
		if (!showSpinner(event)) {
			button.val('working...').attr('disabled', 'disabled');
		}
		$.post("?do=directions", {from: from.val(), to: to.val()}, function(data, textStatus) {
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
	hours = Math.round(minutes / 60);
	if (minutes < 1) {
		return seconds + ' seconds';
	}
	if (hours < 1) {
		return minutes + ' minutes';
	}
	return hours + ' hours and ' + (minutes%60) + ' minutes';
};