function DOMReady(callback)
{
	document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', callback) : callback();
}

DOMReady(function() {
	function heartbeat()
	{
		var req = new XMLHttpRequest();
		req.open('POST', '/heartbeat');
		req.send();
	}

	setInterval(heartbeat, 300000);
	var url = document.getElementById('url');
	var present = document.getElementById('present');

	function handler()
	{
		if (present.checked) {
			url.removeAttribute('disabled');
		}
		else {
			url.setAttribute('disabled', '');
		}
	}

	handler();
	document.getElementById('present').addEventListener('change', handler);
	document.getElementById('absent').addEventListener('change', handler);
});
