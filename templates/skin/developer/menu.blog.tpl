<ul class="menu">
	<li {if $sMenuItemSelect=='index'}class="active"{/if}>
		<a href="{cfg name='path.root.web'}/">{$aLang.blog_menu_all}</a> {if $iCountTopicsNew>0}+{$iCountTopicsNew}{/if}
		{if $sMenuItemSelect=='index'}
			<ul class="sub-menu">
				<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><a href="{cfg name='path.root.web'}/">{$aLang.blog_menu_all_good}</a></li>
				{if $iCountTopicsNew>0}<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><a href="{router page='new'}">{$aLang.blog_menu_all_new} +{$iCountTopicsNew}</a></li>{/if}
				{hook run='menu_blog_index_item'}
			</ul>
		{/if}
	</li>

	<li {if $sMenuItemSelect=='blog'}class="active"{/if}>
		<a href="{router page='blog'}">{$aLang.blog_menu_collective}</a> {if $iCountTopicsCollectiveNew>0}+{$iCountTopicsCollectiveNew}{/if}
		{if $sMenuItemSelect=='blog'}
			<ul class="sub-menu">
				<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><a href="{$sMenuSubBlogUrl}">{$aLang.blog_menu_collective_good}</a></li>
				{if $iCountTopicsBlogNew>0}<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><a href="{$sMenuSubBlogUrl}new/">{$aLang.blog_menu_collective_new}</a> +{$iCountTopicsBlogNew}</li>{/if}
				<li {if $sMenuSubItemSelect=='bad'}class="active"{/if}><a href="{$sMenuSubBlogUrl}bad/">{$aLang.blog_menu_collective_bad}</a></li>
				{hook run='menu_blog_blog_item'}
			</ul>
		{/if}
	</li>

	<li {if $sMenuItemSelect=='log'}class="active"{/if}>
		<a href="{router page='personal_blog'}">{$aLang.blog_menu_personal}</a> {if $iCountTopicsPersonalNew>0}+{$iCountTopicsPersonalNew}{/if}
		{if $sMenuItemSelect=='log'}
			<ul class="sub-menu">
				<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><a href="{router page='personal_blog'}">{$aLang.blog_menu_personal_good}</a></li>
				{if $iCountTopicsPersonalNew>0}<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><a href="{router page='personal_blog'}new/">{$aLang.blog_menu_personal_new}</a> +{$iCountTopicsPersonalNew}</li>{/if}
				<li {if $sMenuSubItemSelect=='bad'}class="active"{/if}><a href="{router page='personal_blog'}bad/">{$aLang.blog_menu_personal_bad}</a></li>
				{hook run='menu_blog_log_item'}
			</ul>
		{/if}
	</li>

	<li {if $sMenuItemSelect=='top'}class="active"{/if}>
		<a href="{router page='top'}">{$aLang.blog_menu_top}</a>
		{if $sMenuItemSelect=='top'}
			<ul class="sub-menu" style="left: -112px;">											
				<li {if $sMenuSubItemSelect=='blog'}class="active"{/if}><a href="{router page='top'}blog/">{$aLang.blog_menu_top_blog}</a></li>
				<li {if $sMenuSubItemSelect=='topic'}class="active"{/if}><a href="{router page='top'}topic/">{$aLang.blog_menu_top_topic}</a></li>
				<li {if $sMenuSubItemSelect=='comment'}class="active"{/if}><a href="{router page='top'}comment/">{$aLang.blog_menu_top_comment}</a></li>
				{hook run='menu_blog_top_item'}
			</ul>
		{/if}
	</li>

	{hook run='menu_blog'}
</ul>