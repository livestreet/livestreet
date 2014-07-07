{**
 * Стена / Форма добавления записи
 *
 * @param integer $id          ID родительского поста
 * @param boolean $display     Отображать форму или нет
 * @param string  $placeholder Плейсхолдер
 *}

<form class="wall-form js-wall-form {$smarty.local.classes}" data-id="{$smarty.local.id|default:0}" {if ! $smarty.local.display|default:true}style="display: none"{/if}>
	{* Текст *}
	{include 'components/field/field.textarea.tpl'
			 sPlaceholder  = "{$smarty.local.placeholder|default:$aLang.wall.form.fields.text.placeholder}"
			 sInputClasses = 'width-full js-wall-form-text'}

	{* Подвал формы *}
	<footer class="wall-form-footer">
		{include 'components/button/button.tpl'
				 sType    = 'submit'
				 sMods    = 'primary'
				 sClasses = 'js-wall-form-submit'
				 sText    = $aLang.common.add}
	</footer>
</form>