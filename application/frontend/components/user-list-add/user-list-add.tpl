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

{* Форма добавления *}
<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}"
     {cattr list=$smarty.local.attributes}>

    {* Заголовок *}
    {if $smarty.local.title}
        <h3 class="{$component}-title">{$smarty.local.title}</h3>
    {/if}

    {* Описание *}
    {if $smarty.local.note}
        <p class="{$component}-note">{$smarty.local.note}</p>
    {/if}

    {* Ссылка показывающая мод. окно со списком пользователей *}
    {capture 'user_list_add_choose'}
        <a href="#" class="link-dotted js-{$component}-choose">
            {lang 'user_list_add.choose'}
        </a>
    {/capture}

    {* Форма добавления *}
    {if $smarty.local.editable|default:true}
        <form class="{$component}-form js-{$component}-form">
            {component 'field' template='text'
                name         = 'add'
                inputClasses = "autocomplete-users-sep"
                label        = $aLang.user_list_add.form.fields.add.label
                note         = $smarty.capture.user_list_add_choose}

            {component 'button' text=$aLang.common.add mods='primary' classes="js-$component-form-submit"}
        </form>
    {/if}

    {* Список пользователей *}
    {* TODO: Изменить порядок вывода - сначало новые *}
    {block 'user_list_add_list'}
        {component 'user-list-add' template='list'
            hideableEmptyAlert = true
            users              = $smarty.local.users
            showActions        = true
            show               = !! $smarty.local.users
            classes            = "js-$component-users"
            itemClasses        = "js-$component-user"}
    {/block}
</div>