function showLoginForm() {	
	if (Browser.Engine.trident) {
		return true;
	}	
	if (!winFormLogin) {		
		winFormLogin=new StickyWin.Modal({content: $('login-form'), closeClassName: 'close-block', useIframeShim: false});
	}
	winFormLogin.show();
	winFormLogin.pin(true);
	return false;
}

function hideLoginForm() {
	winFormLogin.hide();
}

var winFormLogin=false;