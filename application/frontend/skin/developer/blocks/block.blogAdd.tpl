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

		{include 'components/button/button.tpl' sUrl="{router page='blog'}add/" sMods='primary large' sText=$aLang.blog.create_blog}
	{else}
		<p>{lang name='blog.cant_add' rating=Config::Get('acl.create.blog.rating')}</p>

		{include 'components/button/button.tpl' sMods='primary large' sText=$aLang.blog.create_blog bIsDisabled=true}
	{/if}
{/block}