	<!-- Navigation -->
	<div id="nav">
		<div class="left"></div>
		{if $oUserCurrent and $sAction==$ROUTE_PAGE_BLOG}
			<div class="write">
				<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOPIC}/add/" alt="{$aLang.topic_create}" title="{$aLang.topic_create}" class="button small">
					<span><em>{$aLang.topic_create}</em></span>
				</a>
			</div>
		{/if}
		
		{if $menu}
			{include file=menu.$menu.tpl}
		{/if}
	
				
		<div class="right"></div>
		<!--<a href="#" class="rss" onclick="return false;"></a>-->
		<div class="search">
			<form action="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_SEARCH}/topics/" method="GET">
				<input class="text" type="text" onblur="if (!value) value=defaultValue" onclick="if (value==defaultValue) value=''" value="{$aLang.search}" name="q" />
				<input class="button" type="submit" value="" />
			</form>
		</div>
	</div>
	<!-- /Navigation -->