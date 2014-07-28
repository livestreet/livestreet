{**
 * Список топиков
 *
 * @param array $topics
 * @param array $paging
 *}

{$topics = $smarty.local.topics}

{if $topics}
	{add_block group='toolbar' name='toolbar/toolbar.topic.tpl' show=count( $topics )}

	{foreach $topics as $topic}
		{include './topic-type.tpl' topic=$topic isList=true}
	{/foreach}

	{include 'components/pagination/pagination.tpl' aPaging=$smarty.local.paging sClasses='js-pagination-topics'}
{else}
	{include 'components/alert/alert.tpl' mAlerts=$aLang.common.empty sMods='empty'}
{/if}