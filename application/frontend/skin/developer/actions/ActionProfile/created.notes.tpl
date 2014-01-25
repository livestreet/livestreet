{**
 * Список заметок созданных пользователем
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_user_page_title'}{$aLang.user_menu_publication}{/block}

{block name='layout_content'}
	{include file='navs/nav.user.created.tpl'}

	{if $aNotes}
		<ul class="object-list user-list">
			{foreach $aNotes as $oNote}
				{$oUser = $oNote->getTargetUser()}

				<li class="object-list-item">
					{* Аватар *}
					<a href="{$oUser->getUserWebPath()}">
						<img src="{$oUser->getProfileAvatarPath(100)}" width="100" height="100" alt="{$oUser->getLogin()}" class="object-list-item-image" />
					</a>

					{* Заголовок *}
					<h2 class="object-list-item-title">
						<a href="{$oUser->getUserWebPath()}">{$oUser->getDisplayName()}</a>
					</h2>

					{* Заметка *}
					<p class="object-list-item-description user-note">{$oNote->getText()|escape}</p>
				</li>
			{/foreach}
		</ul>
	{else}
		{include file='alert.tpl' mAlerts=$aLang.user_note_list_empty sAlertStyle='empty'}
	{/if}

	{include file='pagination.tpl' aPaging=$aPaging}
{/block}