function alertThenReload(msg) {
	alert(msg);
	window.location.reload(false);
}

function JoinEventAjax(EID)
{
	ajax = $.ajax({
			url: 'include/events-include.php',
			type: 'POST',
			data: {method: "joinEventHelp", EID: EID},
			dataType: 'json',
			success: function(data){
				if (data.status === 'ok') {
					alertThenReload("Successfully joined event.");
				}
				else {
					var errorMessage = data.statusText;
					alertThenReload('Error - ' + errorMessage);
				}
			}
		});
	return;
}
function attemptJoinEvent(eventid)
{
	eid = JSON.stringify(eventid);
	attemptJoinEventAjax(eid);
}