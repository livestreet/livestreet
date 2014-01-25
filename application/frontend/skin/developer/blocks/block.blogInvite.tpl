{**
 * Приглашение пользователей в закрытый блог.
 * Выводится на странице администрирования пользователей закрытого блога.
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.blog.invite.invite_users}{/block}
{block 'block_type'}blog-invite{/block}

{block 'block_content'}
	{* Форма добавления *}
	<form class="js-blog-invite-form mb-20" data-blog-id="{$oBlogEdit->getId()}">
		{include 'forms/fields/form.field.text.tpl'
				 sFieldName    = 'add'
				 sFieldClasses = 'width-full autocomplete-users-sep js-blog-invite-form-users'
				 sFieldLabel   = $aLang.blog.invite.form.users_label}

		{include 'forms/fields/form.field.button.tpl' sFieldText=$aLang.common.add sFieldStyle='primary' sFieldClasses='js-blog-invite-form-submit'}
	</form>

	{* Список приглашенных *}
	<div class="js-blog-invite-container" {if ! $aBlogUsersInvited}style="display: none"{/if}>
		<h3>{$aLang.blog.invite.users_title}</h3>

		<ul class="user-list-small js-blog-invite-users">
			{foreach $aBlogUsersInvited as $oBlogUser}
				{$oUser = $oBlogUser->getUser()}
				
				<li class="user-list-small-item js-blog-invite-user" data-blog-id="{$oBlogEdit->getId()}" data-user-id="{$oUser->getId()}">
					{include 'user_item.tpl' oUser=$oUser}
					
					<div class="user-list-small-item-actions">
						<a href="#" class="icon-repeat js-blog-invite-user-repeat" title="{$aLang.blog.invite.repeat}"></a>
						<a href="#" class="icon-remove js-blog-invite-user-remove" title="{$aLang.common.remove}"></a>
					</div>
				</li>						
			{/foreach}
		</ul>
	</div>
{/block}