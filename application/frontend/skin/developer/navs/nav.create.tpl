{**
 * Навгиация создания топика
 *}

{$aTopicTypes = $LS->Topic_GetTopicTypes()}
{$aItems = []}

{foreach $aTopicTypes as $oTopicType}
	{$aItems[] = [ 'name' => $oTopicType->getCode(), 'url' => $oTopicType->getUrlForAdd(), 'text' => $oTopicType->getName() ]}
{/foreach}

{$aItems[] = [ 'name' => 'drafts', 'url' => "{router page='content'}drafts/", 'text' => $aLang.topic_menu_drafts, 'count' => $iUserCurrentCountTopicDraft ]}

{include 'components/nav/nav.tpl'
		 sName       = 'create'
		 sActiveItem = $sMenuSubItemSelect
		 sMods       = 'pills'
		 aItems      = $aItems}