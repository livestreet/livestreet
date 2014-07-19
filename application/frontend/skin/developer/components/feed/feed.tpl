{**
 * Лента
 *
 * @param array   $topics
 * @param integer $count
 *}

{$component = 'feed'}

{$topics = $smarty.local.topics}

<div class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
	{if $topics}
		{* Список *}
		<ul class="{$component}-topic-list js-{$component}-topic-list">
			{include 'topics/topic_list.tpl' aTopics=$topics}
		</ul>

		{* Кнопка подгрузки *}
		{* TODO: if $smarty.local.count > Config::Get('module.userfeed.count_default') *}
		{if count($topics) == Config::Get('module.userfeed.count_default')}
			{$last = end($topics)}

			{include 'components/more/more.tpl'
					 iCount      = $smarty.local._count
					 sClasses    = "js-{$component}-more"
					 sAttributes = "data-proxy-last_id=\"{$last->getId()}\""}
		{/if}
	{else}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.common.empty sMods='empty'}
	{/if}
</div>