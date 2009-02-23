function showLoginForm() {	
	winFormLogin.show();
	winFormLogin.pin(true);	
}

function hideLoginForm() {
	winFormLogin.hide();
}

var winFormLogin;

window.addEvent('domready', function() {  	
	var form=$('login-form');
	if (form) {
		form.setStyle('display','block');
    	winFormLogin=new StickyWinModal({content: form, closeClassName: 'close-block'});    
    	winFormLogin.hide();
	}
});