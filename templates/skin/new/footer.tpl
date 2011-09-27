		{hook run='content_end'}
		</div>
		<!-- /Content -->
		{if !$bNoSidebar}
			{include file='sidebar.tpl'}
		{/if}
		
	</div>

	<!-- Footer -->
	<div id="footer">
		<div class="right">
			{hook run='copyright'}<br />
			<a href="{router page='page'}about/">{$aLang.page_about}</a>
		</div>
		Design by — <a href="http://www.xeoart.com/">Студия XeoArt</a>&nbsp;<img src="{cfg name='path.static.skin'}/images/xeoart.gif" border="0">
		{hook run='footer_end'}
	</div>
	<!-- /Footer -->

</div>
{hook run='body_end'}
</body>
</html>