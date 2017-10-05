/** global: AccountKit, ak_settings */
jQuery(function($) {
	function heartbeat()
	{
		$.post('/heartbeat');
	}

	setInterval(heartbeat, 300000);
	$('#url').attr('disabled', !$('#present').is(':checked'));
	$('#present, #absent').change(function() {
		$('#url').attr('disabled', !$('#present').is(':checked'));
	});

	$("#date").datepicker({ dateFormat: "dd.mm.yy" });
});
