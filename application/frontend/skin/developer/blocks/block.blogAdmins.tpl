{**
 * Список управляющих блога
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_title'}{$aLang.blog.administrators}{/block}
{block 'block_type'}blog-admins{/block}

{block 'block_content'}
	{**
	 * Функции
	 *}

	{* Список пользователей *}
	{function blog_user_list}
		{if $aUsers}
			<h3>{$sTitle}</h3>

			<ul class="user-list-small">
				{foreach $aUsers as $oUser}
					{if $oUser->getUser()}{$oUser = $oUser->getUser()}{/if}

					<li class="user-list-small-item">{include 'user_item.tpl' oUser=$oUser}</li>
				{/foreach}
			</ul>
		{/if}
	{/function}


	{* Создатель *}
	{blog_user_list sTitle="{$aLang.blog.owner}" aUsers=[ $oBlog->getOwner() ]}

	{* Администраторы *}
	{blog_user_list sTitle="{$aLang.blog.administrators} ({$iCountBlogAdministrators})" aUsers=$aBlogAdministrators}

	{* Модераторы *}
	{blog_user_list sTitle="{$aLang.blog.moderators} ({$iCountBlogModerators})" aUsers=$aBlogModerators}
{/block}