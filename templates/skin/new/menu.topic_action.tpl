		<ul class="menu">
		
			<li {if $sMenuSubItemSelect=='add'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/{if $sMenuItemSelect=='add_blog'}{$ROUTE_PAGE_TOPIC}{else}{$sMenuItemSelect}{/if}/add/">{$aLang.topic_menu_add}</a>
				{if $sMenuSubItemSelect=='add'}
					<ul class="sub-menu" >
						<li {if $sMenuItemSelect=='topic'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOPIC}/{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_topic}</a></div></li>						
						<li {if $sMenuItemSelect=='question'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_QUESTION}/{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_question}</a></div></li>
						<li {if $sMenuItemSelect=='link'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_LINK}/{$sMenuSubItemSelect}/">{$aLang.topic_menu_add_link}</a></div></li>
						<li ><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/add/"><font color="Red">{$aLang.blog_menu_create}</font></a></div></li>
					</ul>
				{/if}
			</li>
			
			<li {if $sMenuSubItemSelect=='saved'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOPIC}/saved/">{$aLang.topic_menu_saved}</a> 				
			</li>
			
			<li {if $sMenuSubItemSelect=='published'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOPIC}/published/">{$aLang.topic_menu_published}</a>			
			</li>		
								
		</ul>
		
		
		

