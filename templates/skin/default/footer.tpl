				{hook run='content_end'}
			</div> <!-- /content -->

		{if !$noSidebar}
			{include file='sidebar.tpl'}
		{/if}
	</div> <!-- /wrapper -->

	<footer id="footer" class="clearfix">
		footer

		<div class="copyright">
			© Powered by <a href="http://livestreetcms.org/">LiveStreet CMS</a>

			{hook run='copyright'}
		</div>

		{hook run='footer_end'}
	</footer>
</div> <!-- /container -->


{hook run='body_end'}

</body>
</html>