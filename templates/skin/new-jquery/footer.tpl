			{hook run='content_end'}
			</div><!-- /content-inner -->
		</div><!-- /content -->

		{if !$noSidebar}
			{include file='sidebar.tpl'}
		{/if}
	</div><!-- /wrapper -->

	<div id="footer">
		<div id="footer-inner">
			<div class="right">&copy; Powered by <a href="http://livestreetcms.ru">LiveStreet CMS</a></div>
			Design by — <a href="http://www.xeoart.com">Студия XeoArt</a>
			<img border="0" src="http://livestreet.ru/templates/skin/new/images/xeoart.gif">
			{if $oUserCurrent and $oUserCurrent->isAdministrator()}| <a href="{cfg name='path.root.web'}/admin">{$aLang.admin_title}</a>{/if}
		</div>
	</div>

</div><!-- /container -->

{hook run='body_end'}

</body>
</html>