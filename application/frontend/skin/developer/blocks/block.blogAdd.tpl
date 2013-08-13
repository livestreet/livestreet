{**
 * Блок с кнопкой добавления блога
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_type'}blog-add{/block}

{block name='block_options'}
	{if ! $oUserCurrent}
		{$bBlockNotShow = true}
	{/if}
{/block}

{block name='block_content'}
	{if $oUserCurrent and ($oUserCurrent->getRating() > {cfg name='acl.create.blog.rating'} or $oUserCurrent->isAdministrator())}
		<p>{$aLang.blog_can_add}</p>

		<a href="{router page='blog'}add/" class="button button-primary button-large">{$aLang.blog_add}</a>
	{else}
		<p>{$aLang.blog_cant_add|ls_lang:"rating%%`$oConfig->get('acl.create.blog.rating')`"}</p>

		<button class="button button-primary button-large" disabled>{$aLang.blog_add}</button>
	{/if}
{/block}