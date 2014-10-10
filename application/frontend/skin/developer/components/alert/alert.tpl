{**
 * Уведомления
 *
 * @param string  $title            Заголовок
 * @param mixed   $text             Массив либо строка с текстом уведомления
 * @param string  $mods (success)   Модификаторы (error, info и т.д.)
 * @param string  $attributes       Дополнительные атрибуты основного блока
 * @param string  $classes          Дополнительные классы
 * @param bool    $visible (true)   Показывать или нет уведомление
 * @param bool    $close (true)     Показывать или нет кнопку закрытия
 *}

{* Название компонента *}
{$component = 'alert'}

{* Уведомление *}
<div class="{$component} {mod name=$component mods=$mods default='success'} {$smarty.local.classes} js-{$component}"
    {if ! $smarty.local.visible|default:true}style="display: none"{/if}
    {$smarty.local.attributes}
    role="alert">

    {* Заголовок *}
    {if $smarty.local.title}
        <h4 class="{$component}-title">{$smarty.local.title}</h4>
    {/if}

    {* Кнопка закрытия *}
    {if $smarty.local.close}
        <div class="{$component}-close" data-type="alert-close">×</div>
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
</div>