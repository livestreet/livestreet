		{hook run='content_end'}
		</div><!-- /content -->

		{if !$noSidebar}
			{include file='sidebar.tpl'}
		{/if}
	</div><!-- /wrapper -->

	<div id="footer">
		<div class="right">Powered by <a href="http://livestreetcms.com">LiveStreet CMS</a></div>
		Автор шаблона &mdash; <a href="http://deniart.ru">deniart</a>
	</div>

</div><!-- /container -->

{hook run='body_end'}

</body>
</html>