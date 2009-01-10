		<ul class="menu">
		
			<li {if $sMenuItemSelect=='index'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/">{$aLang.blog_menu_all}</a> {if $iCountTopicsNew>0}+{$iCountTopicsNew}{/if}
				{if $sMenuItemSelect=='index'}
					<ul class="sub-menu" >
						<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/">{$aLang.blog_menu_all_good}</a>{if $iCountTopicsNew>0} +{$iCountTopicsNew}{/if}</div></li>						
						<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_NEW}/">{$aLang.blog_menu_all_new}</a></div></li>
					</ul>
				{/if}
			</li>
			
			<li {if $sMenuItemSelect=='blog'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/">{$aLang.blog_menu_collective}</a> {if $iCountTopicsCollectiveNew>0}+{$iCountTopicsCollectiveNew}{/if}
				{if $sMenuItemSelect=='blog'}
					<ul class="sub-menu" >											
						<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><div><a href="{$sMenuSubBlogUrl}/">{$aLang.blog_menu_collective_good}</a></div></li>
						<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><div><a href="{$sMenuSubBlogUrl}/new/">{$aLang.blog_menu_collective_new}</a>{if $iCountTopicsBlogNew>0} +{$iCountTopicsBlogNew}{/if}</div></li>
						<li {if $sMenuSubItemSelect=='bad'}class="active"{/if}><div><a href="{$sMenuSubBlogUrl}/bad/">{$aLang.blog_menu_collective_bad}</a></div></li>
					</ul>
				{/if}
			</li>
			
			<li {if $sMenuItemSelect=='log'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PERSONAL_BLOG}/">{$aLang.blog_menu_personal}</a> {if $iCountTopicsPersonalNew>0}+{$iCountTopicsPersonalNew}{/if}
				{if $sMenuItemSelect=='log'}
					<ul class="sub-menu" style="left: -50px;">											
						<li {if $sMenuSubItemSelect=='good'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PERSONAL_BLOG}/">{$aLang.blog_menu_personal_good}</a></div></li>
						<li {if $sMenuSubItemSelect=='new'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PERSONAL_BLOG}/new/">{$aLang.blog_menu_personal_new}</a>{if $iCountTopicsPersonalNew>0} +{$iCountTopicsPersonalNew}{/if}</div></li>
						<li {if $sMenuSubItemSelect=='bad'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PERSONAL_BLOG}/bad/">{$aLang.blog_menu_personal_bad}</a></div></li>
					</ul>
				{/if}
			</li>
			
			<li {if $sMenuItemSelect=='top'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/">{$aLang.blog_menu_top}</a>
				{if $sMenuItemSelect=='top'}
					<ul class="sub-menu" style="left: -80px;">											
						<li {if $sMenuSubItemSelect=='blog'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/blog/">{$aLang.blog_menu_top_blog}</a></div></li>
						<li {if $sMenuSubItemSelect=='topic'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/topic/">{$aLang.blog_menu_top_topic}</a></div></li>
						<li {if $sMenuSubItemSelect=='comment'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/comment/">{$aLang.blog_menu_top_comment}</a></div></li>
					</ul>
				{/if}
			</li>
								
		</ul>
		
		
		

