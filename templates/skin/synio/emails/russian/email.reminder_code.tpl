{extends file='emails/email.base.tpl'}

{block name='content'}
	Если вы хотите сменить себе пароль на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>, то перейдите по ссылке ниже:<br>
	<a href="{router page='login'}reminder/{$oReminder->getCode()}/">{router page='login'}reminder/{$oReminder->getCode()}/</a>
{/block}