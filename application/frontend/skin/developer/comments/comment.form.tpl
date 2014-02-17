{**
 * Форма комментирования
 *
 * @param integer $iTargetId
 * @param string $sTargetType
 *
 * @styles css/comments.css
 *}

{* Подключение редактора *}
{include 'forms/editor.init.tpl' sEditorType='comment' sMediaTargetType='comment' }

{* Форма *}
<form method="post" class="comment-form js-comment-form" enctype="multipart/form-data" data-target-id="{$iTargetId}" data-target-type="{$sTargetType}">
	{hook run='form_add_comment_begin'}

	{* Текст комментария *}
	{include 'forms/fields/form.field.textarea.tpl' sFieldName='comment_text' sFieldId='form_comment_text' sFieldClasses='width-full js-editor'}

	{hook run='form_add_comment_end'}

	{* Скрытые поля *}
	{include 'forms/fields/form.field.hidden.tpl' sFieldName='reply' sFieldValue='0' sFieldId='form_comment_reply'}
	{include 'forms/fields/form.field.hidden.tpl' sFieldName='cmt_target_id' sFieldValue=$iTargetId}

	{* Кнопки *}
	{include 'forms/fields/form.field.button.tpl' sFieldName='submit_comment' sFieldText=$aLang.common.add sFieldStyle='primary' sFieldClasses='js-comment-form-submit'}
	{include 'forms/fields/form.field.button.tpl' sFieldText=$aLang.common.preview_text sFieldType='button' sFieldClasses='js-comment-form-preview'}
</form>