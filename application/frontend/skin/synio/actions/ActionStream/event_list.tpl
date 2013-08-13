{**
 * Список событий активности
 *
 * sActivityParams   Дополнительные параметры активности
 * sActivityType     Тип активности 
 *     all    Вся активность
 *     user   Активность пользователя
 *}

{if count($aStreamEvents)}
	<ul class="activity-event-list" id="activity-event-list">
		{include file='actions/ActionStream/events.tpl'}
	</ul>

	{if ! $bDisableGetMoreButton}
		<input type="hidden" id="activity-last-id" value="{$iStreamLastId}" />
		<div class="get-more" id="activity-get-more" data-param-type="{$sActivityType}" {$sActivityParams}>{$aLang.stream_get_more}</div>
	{/if}
{else}
	{$aLang.stream_no_events}
{/if}