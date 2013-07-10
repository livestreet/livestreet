{**
 * Базовая форма создания топика
 *
 * @styles css/topic.css
 *}

{extends file='layouts/layout.base.tpl'}

{block name='layout_options'}
	{if $sEvent == 'add'}
		{$sNav = 'create'}
	{/if}
{/block}

{block name='layout_page_title'}
	{if $sEvent == 'add'}
		{$aLang.topic_create}
	{else}
		{$aLang.topic_topic_edit}
	{/if}
{/block}

{block name='layout_content'}
	{block name='add_topic_options'}{/block}

	{* Подключение редактора *}
	{include file='forms/editor.init.tpl'}


	{hook run="add_topic_`$sTopicType`_begin"}
	{block name='add_topic_header_after'}{/block}


	<form action="" method="POST" enctype="multipart/form-data" id="form-topic-add">
		{hook run="form_add_topic_`$sTopicType`_begin"}
		{block name='add_topic_form_begin'}{/block}


		{* Выбор блога *}
		<p><label for="blog_id">{$aLang.topic_create_blog}</label>
		<select name="blog_id" id="blog_id" onChange="ls.blog.loadInfo(jQuery(this).val());" class="width-full">
			<option value="0">{$aLang.topic_create_blog_personal}</option>
			{foreach $aBlogsAllow as $oBlog}
				<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()|escape:'html'}</option>
			{/foreach}
		</select>
		<small class="note">{$aLang.topic_create_blog_notice}</small></p>


		{* Заголовок топика *}
		<p><label for="topic_title">{$aLang.topic_create_title}:</label>
		<input type="text" id="topic_title" name="topic_title" value="{$_aRequest.topic_title}" class="width-full" />
		<small class="note">{$aLang.topic_create_title_notice}</small></p>


		{block name='add_topic_form_text_before'}{/block}


		{* Текст топика *}
		<label for="topic_text">{$aLang.topic_create_text}:</label>
		<textarea name="topic_text" id="topic_text" rows="20" class="js-editor width-full">{$_aRequest.topic_text}</textarea>

		{* Если визуальный редактор отключен выводим справку по разметке для обычного редактора *}
		{if ! $oConfig->GetValue('view.wysiwyg')}
			{include file='forms/editor.help.tpl' sTagsTargetId='topic_text'}
		{/if}


		{block name='add_topic_form_text_after'}{/block}
		

		{* Теги *}
		<p><label for="topic_tags">{$aLang.topic_create_tags}:</label>
		<input type="text" id="topic_tags" name="topic_tags" value="{$_aRequest.topic_tags}" class="width-full autocomplete-tags-sep" />
		<small class="note">{$aLang.topic_create_tags_notice}</small></p>


		{* Запретить комментарии *}
		<p><label><input type="checkbox" id="topic_forbid_comment" name="topic_forbid_comment" value="1" {if $_aRequest.topic_forbid_comment==1}checked{/if} />
		{$aLang.topic_create_forbid_comment}</label>
		<small class="note">{$aLang.topic_create_forbid_comment_notice}</small></p>


		{* Принудительный вывод топиков на главную (доступно только админам) *}
		{if $oUserCurrent->isAdministrator()}
			<p><label><input type="checkbox" id="topic_publish_index" name="topic_publish_index" value="1" {if $_aRequest.topic_publish_index==1}checked{/if} />
			{$aLang.topic_create_publish_index}</label>
			<small class="note">{$aLang.topic_create_publish_index_notice}</small></p>
		{/if}
		

		{block name='add_topic_form_end'}{/block}
		{hook run="form_add_topic_`$sTopicType`_end"}

		{* Скрытые поля *}
		<input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
		<input type="hidden" name="topic_type" value="{$sTopicType}" />
		

		{* Кнопки *}
		<button type="submit" name="submit_topic_publish" id="submit_topic_publish" class="button button-primary fl-r">
			{if $sEvent == 'add' or ($oTopicEdit and $oTopicEdit->getPublish() == 0)}
				{$aLang.topic_create_submit_publish}
			{else}
				{$aLang.topic_create_submit_update}
			{/if}
		</button>
		<button type="button" name="submit_preview" class="button js-topic-preview-text-button">{$aLang.topic_create_submit_preview}</button>
		<button type="submit" name="submit_topic_save" id="submit_topic_save" class="button">{$aLang.topic_create_submit_save}</button>
	</form>


	{* Блок с превью текста *}
	<div class="topic-preview" style="display: none;" id="topic-text-preview"></div>


	{block name='add_topic_end'}{/block}
	{hook run="add_topic_`$sTopicType`_end"}
{/block}