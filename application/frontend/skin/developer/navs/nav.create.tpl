{**
 * Навгиация создания топика
 *}

{if $sMenuItemSelect == 'topic'}
	<ul class="nav nav-pills mb-30">
		{$aTopicTypes=$LS->Topic_GetTopicTypes()}
		{foreach $aTopicTypes as $oTopicType}
			<li {if $sMenuSubItemSelect==$oTopicType->getCode()}class="active"{/if}><a href="{$oTopicType->getUrlForAdd()}">{$oTopicType->getName()}</a></li>
		{/foreach}

		{hook run='menu_create_topic_item'}

		{if $iUserCurrentCountTopicDraft}
			<li class="{if $sMenuSubItemSelect == 'drafts'}active{/if}"><a href="{router page='content'}drafts/">{$aLang.topic_menu_drafts} ({$iUserCurrentCountTopicDraft})</a></li>
		{/if}
	</ul>
{/if}

{hook run='menu_create' sMenuItemSelect=$sMenuItemSelect sMenuSubItemSelect=$sMenuSubItemSelect}