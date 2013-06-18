{if $sMenuItemSelect == 'topic'}
	<ul class="nav nav-pills mb-30">
		<li {if $sMenuSubItemSelect=='topic'}class="active"{/if}><a href="{router page='topic'}add/">{$aLang.topic_menu_add_topic}</a></li>
		<li {if $sMenuSubItemSelect=='question'}class="active"{/if}><a href="{router page='question'}add/">{$aLang.topic_menu_add_question}</a></li>
		<li {if $sMenuSubItemSelect=='link'}class="active"{/if}><a href="{router page='link'}add/">{$aLang.topic_menu_add_link}</a></li>
		<li {if $sMenuSubItemSelect=='photoset'}class="active"{/if}><a href="{router page='photoset'}add/">{$aLang.topic_menu_add_photoset}</a></li>
		
		{hook run='menu_create_topic_item'}

		{if $iUserCurrentCountTopicDraft}
			<li class="{if $sMenuSubItemSelect=='saved'}active{/if}"><a href="{router page='topic'}saved/">{$aLang.topic_menu_saved} ({$iUserCurrentCountTopicDraft})</a></li>
		{/if}
	</ul>
{/if}

{hook run='menu_create' sMenuItemSelect=$sMenuItemSelect sMenuSubItemSelect=$sMenuSubItemSelect}