<h2>{$aLang.blog_admin}: <a href="{router page='blog'}{$oBlogEdit->getUrl()}/">{$oBlogEdit->getTitle()}</a></h2>

<ul class="switcher">
	<li {if $sMenuItemSelect=='profile'}class="active"{/if}><a href="{router page='blog'}edit/{$oBlogEdit->getId()}/">{$aLang.blog_admin_profile}</a></li>
	<li {if $sMenuItemSelect=='admin'}class="active"{/if}><a href="{router page='blog'}admin/{$oBlogEdit->getId()}/">{$aLang.blog_admin_users}</a></li>

	{hook run='menu_blog_edit_admin_item'}
</ul>
{hook run='menu_blog_edit'}