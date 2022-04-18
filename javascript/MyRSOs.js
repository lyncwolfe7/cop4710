function alertThenReload(msg) {
	alert(msg);
	window.location.reload(false);
}

function leaveRSOAjax(RID)
{
	ajax = $.ajax({
			url: 'include/myRSOs-include.php',
			type: 'POST',
			data: {method: "leaveRSOHelp", RID: RID},
			dataType: 'json',
			success: function(data){
				if (data.status === 'ok') {
					alertThenReload("Successfully left RSO.");
				}
				else {
					var errorMessage = data.statusText;
					alertThenReload('Error - ' + errorMessage);
				}
			}
		});
	return;
}
function leaveRSO(rsoID)
{
	RID = JSON.stringify(rsoID);
	leaveRSOAjax(RID);
}