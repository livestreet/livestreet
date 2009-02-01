
		<ul class="menu">
			<li class="active"><font color="#333333">{$aLang.blog_admin}</font>
				<ul class="sub-menu">					
					<li {if $sMenuItemSelect=='profile'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/edit/{$oBlogEdit->getId()}/">{$aLang.blog_admin_profile}</a></div></li>
					<li {if $sMenuItemSelect=='admin'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/admin/{$oBlogEdit->getId()}/">{$aLang.blog_admin_users}</a></div></li>
				</ul>
			</li>
		</ul>