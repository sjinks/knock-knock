AccountKit_OnInteractive = function() {
	AccountKit.init(ak_settings);
};

jQuery(
	function($)
	{
		function loginCallback(response)
		{
			var status = response.status;
			var code   = typeof response.code !== 'undefined'  ? response.code  : '';
			var state  = typeof response.state !== 'undefined' ? response.state : '';
			$('#status').val(status);
			$('#code').val(code);
			$('#state').val(state);
			$('#jsform').submit();
		}

		$('#submit').click(
			function()
			{
				AccountKit.login('EMAIL', { 'emailAddress' : jQuery('#email').val() }, loginCallback);
			}
		);
	}
);
