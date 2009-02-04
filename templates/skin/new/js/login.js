function showLoginForm() {
	var divOverlay=$$('.overlay')[0];
	var divLoginForm=$('login-form');
	divOverlay.setStyle('display','block');
	divLoginForm.setStyle('display','block');
	$('login-input').focus();
}

function hideLoginForm() {
	var divOverlay=$$('.overlay')[0];
	var divLoginForm=$('login-form');
	divOverlay.setStyle('display','none');
	divLoginForm.setStyle('display','none');
}