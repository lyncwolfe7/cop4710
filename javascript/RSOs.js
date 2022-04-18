function alertThenReload(msg) {
	alert(msg);
	window.location.reload(false);
}

function joinRSOAjax(RID)
{
	ajax = $.ajax({
			url: 'include/rso-include.php',
			type: 'POST',
			data: {method: "joinRSOHelp", RID: RID},
			dataType: 'json',
			success: function(data){
				if (data.status === 'ok') {
					alertThenReload("Successfully joined RSO.");
				}
				else {
					var errorMessage = data.statusText;
					alertThenReload('Error - ' + errorMessage);
				}
			}
		});
	return;
}
function joinRSO(rsoID)
{
	RID = JSON.stringify(rsoID);
	joinRSOAjax(RID);
}