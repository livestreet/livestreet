{**
 * Навигация по топикам
 *}

<ul class="nav nav-pills">
	<li {if $sMenuItemSelect == 'index'}class="active"{/if}>
		<a href="{cfg name='path.root.web'}/">{$aLang.blog_menu_all}</a>

		{if $iCountTopicsNew}
			<a href="{router page='index'}new/">+{$iCountTopicsNew}</a>
		{/if}
	</li>
	
	{if $oUserCurrent}
		<li {if $sMenuItemSelect == 'feed'}class="active"{/if}>
			<a href="{router page='feed'}">{$aLang.userfeed_title}</a>
		</li>
	{/if}

	{hook run='nav_topics'}
</ul>

{include file='navs/nav.topics.sub.tpl'}