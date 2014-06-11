{**
 * Форма комментирования
 *
 * @param integer $iTargetId
 * @param string  $sTargetType
 * @param string  $sClasses          Дополнительные классы
 * @param string  $sAttributes       Атрибуты
 * @param string  $sMods             Модификаторы
 * @param string  $sEditorSet        (light) Стиль редактора
 *
 * @styles css/comments.css
 *}

{* Название компонента *}
{$sComponent = 'comment-form'}

{* Переменные *}
{$iTargetId = $smarty.local.iTargetId}
{$sTargetType = $smarty.local.sTargetType}


{* Форма *}
<form method           = "post"
	  class            = "{$sComponent} {mod name=$sComponent mods=$sMods} {$smarty.local.classes} js-comment-form"
	  enctype          = "multipart/form-data"
	  data-target-id   = "{$iTargetId}"
	  data-target-type = "{$sTargetType}"
	  {$smarty.local.sAttributes}>

	{block 'comment-form'}
		{hook run='comment-form-begin'}

		{block 'comment-form-fields'}
			{* Скрытые поля *}
			{include 'components/field/field.hidden.tpl' sName='reply' sValue='0' sId='form_comment_reply'}
			{include 'components/field/field.hidden.tpl' sName='cmt_target_id' sValue=$iTargetId}

			{* Текст комментария *}
			{include 'components/editor/editor.tpl'
				sSet             = $smarty.local.sEditorSet|default:'light'
				sName            = 'comment_text'
				sInputClasses    = 'js-comment-form-text'
				bShowHelp        = false
				sMediaTargetType = 'comment'}
		{/block}

		{hook run='comment-form-end'}

		{**
		 * Кнопки
		 *}

		{* Кнопка добавления *}
		{include 'components/button/button.tpl' sName='submit_comment' sText=$aLang.common.add sMods='primary' sClasses='js-comment-form-submit'}

		{* Кнопки редактирования *}
		{include 'components/button/button.tpl' sName='submit_comment' sType='button' sText=$aLang.common.save sMods='primary' sClasses='js-comment-form-update-submit hide'}
		{include 'components/button/button.tpl' sName='submit_comment' sType='button' sText=$aLang.common.cancel sClasses='js-comment-form-update-cancel fl-r hide'}

		{* Кнопка превью текста *}
		{include 'components/button/button.tpl' sText=$aLang.common.preview_text sType='button' sClasses='js-comment-form-preview'}
	{/block}
</form>