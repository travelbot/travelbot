$(function() {
	$.geolocator.geolocate({
		callback: function(data) {
			form = $("#frmlocationsForm-from");
			if (data.latitude == null || typeof(data.client) !== 'undefined') {
				if (form.val() == "") {
					$("#frmlocationsForm-from").val(data.client.address.city + ", " + data.client.address.country)
				}
				
				$("#frmlocationsForm-to").focus();
			} else {
				$.post("?do=location", {latitude: data.latitude, longitude: data.longitude}, function(gData, textStatus) {
					if (gData.status == 'OK') {
						if (form.val() == "") {
							$("#frmlocationsForm-from").val(gData['location']);
						}
						
						$("#frmlocationsForm-to").focus();
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
				text = $('<p>The trip will take <strong>' + data['duration'] + '</strong> to complete and its distance is <strong>' + data['distance'] + '.</strong></p>');
				ol = $('<ol>');
				$.each(data.steps, function(i, el) {
					ol.append($('<li>').html(el['instructions'] + ' (' + el['distance'] + ')'));
				});
				text.append(ol);
			} else {
				text = $('<p>Can not find the destination or the trip can not be finished.</p>');
			}
			
			textEl = $("#directions-text");
				textEl.text('');
			textEl.append(text);
			
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
	if (event.pageX && event.pageY) {
		$("#ajax-spinner").show().css({
			position: "absolute",
			left: event.pageX,
			top: event.pageY
		});
		return true;
	}
	return false;
}