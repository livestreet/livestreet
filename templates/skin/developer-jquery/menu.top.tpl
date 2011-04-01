{if $sMenuItemSelect=='top'}
	<ul class="switcher">											
		<li {if $sMenuSubItemSelect=='blog'}class="active"{/if}><a href="{router page='top'}blog/">{$aLang.blog_menu_top_blog}</a></li>
		<li {if $sMenuSubItemSelect=='topic'}class="active"{/if}><a href="{router page='top'}topic/">{$aLang.blog_menu_top_topic}</a></li>
		<li {if $sMenuSubItemSelect=='comment'}class="active"{/if}><a href="{router page='top'}comment/">{$aLang.blog_menu_top_comment}</a></li>
		
		{hook run='menu_blog_top_item'}
	</ul>
{/if}