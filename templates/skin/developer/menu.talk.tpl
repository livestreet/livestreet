<ul class="menu">
	<li class="active"><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/">{$aLang.talk_menu_inbox}</a>
		<ul class="sub-menu">					
			<li {if $sEvent=='inbox'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/">{$aLang.talk_menu_inbox_list}</a></li>
			<li {if $sEvent=='add'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/add/">{$aLang.talk_menu_inbox_create}</a></li>
		</ul>
	</li>
</ul>