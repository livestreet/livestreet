			{hook run='content_end'}
		</div> <!-- /content -->

		
		{include file='sidebar.tpl'}
	</div> <!-- /wrapper -->


	
	<footer id="footer">
		{if $oUserCurrent}
			<ul class="footer-list">
				<li class="footer-list-header word-wrap">{$oUserCurrent->getLogin()}</li>
				<li><a href="{$oUserCurrent->getUserWebPath()}">Мой профиль</a></li> {*r*}
				<li><a href="{router page='settings'}profile/">{$aLang.user_settings}</a></li>
				<li><a href="{router page='topic'}add/">{$aLang.block_create}</a></li>
			</ul>
		{/if}
		
		<ul class="footer-list">
			<li class="footer-list-header">Разделы</li>
			<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a> <i></i></li>
			<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a> <i></i></li>
			<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a> <i></i></li>
			<li {if $sMenuHeadItemSelect=='stream'}class="active"{/if}><a href="{router page='stream'}">{$aLang.stream_menu}</a> <i></i></li>

			{hook run='main_menu_item'}
		</ul>
		
		<ul class="footer-list">
			<li class="footer-list-header">LiveStreetCMS</li>
			<li><a href="#">О проекте</a></li>
			<li><a href="#">Контакты</a></li>
			<li><a href="#">Реклама</a></li>
			<li><a href="#">Помощь</a></li>
		</ul>
	
		<div class="copyright">
			{hook run='copyright'}
			
			<div class="design-by">
				<img src="{cfg name='path.static.skin'}/images/xeoart.png" alt="xeoart" />
				Дизайн от <a href="#">xeoart</a>
				<div>2012</div>
			</div>
		</div>
		
		{hook run='footer_end'}
	</footer>
</div> <!-- /container -->

{include file='toolbar.tpl'}

{hook run='body_end'}

</body>
</html>