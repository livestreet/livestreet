
		<ul class="menu">
			<li class="active"><font color="#333333">{$aLang.talk_menu_inbox}</font>
				<ul class="sub-menu">					
					<li {if $sEvent=='inbox'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/">{$aLang.talk_menu_inbox_list}</a></div></li>
					<li {if $sEvent=='add'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/add/">{$aLang.talk_menu_inbox_create}</a></div></li>
				</ul>
			</li>
		</ul>