{**
 * Блок с аватаркой и именем пользователя
 *
 * @styles css/common.css
 *}

<div class="user-item {if $sUserItemStyle}user-item-{$sUserItemStyle}{/if} {if $bUserItemIsInactive}inactive{/if} {$sUserItemClasses}"
	 {if $bUserItemIsInactive}title="{$sUserItemInactiveTitle}"{/if}>
	<a href="{$oUser->getUserWebPath()}" class="user-item-avatar-link"><img src="{$oUser->getProfileAvatarPath($iUserItemAvatarSize|default:24)}" alt="{$oUser->getLogin()}" class="user-item-avatar" /></a>
	<a {* TODO: rel="author" *} href="{$oUser->getUserWebPath()}" class="user-item-name">{$oUser->getDisplayName()}</a>
</div>