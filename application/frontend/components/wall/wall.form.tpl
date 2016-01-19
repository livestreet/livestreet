{**
 * Стена / Форма добавления записи
 *
 * @param integer $id          ID родительского поста
 * @param boolean $display     Отображать форму или нет
 * @param string  $placeholder Плейсхолдер
 *}

{component_define_params params=[ 'classes', 'id', 'display', 'placeholder' ]}

<form class="wall-form js-wall-form {$classes}" data-id="{$id|default:0}" {if ! $display|default:true}style="display: none"{/if}>
    {* Текст *}
    {component 'field' template='textarea'
        placeholder  = "{$placeholder|default:$aLang.wall.form.fields.text.placeholder}"
        inputClasses = 'ls-width-full js-wall-form-text'}

    {* Подвал формы *}
    <footer class="wall-form-footer">
        {component 'button'
            type    = 'submit'
            mods    = 'primary'
            classes = 'js-wall-form-submit'
            text    = $aLang.common.add}
    </footer>
</form>