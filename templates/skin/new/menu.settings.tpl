		<ul class="menu">
		
			<li {if $sMenuItemSelect=='settings'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/">{$aLang.settings_menu}</a>
				{if $sMenuItemSelect=='settings'}
					<ul class="sub-menu" >
						<li {if $sMenuSubItemSelect=='profile'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SETTINGS}/profile/">{$aLang.settings_menu_profile}</a></div></li>						
						<li {if $sMenuSubItemSelect=='tuning'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SETTINGS}/tuning/">{$aLang.settings_menu_tuning}</a></div></li>
					</ul>
				{/if}
			</li>
			
			{if $USER_USE_INVITE}
			<li {if $sMenuItemSelect=='invite'}class="active"{/if}>
				<a href="{$DIR_WEB_ROOT}/">{$aLang.settings_menu_invite}</a>
				
			</li>
			{/if}		
						
		</ul>
		
		
		

