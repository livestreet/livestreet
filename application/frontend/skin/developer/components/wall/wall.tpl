{**
 * Стена
 *
 * @param array $posts      Посты
 * @param array $count      Общее кол-во постов на стене
 * @param array $lastId     ID последнего загруженного поста
 * @param array $classes    Доп-ые классы
 * @param array $mods       Модификаторы
 * @param array $attributes Атрибуты
 *}

{* Название компонента *}
{$component = 'wall'}

{$loadedCount = count($smarty.local.posts)}
{$moreCount = $smarty.local.count - $loadedCount}

{* Стена *}
<div class="{$component} {cmods name=$component mods=$smarty.local.mods} {$smarty.local.classes}" data-user-id="{$oUserProfile->getId()}" {cattr list=$smarty.local.attributes}>
    {* Форма добавления записи *}
    {if $oUserCurrent}
        {include './wall.form.tpl'}
    {else}
        {component 'alert' text=$aLang.wall.alerts.unregistered mods='info' classes='mt-15'}
    {/if}

    {* Список записей *}
    <div class="js-wall-entry-container" data-id="0">
        {include './wall.posts.tpl' posts=$smarty.local.posts}
    </div>

    {* Уведомление о пустом списке *}
    {if $oUserCurrent || ( ! $oUserCurrent && ! $loadedCount )}
        {component 'alert' text=$aLang.common.empty mods='empty' classes='mt-15 js-wall-alert-empty' attributes=[ 'id' => 'wall-empty' ] visible=!$loadedCount}
    {/if}

    {* Кнопка подгрузки записей *}
    {if $moreCount}
        {component 'more'
            classes    = 'js-wall-more'
            count      = $moreCount
            target     = '.js-wall-entry-container[data-id=0]'
            ajaxParams = [
                'last_id' => $smarty.local.lastId
            ]}
    {/if}
</div>