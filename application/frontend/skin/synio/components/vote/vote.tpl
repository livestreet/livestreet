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

{* Параметры для тестирования *}
{component_define_params params=[ 'targetId', 'rating']}

{* Установка дефолтных значений *}
{$showRating = $showRating|default:true}

{* Рейтинг *}
{$rating = $rating|default:$target->getRating()}
{$_id = $targetId|default:$target->getId()}
{$_vote = $target->getVote()}
{$_direction = ($_vote) ? $_vote->getDirection() : 0}


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

{if $_vote}
    {$mods = "$mods voted"}

    {if $_direction > 0}
        {$mods = "$mods voted-up"}
    {elseif $_direction < 0}
        {$mods = "$mods voted-down"}
    {else}
        {$mods = "$mods voted-abstain"}
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

{if ! in_array( 'small', explode(' ', $mods) )}
    {$mods = "$mods default"}
{/if}

{block 'vote_options'}{/block}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes} data-param-i-target-id="{$_id}">
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

            {* Нравится *}
            <div class="{$component}-item {$component}-item-up js-vote-item" {if ! $_vote}title="{$aLang.$component.up}"{/if} data-vote-value="1"></div>

            {* Воздержаться *}
            {if $useAbstain}
                <div class="{$component}-item {$component}-item-abstain js-vote-item" {if ! $_vote}title="{$aLang.$component.abstain}"{/if} data-vote-value="0"></div>
            {/if}

            {* Не нравится *}
            <div class="{$component}-item {$component}-item-down js-vote-item" {if ! $_vote}title="{$aLang.$component.down}"{/if} data-vote-value="-1"></div>
        {/block}
    </div>
</div>