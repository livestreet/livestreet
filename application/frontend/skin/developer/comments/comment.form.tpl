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
	{include 'components/field/field.textarea.tpl' sName='comment_text' sId='form_comment_text' sInputClasses='js-editor'}

	{hook run='form_add_comment_end'}

	{* Скрытые поля *}
	{include 'components/field/field.hidden.tpl' sName='reply' sValue='0' sId='form_comment_reply'}
	{include 'components/field/field.hidden.tpl' sName='cmt_target_id' sValue=$iTargetId}

	{* Кнопки создания *}
	{include 'components/button/button.tpl' sName='submit_comment' sText=$aLang.common.add sMods='primary' sClasses='js-comment-form-submit'}

	{* Кнопки редактирования *}
	{include 'components/button/button.tpl' sName='submit_comment' sType='button' sText=$aLang.common.save sMods='primary' sClasses='js-comment-form-update-submit hide'}
	{include 'components/button/button.tpl' sName='submit_comment' sType='button' sText=$aLang.common.cancel sClasses='js-comment-form-update-cancel  fl-r hide'}

	{* Общие кнопки *}
	{include 'components/button/button.tpl' sText=$aLang.common.preview_text sType='button' sClasses='js-comment-form-preview'}
</form>