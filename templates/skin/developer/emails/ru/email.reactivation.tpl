{extends file='emails/email.base.tpl'}

{block name='content'}
	Вы запросили повторную активацию на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>
	<br>
	<br>
	Ссылка на активацию аккаунта:
	<br>
	<a href="{router page='registration'}activate/{$oUser->getActivateKey()}/">{router page='registration'}activate/{$oUser->getActivateKey()}/</a>
{/block}