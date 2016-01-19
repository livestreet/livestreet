{**
 * Голосование
 *
 * @param object  $target     Объект сущности
 * @param boolean $showRating Показывать рейтинг или нет
 * @param boolean $isLocked   Блокировка голосования
 * @param boolean $useAbstain
 *}

{* Название компонента *}
{$component = 'ls-vote'}
{component_define_params params=[ 'showRating', 'target', 'isLocked', 'useAbstain', 'mods', 'classes', 'attributes' ]}

{* Установка дефолтных значений *}
{$showRating = $showRating|default:true}

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

{if ! $oUserCurrent || $isLocked}
    {$mods = "$mods locked"}
{/if}

{if ! $showRating}
    {$mods = "$mods rating-hidden"}
{/if}

{* Дополнительный мод-ор для иконок *}
{$iconMod = ( in_array( 'small', explode(' ', $mods) ) ) ? 'white' : ''}

{block 'vote_options'}{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes} data-param-i-target-id="{$target->getId()}">
    {* Основной блок *}
    <div class="{$component}-body">
        {block 'vote_body'}
            {* Рейтинг *}
            <div class="{$component}-rating js-vote-rating">
                {if $showRating}
                    {$rating}
                {else}
                    ?
                {/if}
            </div>

            {* Воздержаться *}
            {if $useAbstain}
                <div class="{$component}-item {$component}-item-abstain js-vote-item" {if ! $vote}title="{$aLang.$component.abstain}"{/if} data-vote-value="0">
                    {component 'icon' icon='eye' mods=$iconMod}
                </div>
            {/if}

            {* Нравится *}
            <div class="{$component}-item {$component}-item-up js-vote-item" {if ! $vote}title="{$aLang.$component.up}"{/if} data-vote-value="1">
                {component 'icon' icon='plus' mods=$iconMod}
            </div>

            {* Не нравится *}
            <div class="{$component}-item {$component}-item-down js-vote-item" {if ! $vote}title="{$aLang.$component.down}"{/if} data-vote-value="-1">
                {component 'icon' icon='minus' mods=$iconMod}
            </div>
        {/block}
    </div>
</div>