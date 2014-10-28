{**
 * Форма комментирования
 *
 * @param integer $iTargetId
 * @param string  $sTargetType
 * @param string  $classes          Дополнительные классы
 * @param string  $attributes       Атрибуты
 * @param string  $mods             Модификаторы
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
	  class            = "{$sComponent} {mod name=$sComponent mods=$mods} {$smarty.local.classes} js-comment-form"
	  enctype          = "multipart/form-data"
	  data-target-id   = "{$iTargetId}"
	  data-target-type = "{$sTargetType}"
	  {$smarty.local.attributes}>

	{block 'comment-form'}
		{hook run='comment-form-begin'}

		{block 'comment-form-fields'}
			{* Скрытые поля *}
			{include 'components/field/field.hidden.tpl' name='reply' value='0' id='form_comment_reply'}
			{include 'components/field/field.hidden.tpl' name='cmt_target_id' value=$iTargetId}

			{* Текст комментария *}
			{include 'components/editor/editor.tpl'
				set             = $smarty.local.sEditorSet|default:'light'
				name            = 'comment_text'
				inputClasses    = 'js-editor-default js-comment-form-text'
				help            = false
				mediaTargetType = 'comment'}
		{/block}

		{hook run='comment-form-end'}

		{**
		 * Кнопки
		 *}

		{* Кнопка добавления *}
		{include 'components/button/button.tpl' name='submit_comment' text=$aLang.common.add mods='primary' classes='js-comment-form-submit'}

		{* Кнопки редактирования *}
		{include 'components/button/button.tpl' name='submit_comment' type='button' text=$aLang.common.save mods='primary' classes='js-comment-form-update-submit hide'}
		{include 'components/button/button.tpl' name='submit_comment' type='button' text=$aLang.common.cancel classes='js-comment-form-update-cancel fl-r hide'}

		{* Кнопка превью текста *}
		{include 'components/button/button.tpl' text=$aLang.common.preview_text type='button' classes='js-comment-form-preview'}
	{/block}
</form>