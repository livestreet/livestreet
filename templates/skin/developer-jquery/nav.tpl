<nav id="nav">
	<ul class="nav nav-main">
		<li {if $sMenuHeadItemSelect=='blog'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.topic_title}</a></li>
		<li {if $sMenuHeadItemSelect=='blogs'}class="active"{/if}><a href="{router page='blogs'}">{$aLang.blogs}</a></li>
		<li {if $sMenuHeadItemSelect=='people'}class="active"{/if}><a href="{router page='people'}">{$aLang.people}</a></li>
		<li {if $sMenuItemSelect=='top'}class="active"{/if}><a href="{router page='top'}">{$aLang.blog_menu_top}</a></li>
		{if $oUserCurrent}
			<li {if $sMenuItemSelect=='stream'}class="active"{/if}>
				<a href="{router page='stream'}">{$aLang.stream_personal_title}</a>
			</li>
		{/if}
						
		{hook run='main_menu'}
	</ul>
</nav>