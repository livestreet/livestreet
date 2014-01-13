{**
 * Стена / Форма добавления записи
 *
 * @param integer $iWallFormId          ID родительского поста
 * @param boolean $bWallFormDisplay     Отображать форму или нет
 * @param string  $sWallFormPlaceholder Плейсхолдер
 *}

{if ! isset($iWallFormId)}{$iWallFormId = 0}{/if}
{if ! isset($bWallFormDisplay)}{$bWallFormDisplay = true}{/if}

<form class="wall-form js-wall-form {$sWallFormClasses}" data-id="{$iWallFormId}"  {if ! $bWallFormDisplay}style="display: none"{/if}>
	{* Текст *}
	{include 'forms/fields/form.field.textarea.tpl'
			 sFieldPlaceholder = "{if $sWallFormPlaceholder}{$sWallFormPlaceholder}{else}{$aLang.wall_add_title}{/if}"
			 sFieldClasses     = 'width-full js-wall-form-text'}

	{* Подвал формы *}
	<footer class="wall-form-footer">
		{include 'forms/fields/form.field.button.tpl'
				 sFieldStyle   = 'primary'
				 sFieldClasses = 'js-wall-form-submit'
				 sFieldText    = $aLang.wall_add_submit}
	</footer>
</form>