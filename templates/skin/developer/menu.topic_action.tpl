<ul class="menu">
	<li {if $sMenuSubItemSelect=='add'}class="active"{/if}>
		<a href="{cfg name='path.root.web'}/{if $sMenuItemSelect=='add_blog'}topic{else}{$sMenuItemSelect}{/if}/add/">{$aLang.topic_menu_add}</a>
		{if $sMenuSubItemSelect=='add'}
			<ul class="sub-menu" >
				<li {if $sMenuItemSelect=='topic'}class="active"{/if}><a href="{router page='topic'}{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_topic}</a></li>
				<li {if $sMenuItemSelect=='question'}class="active"{/if}><a href="{router page='question'}{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_question}</a></li>
				<li {if $sMenuItemSelect=='link'}class="active"{/if}><a href="{router page='link'}{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_link}</a></li>
				<li ><a href="{router page='blog'}add/">{$aLang.blog_menu_create}</a></li>
			</ul>
		{/if}
	</li>

	<li {if $sMenuSubItemSelect=='saved'}class="active"{/if}>
		<a href="{router page='topic'}saved/">{$aLang.topic_menu_saved}</a>
	</li>

	<li {if $sMenuSubItemSelect=='published'}class="active"{/if}>
		<a href="{router page='topic'}published/">{$aLang.topic_menu_published}</a>
	</li>

	{hook run='menu_topic_action'}
</ul>
		
		
		

