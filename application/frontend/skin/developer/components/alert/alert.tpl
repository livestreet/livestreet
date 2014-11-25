{**
 * Уведомления
 *
 * @param string  $title            Заголовок
 * @param mixed   $text             Массив либо строка с текстом уведомления
 * @param string  $mods (success)   Модификаторы
 * @param string  $attributes       Дополнительные атрибуты основного блока
 * @param string  $classes          Дополнительные классы
 * @param bool    $visible (true)   Показывать или нет уведомление
 * @param bool    $close (false)    Показывать или нет кнопку закрытия
 *}

{* Название компонента *}
{$component = 'alert'}

{$visible = $smarty.local.visible|default:true}
{$mods = $smarty.local.mods}
{$uid = "{$component}{rand( 0, 10e10 )}"}

{if $smarty.local.close}
    {$mods = "$mods dismissible"}
{/if}

{* Уведомление *}
<div class="{$component} {cmods name=$component mods=$mods} {$smarty.local.classes} js-alert"
    {if ! $visible}hidden{/if}
    {cattr list=$smarty.local.attributes}
    role="alert">

    {* Заголовок *}
    {if $smarty.local.title}
        <h4 class="{$component}-title">{$smarty.local.title}</h4>
    {/if}

    {* Контент *}
    <div class="{$component}-body">
        {block 'alert_body'}
            {if is_array( $smarty.local.text )}
                <ul class="{$component}-list">
                    {foreach $smarty.local.text as $alert}
                        <li class="{$component}-list-item">
                            {if $alert.title}
                                <strong>{$alert.title}</strong>:
                            {/if}

                            {$alert.msg}
                        </li>
                    {/foreach}
                </ul>
            {else}
                {$smarty.local.text}
            {/if}
        {/block}
    </div>

    {* Кнопка закрытия *}
    {if $smarty.local.close}
        <button class="{$component}-close js-alert-close" aria-labelledby="{$uid}">
            <span class="icon-remove"></span>
            <span id="{$uid}" aria-hidden="true" hidden>{lang 'common.close'}</span>
        </button>
    {/if}
</div>