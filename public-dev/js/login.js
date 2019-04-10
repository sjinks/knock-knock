function login()
{
	var lock = new Auth0LockPasswordless(settings[0], settings[1], { passwordlessMethod: "link", auth: auth });
	lock.show();
}

function DOMReady(callback)
{
	document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', callback) : callback();
}

DOMReady(function() {
	document.getElementById('form').addEventListener('submit', function(e) {
		e.preventDefault();
		try {
			login();
		}
		catch (e) {
			console && console.log(e);
		}

		return false;
	});
});