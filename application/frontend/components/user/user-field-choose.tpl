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

{* TODO: i18n *}
{$label = $label|default:{lang 'user_list_add.form.fields.add.label'}}
{$lang_choose = $lang_choose|default:{lang 'user_list_add.choose'}}

{* Ссылка показывающая мод. окно со списком пользователей *}
{capture 'user_list_add_choose'}
    <a href="#" class="link-dotted js-{$component}-button">
        {$lang_choose}
    </a>
{/capture}

{component 'field' template='text'
    name         = $name
    inputClasses = "js-{$component}-text autocomplete-users-sep"
    label        = $label
    note         = $smarty.capture.user_list_add_choose
    params       = $smarty.local.params}