{include file='header.tpl' menu="blog" }


<h2>{$aLang.top_topics}</h2>

<ul class="switcher">
	<li {if $aParams[0] and $aParams[0]=='24h'}class="active"{/if}><a href="{router page='top'}topic/24h/">{$aLang.blog_menu_top_period_24h}</a></li>
	<li {if $aParams[0] and $aParams[0]=='7d'}class="active"{/if}><a href="{router page='top'}topic/7d/">{$aLang.blog_menu_top_period_7d}</a></li>
	<li {if $aParams[0] and $aParams[0]=='30d'}class="active"{/if}><a href="{router page='top'}topic/30d/">{$aLang.blog_menu_top_period_30d}</a></li>
	<li {if $aParams[0] and $aParams[0]=='all'}class="active"{/if}><a href="{router page='top'}topic/all/">{$aLang.blog_menu_top_period_all}</a></li>
</ul>

		
{include file='topic_list.tpl'}
{include file='footer.tpl'}