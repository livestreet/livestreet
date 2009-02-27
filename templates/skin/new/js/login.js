function showLoginForm() {	
	if (!winFormLogin) {
		winFormLogin=new StickyWinModal({content: $('login-form-content').get('html'), closeClassName: 'close-block'});
	}
	winFormLogin.show();
	winFormLogin.pin(true);	
}

function hideLoginForm() {
	winFormLogin.hide();
}

var winFormLogin=false;