<nav id="nav">
	<ul class="clearfix">
		<li {if $sMenuHeadItemSelect=='index'}class="active"{/if}><a href="{cfg name='path.root.web'}">{$aLang.menu_index}</a></li>

		{hook run='main_menu_item'}
	</ul>
	{hook run='main_menu'}
</nav>