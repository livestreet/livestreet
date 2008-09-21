function MoveCenterScreen(objID)
{
	var innerHeight_ = window.innerHeight ? window.innerHeight : document.documentElement.offsetHeight;
	var obj = document.getElementById(objID);
	obj.style.left = ( document.body.clientWidth / 2 - obj.clientWidth / 2  + document.body.scrollLeft) + 'px';
	obj.style.top = ( document.documentElement.scrollTop + innerHeight_ / 2 - obj.clientHeight / 2 + document.body.scrollTop - 160) + 'px';
}

function showWindow(sId) {
	var obj = document.getElementById(sId);	
	obj.style.display='block';
	MoveCenterScreen(sId);
}

function closeWindow(sId) {
	var obj = document.getElementById(sId);	
	obj.style.display='none';
}

function showWindowStatus(sText) {
	var obj = document.getElementById('window_status_text');	
	obj.innerHTML=sText;
	showWindow('window_status');
}

function closeWindowStatus() {
	closeWindow('window_status');	
}