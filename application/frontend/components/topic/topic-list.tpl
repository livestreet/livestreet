{**
 * Список топиков
 *
 * @param array $topics
 * @param array $paging
 *}

{$topics = $smarty.local.topics}
{$paging = $smarty.local.paging}

{if $topics}
	{add_block group='toolbar' name='component@toolbar-scrollnav.toolbar.scrollnav' show=count( $topics )}

	{foreach $topics as $topic}
		{include './topic-type.tpl' topic=$topic isList=true}
	{/foreach}

	{component 'pagination' total=+$paging.iCountPage current=+$paging.iCurrentPage url="{$paging.sBaseUrl}/page__page__/{$paging.sGetParams}" classes='js-pagination-topics'}
{else}
	{component 'alert' text=$aLang.common.empty mods='empty'}
{/if}