{**
 * Пополняемый список пользователей
 *
 * @param array   $users
 * @param string  $title
 * @param string  $note
 * @param boolean $editable
 *
 * @param string $classes
 * @param array  $attributes
 * @param array  $mods
 *}

{* Название компонента *}
{$component = 'user-list-add'}
{component_define_params params=[ 'title', 'note', 'editable', 'users', 'mods', 'classes', 'attributes' ]}

{* Форма добавления *}
<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes}>
    {* Заголовок *}
    {if $title}
        <h3 class="{$component}-title">{$title}</h3>
    {/if}

    {* Описание *}
    {if $note}
        <p class="{$component}-note">{$note}</p>
    {/if}

    {* Форма добавления *}
    {if $editable|default:true}
        <form class="{$component}-form js-{$component}-form">
            {component 'user' template='choose'
                name    = 'add'
                classes = "js-{$component}-choose"
                label   = {lang 'user_list_add.form.fields.add.label'}}

            {component 'button' text={lang 'common.add'} mods='primary' classes="js-$component-form-submit"}
        </form>
    {/if}

    {* Список пользователей *}
    {* TODO: Изменить порядок вывода - сначало новые *}
    {block 'user_list_add_list'}
        {component 'user-list-add' template='list'
            hideableEmptyAlert = true
            users              = $users
            showActions        = true
            show               = !! $users
            classes            = "js-$component-users"
            itemClasses        = "js-$component-user"}
    {/block}
</div>