{**
 * Выбор пользователей
 *
 * @param string  $lang_choose
 *}

{$component = 'user-field-choose'}

{* Генерируем копии локальных переменных, *}
{* чтобы их можно было изменять в дочерних шаблонах *}
{foreach [ 'name', 'label', 'lang_choose', 'mods', 'classes', 'attributes' ] as $param}
    {assign var="$param" value=$smarty.local.$param}
{/foreach}

{$label = $label|default:{lang 'user.choose.label'}}
{$lang_choose = $lang_choose|default:{lang 'user.choose.choose'}}

{* Ссылка показывающая мод. окно со списком пользователей *}
{capture 'user_field_choose'}
    <a href="#" class="link-dotted js-{$component}-button">
        {$lang_choose}
    </a>
{/capture}

{component 'field' template='select'
    label         = $label
    name          = $name
    inputClasses  = 'js-user-field-choose-users'
    isMultiple    = true
    placeholder   = " "
    note          = $smarty.capture.user_field_choose
    params        = $smarty.local.params}