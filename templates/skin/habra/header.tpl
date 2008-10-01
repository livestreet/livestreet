<HTML>

<HEAD>
	<TITLE>{$sHtmlTitle}</TITLE>
	<META http-equiv=Content-Type content="text/html; charset=UTF-8">
	<META name="DESCRIPTION" content="LiveStreet - официальный сайт бесплатного движка социальной сети">
	<META name="KEYWORDS" content="движок, livestreet, блоги, социальная сеть, бесплатный, php">

	<LINK href="{$DIR_STATIC_SKIN}/img/favicon.ico" rel="shortcut icon">
	<LINK media=all href="{$DIR_STATIC_SKIN}/css/habrahabr.css?v=1" type=text/css rel=stylesheet>
	<LINK media=all href="{$DIR_STATIC_SKIN}/css/backoffice.css?v=2" type=text/css rel=stylesheet>
	<LINK media=all href="{$DIR_STATIC_SKIN}/css/global.css?v=4" type=text/css rel=stylesheet>
	<LINK media=all href="{$DIR_STATIC_SKIN}/css/my.css?v=3" type=text/css rel=stylesheet>	
	<LINK media=all href="{$DIR_STATIC_SKIN}/css/Roar.css" type=text/css rel=stylesheet>
	<LINK media=all href="{$DIR_STATIC_SKIN}/css/Autocompleter.css" type=text/css rel=stylesheet>
</HEAD>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/panel.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/windows.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/JsHttpRequest/JsHttpRequest.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/mootools-1.2-core-yc.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Roal/Roar.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Autocompleter.Request.js"></script>
<script type="text/javascript" src="{$DIR_WEB_ROOT}/classes/lib/external/MooTools_1.2/plugs/Autocompleter/Observer.js"></script>
<script type="text/javascript" src="{$DIR_STATIC_SKIN}/js/main.js?v=10"></script>
<BODY>

{literal}
<script language="JavaScript" type="text/javascript">
var msgErrorBox=new Roar({
			position: 'upperRight',
			className: 'roar-error'
		});	
var msgNoticeBox=new Roar({
			position: 'upperRight',
			className: 'roar-notice'
		});	
</script>
{/literal}
<script>var DIR_WEB_ROOT='{$DIR_WEB_ROOT}';</script>


<div id="debug" style="border: 2px #dd0000 solid; display: none;"></div>

<div id="window_status">
<table>
	<tr>
		<td><IMG class=sitelogo title="закрыть"  alt="закрыть" src="{$DIR_STATIC_SKIN}/img/loader.gif" border=0 style="cursor: pointer;" onclick="closeWindowStatus();"></td>
		<td><div id="window_status_text"></div></td>
	</tr>
</table>
</div>

<DIV id=head>
	<DIV class=right>
		
	{if $oUserCurrent}
		<A href="{$DIR_WEB_ROOT}/profile/{$oUserCurrent->getLogin()}/"><IMG class=img_border title=" это я! " aheight=64 alt="" src="{$oUserCurrent->getProfileAvatarPath(64)}" width=64 border=0></A> 
		<DIV class=hello>
			<A class=hello_nickname href="{$DIR_WEB_ROOT}/profile/{$oUserCurrent->getLogin()}/">{$oUserCurrent->getLogin()}</A> ( <A class=hello_exit href="{$DIR_WEB_ROOT}/login/exit/">выход</a> ) <BR>			
		</DIV>
		{if $iUserCurrentCountTalkNew}
		<nobr>У вас есть <IMG vspace="0" title="Проверьте почту!" align="middle" height=12 alt="" src="{$DIR_STATIC_SKIN}/img/mail.gif" width=12 border=0> <A class=hello_exit href="{$DIR_WEB_ROOT}/talk/">новые сообщения({$iUserCurrentCountTalkNew})</a></nobr><br>
		{else}
		<nobr>У вас нет <A class=hello_exit href="{$DIR_WEB_ROOT}/talk/">новых сообщений</a></nobr><br>
		{/if}

		Изменить <A class=hello_exit href="{$DIR_WEB_ROOT}/settings/">настройки профиля</a>
	{else}
		<DIV class=hello>		
			<form action="{$DIR_WEB_ROOT}/login/" method="POST" id="loginform">		
			<table  border="0">
				<tr>
					<td align="right" width="100%">
					Логин:
					</td>
					<td>
					<input type="text" name="login" value="" size="15">
					</td>
				</tr>
				<tr>
					<td align="right" >
					Пароль:
					</td>
					<td>
					<input type="password" name="password" value="" size="15">
					</td>
				</tr>
				<tr>
					<td>
					
					</td>
					<td  align="right" >
					<input type="submit" name="submit_login" value="Войти" size="15">
					</td>
				</tr>
			</table>	
			</form>	
			<A href="{$DIR_WEB_ROOT}/registration/">Регистрация</A> &nbsp;	
		</DIV>		
	{/if}
		
		
		<!--
		<DIV class=search_form>
			форма поиска
		</DIV>
		-->
		
		
		
		
		
		
	</DIV>
	<DIV class=headlayout>
		<DIV class=left>
			<A href="{$DIR_WEB_ROOT}/"><IMG class=sitelogo title="{$SITE_NAME}" height=130 alt="{$SITE_NAME}" src="{$DIR_STATIC_SKIN}/img/logo.gif" width=240 border=0></A> 
			<DIV class=menu>
				<A href="{$DIR_WEB_ROOT}/blog/">Блоги</A> 
				<A href="{$DIR_WEB_ROOT}/people/">Люди</A> 
				<A href="{$DIR_WEB_ROOT}/page/about/">О проекте</A>				
				<A href="{$DIR_WEB_ROOT}/page/download/">Скачать</A>
				<A href="http://trac.assembla.com/livestreet/timeline" target="_blank" style="color: #d00;">SVN</A>
			</DIV>
			<!--
			<DIV class=submenu>
				вложенное меню
			</DIV>
			<DIV class=habraadvert>
				баннер 1
			</DIV>
			-->
		</DIV>
	</DIV>
</DIV>




  
<DIV id=maindata>
	<DIV id=megadata>
		{if ($sAction=='profile' or $sAction=='my') and $oUserProfile}
		<DIV id=usermegadata>
			<DIV class=biguserinfo>
				<A class=userinfo_name_superbig href="{$DIR_WEB_ROOT}/profile/{$oUserProfile->getLogin()}/"><IMG class=img_border title=" это я! " height=48 alt="" src="{$oUserProfile->getProfileAvatarPath(48)}" width=48 align=absMiddle border=0> {$oUserProfile->getLogin()}</A> 				
				
			</DIV>
		</DIV>
			{if $oUserCurrent and $oUserCurrent->getId()!=$oUserProfile->getId()}
				<a href="{$DIR_WEB_ROOT}/talk/add/?talk_users={$oUserProfile->getLogin()}"><img class=userinfo_name_superbig src="{$DIR_STATIC_SKIN}/img/mail_big.gif" border="0" title="Написать письмо" alt="Написать письмо" align="top"></a>
				
				<span id="user_frend_add" {if $oUserProfile->getUserIsFrend()}style="display: none;"{/if}>
					<a href="#" title="добавить в друзья" onclick="ajaxUserFrend({$oUserProfile->getId()},1); return false;"><img src="{$DIR_STATIC_SKIN}/img/frend_add.gif" width="24" height="24"></a>
				</span>	
				<span id="user_frend_del" {if !$oUserProfile->getUserIsFrend()}style="display: none;"{/if}>
					<a href="#" title="удалить из друзей" onclick="ajaxUserFrend({$oUserProfile->getId()},0); return false;"><img src="{$DIR_STATIC_SKIN}/img/frend_del.gif" width="24" height="24"></a>
				</span>
				
			{/if}
		{/if}
		<DIV id=content>
			<DIV id=right>
			
			
				{foreach from=$aBlockRight item=aBlock}															
					{if $aBlock.type=='block'}
						{insert name="block" block=`$aBlock.name` params=`$aBlock.params`} 
					{/if}
					{if $aBlock.type=='template'}						 
						{include file=`$aBlock.name` params=`$aBlock.params`}
					{/if}					
				{/foreach}			
				
				
				
			</DIV>
			<DIV id=left>