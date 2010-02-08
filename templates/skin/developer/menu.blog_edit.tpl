<ul class="menu">
	<li class="active"><a href="{router page='blog'}edit/{$oBlogEdit->getId()}/">{$aLang.blog_admin}</a>
		<ul class="sub-menu">					
			<li {if $sMenuItemSelect=='profile'}class="active"{/if}><a href="{router page='blog'}edit/{$oBlogEdit->getId()}/">{$aLang.blog_admin_profile}</a></li>
			<li {if $sMenuItemSelect=='admin'}class="active"{/if}><a href="{router page='blog'}admin/{$oBlogEdit->getId()}/">{$aLang.blog_admin_users}</a></li>	
		</ul>
	</li>
</ul>