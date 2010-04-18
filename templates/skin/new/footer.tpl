		</div>
		<!-- /Content -->
		{if !$bNoSidebar}
			{include file='sidebar.tpl'}
		{/if}
		
	</div>

	<!-- Footer -->
	<div id="footer">
		<div class="right">
			© Powered by <a href="http://livestreet.ru" title="Free social engine">«LiveStreet»</a><br />
			<a href="{router page='page'}about/">{$aLang.page_about}</a>
		</div>
		Design by — <a href="http://www.xeoart.com/">Студия XeoArt</a>&nbsp;<img src="{cfg name='path.static.skin'}/images/xeoart.gif" border="0">
	</div>
	<!-- /Footer -->

</div>
{hook run='body_end'}
</body>
</html>