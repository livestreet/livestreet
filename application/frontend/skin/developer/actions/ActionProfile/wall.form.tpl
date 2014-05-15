{**
 * Стена / Форма добавления записи
 *
 * @param integer $iWallFormId          ID родительского поста
 * @param boolean $bWallFormDisplay     Отображать форму или нет
 * @param string  $sWallFormPlaceholder Плейсхолдер
 *}

<form class="wall-form js-wall-form {$sWallFormClasses}" data-id="{$iWallFormId|default:0}" {if ! $bWallFormDisplay|default:true}style="display: none"{/if}>
	{* Текст *}
	{include 'components/field/field.textarea.tpl'
			 sPlaceholder = "{if $sWallFormPlaceholder}{$sWallFormPlaceholder}{else}{$aLang.wall_add_title}{/if}"
			 sClasses     = 'width-full js-wall-form-text'}

	{* Подвал формы *}
	<footer class="wall-form-footer">
		{include 'components/button/button.tpl'
				 sMods   = 'primary'
				 sClasses = 'js-wall-form-submit'
				 sText    = $aLang.wall_add_submit}
	</footer>
</form>