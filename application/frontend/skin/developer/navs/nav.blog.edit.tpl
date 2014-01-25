{**
 * Навгиация редактирования блога
 *}

<ul class="nav nav-pills">
	<li {if $sMenuItemSelect=='profile'}class="active"{/if}><a href="{router page='blog'}edit/{$oBlogEdit->getId()}/">{$aLang.blog.admin.nav.profile}</a></li>
	<li {if $sMenuItemSelect=='admin'}class="active"{/if}><a href="{router page='blog'}admin/{$oBlogEdit->getId()}/">{$aLang.blog.admin.nav.users}</a></li>

	{hook run='menu_blog_edit_admin_item'}
</ul>

{hook run='menu_blog_edit'}