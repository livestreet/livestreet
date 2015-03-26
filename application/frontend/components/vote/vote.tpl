{**
 * Голосование
 *
 * @param object  $target     Объект сущности
 * @param string  $classes    Дополнительные классы
 * @param string  $attributes Атрибуты
 * @param boolean $showRating Показывать рейтинг или нет
 * @param boolean $isLocked   Блокировка голосования
 *
 * TODO: Добавить смарти блоки
 *}

{* Название компонента *}
{$component = 'vote'}

{* Установка дефолтных значений *}
{$showRating = $smarty.local.showRating|default:true}
{$target = $smarty.local.target}
{$mods = $smarty.local.mods}

{* Рейтинг *}
{$rating = $target->getRating()}

{* Получаем модификаторы *}
{if $showRating}
    {if $rating > 0}
        {$mods = "$mods count-positive"}
    {elseif $rating < 0}
        {$mods = "$mods count-negative"}
    {else}
        {$mods = "$mods count-zero"}
    {/if}
{/if}

{if $vote = $target->getVote()}
    {$mods = "$mods voted"}

    {if $vote->getDirection() > 0}
        {$mods = "$mods voted-up"}
    {elseif $vote->getDirection() < 0}
        {$mods = "$mods voted-down"}
    {else}
        {$mods = "$mods voted-zero"}
    {/if}
{else}
    {$mods = "$mods not-voted"}
{/if}

{if ! $oUserCurrent || $smarty.local.isLocked}
    {$mods = "$mods locked"}
{/if}

{if ! $showRating}
    {$mods = "$mods rating-hidden"}
{/if}

{* Дополнительный мод-ор для иконок *}
{$iconMod = ( in_array( 'small', explode(' ', $mods) ) ) ? 'white' : ''}


<div class="{$component} {cmods name=$component mods=$mods} {$smarty.local.classes}" data-param-i-target-id="{$target->getId()}" {cattr list=$smarty.local.attributes}>
    {* Заголовок *}
    {if $showLabel}
        <h4 class="{$component}-heading">{$aLang.$component.rating}</h4>
    {/if}

    {* Основной блок *}
    <div class="{$component}-body">
        {* Рейтинг *}
        <div class="{$component}-rating js-{$component}-rating">
            {if $showRating}
                {$rating}
            {else}
                ?
            {/if}
        </div>

        {* Воздержаться *}
        {if $smarty.local.useAbstain}
            <div class="{$component}-item {$component}-item-abstain js-{$component}-item" {if ! $vote}title="{$aLang.$component.abstain}"{/if} data-vote-value="0">
                {component 'icon' icon='eye-open' mods=$iconMod}
            </div>
        {/if}

        {* Нравится *}
        <div class="{$component}-item {$component}-item-up js-{$component}-item" {if ! $vote}title="{$aLang.$component.up}"{/if} data-vote-value="1">
            {component 'icon' icon='plus' mods=$iconMod}
        </div>

        {* Не нравится *}
        <div class="{$component}-item {$component}-item-down js-{$component}-item" {if ! $vote}title="{$aLang.$component.down}"{/if} data-vote-value="-1">
            {component 'icon' icon='minus' mods=$iconMod}
        </div>
    </div>
</div>