{**
 * Создание блога
 * TODO: Вынести rangelength в конфиг
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{$bNoSidebar = true}

	{if $sEvent == 'edit'}
		{$sNav = 'blog.edit'}
	{/if}
{/block}

{block name='layout_page_title'}
	{if $sEvent == 'add'}
		{$aLang.blog_create}
	{else}
		{$aLang.blog_admin}: <a href="{$oBlogEdit->getUrlFull()}">{$oBlogEdit->getTitle()|escape:'html'}</a>
	{/if}
{/block}

{block name='layout_content'}
	{* Подключение редактора *}
	{include file='forms/editor.init.tpl' sEditorType='comment'}


	<form method="post" enctype="multipart/form-data" data-validate="parsley">
		{hook run='form_add_blog_begin'}


		{* Название блога *}
		{include file='forms/form.field.text.tpl' 
				 sFieldName  = 'blog_title' 
				 sFieldRules = 'required="true" rangelength="[2,200]"'
				 sFieldNote  = $aLang.blog_create_title_notice 
				 sFieldLabel = $aLang.blog_create_title}

		{* URL блога *}
		{include file='forms/form.field.text.tpl' 
				 sFieldName       = 'blog_url' 
				 sFieldRules      = 'required="true" type="alphanum" rangelength="[2,50]"'
				 bFieldIsDisabled = $_aRequest.blog_id && ! $oUserCurrent->isAdministrator()
				 sFieldNote       = $aLang.blog_create_url_notice 
				 sFieldLabel      = $aLang.blog_create_url}


		{* Категория блога *}
		{if Config::Get('module.blog.category_allow') and ($oUserCurrent->isAdministrator() or ! Config::Get('module.blog.category_only_admin'))}
			{$aBlogCategoriesCustom = [
				[ 'value' => 0, 'text' => '&mdash;' ]
			]}

			{foreach $aBlogCategories as $oBlogCategory}
				{$aBlogCategoriesCustom[] = [
					'value' => $oBlogCategory->getId(),
					'text' => $oBlogCategory->getTitle()|escape
				]}
			{/foreach}

			{include file='forms/form.field.select.tpl' 
					 sFieldName          = 'blog_category'
					 sFieldLabel         = $aLang.blog_create_category
					 sFieldNote          = $aLang.blog_create_category_notice
					 sFieldClasses       = 'width-200'
					 aFieldItems         = $aBlogCategoriesCustom
					 sFieldSelectedValue = $_aRequest.blog_category}
		{/if}


		{* Тип блога *}
		{$aBlogsType = [
			[ 'value' => 'open', 'text' => $aLang.blog_create_type_open ],
			[ 'value' => 'close', 'text' => $aLang.blog_create_type_close ]
        ]}

		{include file='forms/form.field.select.tpl' 
				 sFieldName          = 'blog_type'
				 sFieldLabel         = $aLang.blog_create_type
				 sFieldNote          = $aLang.blog_create_type_open_notice
				 sFieldClasses       = 'width-200 js-blog-add-type'
				 aFieldItems         = $aBlogsType
				 sFieldSelectedValue = $_aRequest.blog_type} {* TODO: Подсказка при смене типа *}


		{* Описание блога *}
		{include file='forms/form.field.textarea.tpl' 
				 sFieldName    = 'blog_description'
				 sFieldRules   = 'required="true" rangelength="[10,3000]"' 
				 sFieldLabel   = $aLang.blog_create_description
				 sFieldClasses = 'width-full js-editor'}

		{* Если визуальный редактор отключен выводим справку по разметке для обычного редактора *}
		{if ! $oConfig->GetValue('view.wysiwyg')}
			{include file='forms/editor.help.tpl' sTagsTargetId='blog_description'}
		{/if}

		
		{* Ограничение по рейтингу *}
		{include file='forms/form.field.text.tpl' 
				 sFieldName    = 'blog_limit_rating_topic'
				 sFieldRules   = 'required="true" type="number"'
				 sFieldValue   = '0'
				 sFieldClasses = 'width-100'
				 sFieldNote    = $aLang.blog_create_rating_notice
				 sFieldLabel   = $aLang.blog_create_rating}


		{* Аватар *}
		{if $oBlogEdit and $oBlogEdit->getAvatar()}
			{foreach $oConfig->GetValue('module.blog.avatar_size') as $iSize}
				{if $iSize}<img src="{$oBlogEdit->getAvatarPath({$iSize})}">{/if}
			{/foreach}

			{include file='forms/form.field.checkbox.tpl' sFieldName='avatar_delete' bFieldNoMargin=true sFieldValue='on' sFieldLabel=$aLang.blog_create_avatar_delete}
		{/if}

		{include file='forms/form.field.file.tpl' 
				 sFieldName  = 'avatar'
				 sFieldLabel = $aLang.blog_create_avatar}


		{hook run='form_add_blog_end'}
		

		{* Скрытые поля *}
		{include file='forms/form.field.hidden.security_key.tpl'}


		{* Кнопки *}
		{if $sEvent == 'add'}
			{$sSubmitInputText = $aLang.blog_create_submit}
		{else}
			{$sSubmitInputText = $aLang.topic_create_submit_update}
		{/if}
		{include file='forms/form.field.button.tpl' sFieldName='submit_blog_add' sFieldText=$sSubmitInputText sFieldStyle='primary'}
	</form>
{/block}