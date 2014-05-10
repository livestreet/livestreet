{**
 * Создание блога
 * TODO: Вынести 'rangelength'  > в конфиг
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
	{include 'forms/editor.init.tpl' sEditorType='comment' sMediaTargetType='blog'}


	<form method="post" enctype="multipart/form-data" class="js-form-validate">
		{hook run='form_add_blog_begin'}


		{* Название блога *}
		{include 'components/field/field.text.tpl'
				 sName  = 'blog_title'
				 aRules = [ 'required' => true, 'rangelength' => "[2,200]" ]
				 sNote  = $aLang.blog.add.fields.title.note
				 sLabel = $aLang.blog.add.fields.title.label}

		{* URL блога *}
		{include 'components/field/field.text.tpl'
				 sName       = 'blog_url'
				 aRules      = [ 'required' => true, 'type' => 'alphanum', 'rangelength' => '[2,50]' ]
				 bIsDisabled = $_aRequest.blog_id && ! $oUserCurrent->isAdministrator()
				 sNote       = $aLang.blog.add.fields.url.note
				 sLabel      = $aLang.blog.add.fields.url.label}


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

			{include 'components/field/field.select.tpl'
					 sName          = 'blog_category'
					 sLabel         = $aLang.blog.add.fields.category.label
					 sNote          = $aLang.blog.add.fields.category.note
					 sClasses       = 'width-200'
					 aItems         = $aBlogCategoriesCustom
					 sSelectedValue = $_aRequest.blog_category}
		{/if}


		{* Тип блога *}
		{include 'components/field/field.select.tpl'
				sName          = 'blog_type'
				sLabel         = $aLang.blog.add.fields.type.label
				sNote          = $aLang.blog.add.fields.type.note_open
				sClasses       = 'width-200 js-blog-add-type'
				sSelectedValue = $_aRequest.blog_type
				aItems         = [
					[ 'value' => 'open', 'text' => $aLang.blog.add.fields.type.value_open ],
					[ 'value' => 'close', 'text' => $aLang.blog.add.fields.type.value_close ]
				]}


		{* Описание блога *}
		{include 'components/field/field.textarea.tpl'
				 sName    = 'blog_description'
				 aRules   = [ 'required' => true, 'rangelength' => '[10,3000]' ]
				 sLabel   = $aLang.blog.add.fields.description.label
				 sInputClasses = 'js-editor'}

		{* Если визуальный редактор отключен выводим справку по разметке для обычного редактора *}
		{if ! $oConfig->GetValue('view.wysiwyg')}
			{include 'forms/editor.help.tpl' sTagsTargetId='blog_description'}
		{/if}


		{* Ограничение по рейтингу *}
		{include 'components/field/field.text.tpl'
				 sName    = 'blog_limit_rating_topic'
				 aRules   = [ 'required' => true, 'type' => 'number' ]
				 sValue   = '0'
				 sClasses = 'width-100'
				 sNote    = $aLang.blog.add.fields.rating.note
				 sLabel   = $aLang.blog.add.fields.rating.label}


		{* Аватар *}
		{if $oBlogEdit and $oBlogEdit->getAvatar()}
			{foreach $oConfig->GetValue('module.blog.avatar_size') as $iSize}
				{if $iSize}<img src="{$oBlogEdit->getAvatarPath({$iSize})}">{/if}
			{/foreach}

			{include 'components/field/field.checkbox.tpl' sName='avatar_delete' bNoMargin=true sValue='on' sLabel=$aLang.common.remove}
		{/if}

		{include 'components/field/field.file.tpl'
				 sName  = 'avatar'
				 sLabel = $aLang.blog.add.fields.avatar.label}


		{hook run='form_add_blog_end'}


		{* Скрытые поля *}
		{include 'components/field/field.hidden.security_key.tpl'}


		{* Кнопки *}
		{if $sEvent == 'add'}
			{$sSubmitInputText = $aLang.common.create}
		{else}
			{$sSubmitInputText = $aLang.common.save}
		{/if}

		{include 'components/button/button.tpl' sName='submit_blog_add' sText=$sSubmitInputText sStyle='primary'}
	</form>
{/block}