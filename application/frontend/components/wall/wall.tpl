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
{component_define_params params=[ 'count', 'posts', 'lastId', 'mods', 'classes', 'attributes' ]}

{$loadedCount = count($posts)}
{$moreCount = $count - $loadedCount}

{* Стена *}
<div class="{$component} {cmods name=$component mods=$mods} {$classes}" {cattr list=$attributes} data-user-id="{$oUserProfile->getId()}">
    {* Форма добавления записи *}
    {if $oUserCurrent}
        {component 'wall' template='form'}
    {else}
        {component 'alert' text=$aLang.wall.alerts.unregistered mods='info' classes='ls-mt-15'}
    {/if}

    {* Список записей *}
    <div class="js-wall-entry-container" data-id="0">
        {component 'wall' template='posts' posts=$posts}
    </div>

    {* Уведомление о пустом списке *}
    {if $oUserCurrent || ( ! $oUserCurrent && ! $loadedCount )}
        {component 'blankslate' text=$aLang.common.empty classes='ls-mt-15 js-wall-alert-empty' attributes=[ 'id' => 'wall-empty' ] visible=!$loadedCount}
    {/if}

    {* Кнопка подгрузки записей *}
    {if $moreCount}
        {component 'more'
            classes    = 'js-wall-more'
            count      = $moreCount
            target     = '.js-wall-entry-container[data-id=0]'
            ajaxParams = [
                'last_id' => $lastId
            ]}
    {/if}
</div>