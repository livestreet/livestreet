		</div>
		<!-- /Content -->
		{if !$bNoSidebar}
			{include file='sidebar.tpl'}
		{/if}
		
	</div>

	<!-- Footer -->
	<div id="footer">
		<div class="right">
			Сайт работает на базе движка <a href="http://livestreet.ru">LiveStreet</a><br />
			<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/about/">{$aLang.page_about}</a> | <a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PAGE}/download/">{$aLang.page_download} LiveStreet</a>
		</div>
		Автор шаблона — <a href="http://deniart.ru/">deniart</a>
	</div>
	<!-- /Footer -->

</div>

</body>
</html>