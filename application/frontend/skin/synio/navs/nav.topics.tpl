{**
 * Навигация по топикам
 *}

<ul class="nav nav-menu">
	<li {if $sMenuItemSelect == 'index'}class="active"{/if}>
		<a href="{cfg name='path.root.web'}/">{$aLang.blog_menu_all}</a>
	</li>
	
	{if $oUserCurrent}
		<li {if $sMenuItemSelect == 'feed'}class="active"{/if}>
			<a href="{router page='feed'}">{$aLang.userfeed_title}</a>
		</li>
	{/if}

	{hook run='nav_topics_item'}
</ul>