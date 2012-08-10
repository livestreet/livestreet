<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

<head>
	<title>___LANG_INSTALL_TITLE___</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<link rel="stylesheet" type="text/css" href="templates/styles/style.css?v=1" />	
</head>

<body>
	<div id="container">
		<div id="header">
			<h1>___LANG_INSTALL_TITLE___ ___INSTALL_VERSION___ <span>___LANG_STEP___ ___INSTALL_STEP_NUMBER___ / ___INSTALL_STEP_COUNT___</span></h1>
			
			<div class="lang"><a href="?lang=russian">RUS</a> | <a href="?lang=english">ENG</a></div>
		</div>

		<div id="content">
			
			___SYSTEM_MESSAGES___
			
			<form action="___FORM_ACTION___" method="POST">
				___CONTENT___
				<br />
				
				<input type="submit" class="button" name="install_step_prev" value="___LANG_PREV___" ___PREV_STEP_DISABLED___ style="display:___PREV_STEP_DISPLAY___;" />
				<input type="submit" class="button button-primary" name="install_step_next" value="___LANG_NEXT___" ___NEXT_STEP_DISABLED___ style="display:___NEXT_STEP_DISPLAY___;" />
			</form>
		</div>
	</div>
</body>

</html>