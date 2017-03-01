{**
 * Выбор пользователей
 *
 * @param string  $lang_choose
 *}

{$component = 'user-field-choose'}
{component_define_params params=[ 'name', 'label', 'lang_choose', 'mods', 'classes', 'attributes' ]}

{$label = $label|default:{lang 'user.choose.label'}}
{$lang_choose = $lang_choose|default:{lang 'user.choose.choose'}}

{* Ссылка показывающая мод. окно со списком пользователей *}
{capture 'user_field_choose'}
    <a href="#" class="ls-link-dotted js-{$component}-button">
        {$lang_choose}
    </a>
{/capture}

{component 'field.autocomplete'
    label         = $label
    name          = $name
    inputClasses  = 'js-user-field-choose-users ls-hidden'
    isMultiple    = true
    placeholder   = " "
    note          = $smarty.capture.user_field_choose
    params        = $params}