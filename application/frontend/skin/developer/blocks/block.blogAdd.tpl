{**
 * Блок с кнопкой добавления блога
 *
 * @styles css/blocks.css
 *}

{extends 'blocks/block.aside.base.tpl'}

{block 'block_type'}blog-add{/block}

{block 'block_options'}
	{if ! $oUserCurrent}
		{$bBlockNotShow = true}
	{/if}
{/block}

{block 'block_content'}
	{if $oUserCurrent and ($oUserCurrent->getRating() > {cfg name='acl.create.blog.rating'} or $oUserCurrent->isAdministrator())}
		<p>{$aLang.blog.can_add}</p>

		<a href="{router page='blog'}add/" class="button button-primary button-large">{$aLang.blog.create_blog}</a>
	{else}
		<p>{$aLang.blog.cant_add|ls_lang:"rating%%`$oConfig->get('acl.create.blog.rating')`"}</p>

		<button class="button button-primary button-large" disabled>{$aLang.blog.create_blog}</button>
	{/if}
{/block}