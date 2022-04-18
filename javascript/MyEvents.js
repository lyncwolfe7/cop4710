function alertThenReload(msg) {
	alert(msg);
	window.location.reload(false);
}

function leaveEventAjax(EID)
{
	ajax = $.ajax({
			url: 'include/myEvents-include.php',
			type: 'POST',
			data: {method: "leaveEventHelp", EID: EID},
			dataType: 'json',
			success: function(data){
				if (data.status === 'ok') {
					alertThenReload("Successfully left event.");
				}
				else {
					var errorMessage = data.statusText;
					alertThenReload('Error - ' + errorMessage);
				}
			}
		});
	return;
}

function leaveEvent(event_ID)
{
	EID = JSON.stringify(event_ID);
	leaveEventAjax(EID);
}