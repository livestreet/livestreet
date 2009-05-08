<ul class="menu">
	<li class="active">{$aLang.blog_admin}
		<ul class="sub-menu">					
			<li {if $sMenuItemSelect=='profile'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/edit/{$oBlogEdit->getId()}/">{$aLang.blog_admin_profile}</a></li>
			<li {if $sMenuItemSelect=='admin'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/admin/{$oBlogEdit->getId()}/">{$aLang.blog_admin_users}</a></li>
		</ul>
	</li>
</ul>