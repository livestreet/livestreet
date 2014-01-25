{**
 * Создание блога
 * TODO: Вынести rangelength в конфиг
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$bNoSidebar = true}

	{if $sEvent == 'edit'}
		{$sNav = 'blog.edit'}
	{/if}
{/block}

{block 'layout_page_title'}
	{if $sEvent == 'add'}
		{$aLang.blog.add.title}
	{else}
		{$aLang.blog.admin.title}: <a href="{$oBlogEdit->getUrlFull()}">{$oBlogEdit->getTitle()|escape}</a>
	{/if}
{/block}

{block 'layout_content'}
	{* Подключение редактора *}
	{include 'forms/editor.init.tpl' sEditorType='comment'}


	<form method="post" enctype="multipart/form-data" class="js-form-validate">
		{hook run='form_add_blog_begin'}


		{* Название блога *}
		{include 'forms/fields/form.field.text.tpl'
				 sFieldName  = 'blog_title'
				 sFieldRules = 'required="true" rangelength="[2,200]"'
				 sFieldNote  = $aLang.blog.add.fields.title.note
				 sFieldLabel = $aLang.blog.add.fields.title.label}

		{* URL блога *}
		{include 'forms/fields/form.field.text.tpl'
				 sFieldName       = 'blog_url'
				 sFieldRules      = 'required="true" type="alphanum" rangelength="[2,50]"'
				 bFieldIsDisabled = $_aRequest.blog_id && ! $oUserCurrent->isAdministrator()
				 sFieldNote       = $aLang.blog.add.fields.url.note
				 sFieldLabel      = $aLang.blog.add.fields.url.label}


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

			{include 'forms/fields/form.field.select.tpl'
					 sFieldName          = 'blog_category'
					 sFieldLabel         = $aLang.blog.add.fields.category.label
					 sFieldNote          = $aLang.blog.add.fields.category.note
					 sFieldClasses       = 'width-200'
					 aFieldItems         = $aBlogCategoriesCustom
					 sFieldSelectedValue = $_aRequest.blog_category}
		{/if}


		{* Тип блога *}
		{$aBlogsType = [
			[ 'value' => 'open', 'text' => $aLang.blog.add.fields.type.value_open ],
			[ 'value' => 'close', 'text' => $aLang.blog.add.fields.type.value_close ]
        ]}

		{include 'forms/fields/form.field.select.tpl'
				 sFieldName          = 'blog_type'
				 sFieldLabel         = $aLang.blog.add.fields.type.label
				 sFieldNote          = $aLang.blog.add.fields.type.note_open
				 sFieldClasses       = 'width-200 js-blog-add-type'
				 aFieldItems         = $aBlogsType
				 sFieldSelectedValue = $_aRequest.blog_type}


		{* Описание блога *}
		{include 'forms/fields/form.field.textarea.tpl'
				 sFieldName    = 'blog_description'
				 sFieldRules   = 'required="true" rangelength="[10,3000]"'
				 sFieldLabel   = $aLang.blog.add.fields.description.label
				 sFieldClasses = 'width-full js-editor'}

		{* Если визуальный редактор отключен выводим справку по разметке для обычного редактора *}
		{if ! $oConfig->GetValue('view.wysiwyg')}
			{include 'forms/editor.help.tpl' sTagsTargetId='blog_description'}
		{/if}


		{* Ограничение по рейтингу *}
		{include 'forms/fields/form.field.text.tpl'
				 sFieldName    = 'blog_limit_rating_topic'
				 sFieldRules   = 'required="true" type="number"'
				 sFieldValue   = '0'
				 sFieldClasses = 'width-100'
				 sFieldNote    = $aLang.blog.add.fields.rating.note
				 sFieldLabel   = $aLang.blog.add.fields.rating.label}


		{* Аватар *}
		{if $oBlogEdit and $oBlogEdit->getAvatar()}
			{foreach $oConfig->GetValue('module.blog.avatar_size') as $iSize}
				{if $iSize}<img src="{$oBlogEdit->getAvatarPath({$iSize})}">{/if}
			{/foreach}

			{include 'forms/fields/form.field.checkbox.tpl' sFieldName='avatar_delete' bFieldNoMargin=true sFieldValue='on' sFieldLabel=$aLang.common.remove}
		{/if}

		{include 'forms/fields/form.field.file.tpl'
				 sFieldName  = 'avatar'
				 sFieldLabel = $aLang.blog.add.fields.avatar.label}


		{hook run='form_add_blog_end'}


		{* Скрытые поля *}
		{include 'forms/fields/form.field.hidden.security_key.tpl'}


		{* Кнопки *}
		{if $sEvent == 'add'}
			{$sSubmitInputText = $aLang.common.create}
		{else}
			{$sSubmitInputText = $aLang.common.save}
		{/if}
		
		{include 'forms/fields/form.field.button.tpl' sFieldName='submit_blog_add' sFieldText=$sSubmitInputText sFieldStyle='primary'}
	</form>
{/block}