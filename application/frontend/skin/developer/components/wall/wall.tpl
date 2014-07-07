{**
 * Стена
 *
 * @param array $posts   Посты
 * @param array $count   Общее кол-во постов на стене
 * @param array $lastId  ID последнего загруженного поста
 * @param array $classes Доп-ые классы
 *
 * TODO: Локальные переменные
 *}

{* Название компонента *}
{$component = 'wall'}

{$loadedCount = count($posts)}
{$moreCount = $count - $loadedCount}

{* Стена *}
<div class="{$component} {mod name=$component mods=$mods} {$classes}" data-user-id="{$oUserProfile->getId()}">
	{* Форма добавления записи *}
	{if $oUserCurrent}
		{include './wall.form.tpl'}
	{else}
		{include 'components/alert/alert.tpl' sMods='info' sClasses='mt-15' mAlerts=$aLang.wall.alerts.unregistered}
	{/if}

	{* Список записей *}
	<div class="js-wall-entry-container" data-id="0">
		{include './wall.posts.tpl' posts=$posts}
	</div>

	{* Уведомление о пустом списке *}
	{if $oUserCurrent || ( ! $oUserCurrent && ! $loadedCount )}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.common.empty sMods='empty' sClasses='mt-15 js-wall-alert-empty' sAttributes='id="wall-empty"' bVisible=!$loadedCount}
	{/if}

	{* Кнопка подгрузки записей *}
	{if $moreCount}
		{include 'components/more/more.tpl'
				 sClasses    = 'js-wall-more'
				 iCount      = $moreCount
				 sAttributes = "data-more-target=\".js-wall-entry-container[data-id=0]\" data-proxy-last_id=\"{$lastId}\""}
	{/if}
</div>