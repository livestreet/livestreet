{**
 * Стена / Форма добавления записи
 *
 * @param integer $id          ID родительского поста
 * @param boolean $display     Отображать форму или нет
 * @param string  $placeholder Плейсхолдер
 *}

<form class="wall-form js-wall-form {$smarty.local.classes}" data-id="{$smarty.local.id|default:0}" {if ! $smarty.local.display|default:true}style="display: none"{/if}>
    {* Текст *}
    {component 'field' template='textarea'
        placeholder  = "{$smarty.local.placeholder|default:$aLang.wall.form.fields.text.placeholder}"
        inputClasses = 'width-full js-wall-form-text'}

    {* Подвал формы *}
    <footer class="wall-form-footer">
        {component 'button'
            type    = 'submit'
            mods    = 'primary'
            classes = 'js-wall-form-submit'
            text    = $aLang.common.add}
    </footer>
</form>