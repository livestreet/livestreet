<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="ru" xml:lang="ru">

<head>
	<title>___LANG_INSTALL_TITLE___</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />	
	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="templates/styles/style.css?v=1" />	
	<!--[if IE 6]><link rel="stylesheet" type="text/css" href="templates/styles/ie6.css?v=1" /><![endif]-->
	<!--[if gte IE 7]><link rel="stylesheet" type="text/css" href="templates/styles/ie7.css?v=1" /><![endif]-->
</head>

<body>

<div id="container">
	<h1 class="lite-header">___LANG_INSTALL_TITLE___ (v.___INSTALL_VERSION___) | ___LANG_STEP___ #___INSTALL_STEP_NUMBER___ / ___INSTALL_STEP_COUNT___</h1>

	<div class="lite-center register">
		<p align="right"><sup><a href="?lang=russian">RUS</a> | <a href="?lang=english">ENG</a></sup></p>
		___SYSTEM_MESSAGES___
		<form action="___FORM_ACTION___" method="POST">
			___CONTENT___
			<br />
			<p class="buttons">
				<input type="submit" class="right" name="install_step_next" value="___LANG_NEXT___" ___NEXT_STEP_DISABLED___ style="display:___NEXT_STEP_DISPLAY___;" />
				<input  type="submit" class="left" name="install_step_prev" value="___LANG_PREV___" ___PREV_STEP_DISABLED___ style="display:___PREV_STEP_DISPLAY___;" />				
			</p>
		</form>
	
	</div>
</div>
</body>
</html>