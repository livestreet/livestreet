{**
 * Список топиков
 *
 * @param array $topics
 * @param array $paging
 *}

{$topics = $smarty.local.topics}

{if $topics}
	{add_block group='toolbar' name='components/toolbar-scrollnav/toolbar.scrollnav.tpl' show=count( $topics )}

	{foreach $topics as $topic}
		{include './topic-type.tpl' topic=$topic isList=true}
	{/foreach}

	{include 'components/pagination/pagination.tpl' paging=$smarty.local.paging classes='js-pagination-topics'}
{else}
	{include 'components/alert/alert.tpl' text=$aLang.common.empty mods='empty'}
{/if}