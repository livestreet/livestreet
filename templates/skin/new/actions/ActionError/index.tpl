{include file='header.light.tpl' noShowSystemMessage=true}

	<div class="lite-center error">
		<h1>{$aLang.error}: {$aMsgError[0].title}</h1>
		<p>{$aMsgError[0].msg}</p>
		<p><a href="javascript:history.go(-1);">Вернуться назад</a> или <a href="{$DIR_WEB_ROOT}">перейти на главную</a></p>
	</div>

{include file='footer.light.tpl'}