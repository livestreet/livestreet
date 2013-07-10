{**
 * Создание блога
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{if $sEvent == 'add'}
		{$sNavContent = 'create'}
	{else}
		{$sNavContent = 'blog.edit'}
	{/if}
{/block}

{block name='layout_content'}

	{* Подключение редактора *}
	{include file='forms/editor.init.tpl' sEditorType='comment'}


	{* Подгрузка инфорамации о типе блога *}
	<script type="text/javascript">
		jQuery(document).ready(function($){
			ls.lang.load({lang_load name="blog_create_type_open_notice,blog_create_type_close_notice"});
			ls.blog.loadInfoType($('#blog_type').val());
		});
	</script>


	<form method="post" enctype="multipart/form-data">
		{hook run='form_add_blog_begin'}


		{* Название блога *}
		<p><label for="blog_title">{$aLang.blog_create_title}:</label>
		<input type="text" id="blog_title" name="blog_title" value="{$_aRequest.blog_title}" class="width-full" />
		<small class="note">{$aLang.blog_create_title_notice}</small></p>


		{* URL блога *}
		<p><label for="blog_url">{$aLang.blog_create_url}:</label>
		<input type="text" id="blog_url" name="blog_url" value="{$_aRequest.blog_url}" class="width-full" {if $_aRequest.blog_id and !$oUserCurrent->isAdministrator()}disabled{/if} />
		<small class="note">{$aLang.blog_create_url_notice}</small></p>


		{* Категория блога *}
		{if Config::Get('module.blog.category_allow') and ($oUserCurrent->isAdministrator() or !Config::Get('module.blog.category_only_admin'))}
			<p><label for="blog_category">{$aLang.blog_create_category}:</label>
			<select name="blog_category" id="blog_category" class="width-200" >
				{if Config::Get('module.blog.category_allow_empty')}
					<option value="0"></option>
				{/if}
				{foreach $aBlogCategories as $oBlogCategory}
					<option {if $_aRequest.blog_category==$oBlogCategory->getId()}selected{/if} value="{$oBlogCategory->getId()}" style="margin-left: {$oBlogCategory->getLevel()*20}px;">{$oBlogCategory->getTitle()|escape:'html'}</option>
				{/foreach}
			</select>
			<small class="note" id="blog_category_note">{$aLang.blog_create_category_notice}</small></p>
		{/if}


		{* Тип блога *}
		<p><label for="blog_type">{$aLang.blog_create_type}:</label>
		<select name="blog_type" id="blog_type" class="width-200" onChange="ls.blog.loadInfoType(jQuery(this).val());">
			<option value="open" {if $_aRequest.blog_type=='open'}selected{/if}>{$aLang.blog_create_type_open}</option>
			<option value="close" {if $_aRequest.blog_type=='close'}selected{/if}>{$aLang.blog_create_type_close}</option>
		</select>
		<small class="note" id="blog_type_note">{$aLang.blog_create_type_open_notice}</small></p>


		{* Описание блога *}
		<label for="blog_description">{$aLang.blog_create_description}:</label>
		<textarea name="blog_description" id="blog_description" rows="15" class="js-editor width-full">{$_aRequest.blog_description}</textarea>

		{* Если визуальный редактор отключен выводим справку по разметке для обычного редактора *}
		{if ! $oConfig->GetValue('view.wysiwyg')}
			{include file='forms/editor.help.tpl' sTagsTargetId='blog_description'}
		{/if}

		
		{* Ограничение по рейтингу *}
		<p><label for="blog_limit_rating_topic">{$aLang.blog_create_rating}:</label>
		<input type="text" id="blog_limit_rating_topic" name="blog_limit_rating_topic" value="{$_aRequest.blog_limit_rating_topic}" class="width-100" />
		<small class="note">{$aLang.blog_create_rating_notice}</small></p>


		{* Аватар *}
		<p>
			{if $oBlogEdit and $oBlogEdit->getAvatar()}
				<div class="avatar-edit">
					{foreach $oConfig->GetValue('module.blog.avatar_size') as $iSize}
						{if $iSize}<img src="{$oBlogEdit->getAvatarPath({$iSize})}">{/if}
					{/foreach}
					
					<label><input type="checkbox" id="avatar_delete" name="avatar_delete" value="on"> {$aLang.blog_create_avatar_delete}</label>
				</div>
			{/if}
			
			<label for="avatar">{$aLang.blog_create_avatar}:</label>
			<input type="file" name="avatar" id="avatar">
		</p>


		{hook run='form_add_blog_end'}
		

		{* Скрытые поля *}
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />


		{* Кнопки *}
		<button type="submit" name="submit_blog_add" class="button button-primary">
			{if $sEvent == 'add'}
				{$aLang.blog_create_submit}
			{else}
				{$aLang.topic_create_submit_update}
			{/if}
		</button>
	</form>
{/block}