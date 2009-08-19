{include file='header.tpl' menu="blog" }


	<div class="topic top">
		<h1>{$aLang.top_topics}</h1>				
		<ul class="block-nav">
			<li {if $aParams[0] and $aParams[0]=='24h'}class="active"{/if}><strong></strong><a href="{router page='top'}topic/24h/">{$aLang.blog_menu_top_period_24h}</a></li>
			<li {if $aParams[0] and $aParams[0]=='7d'}class="active"{/if}><a href="{router page='top'}topic/7d/">{$aLang.blog_menu_top_period_7d}</a></li>
			<li {if $aParams[0] and $aParams[0]=='30d'}class="active"{/if}><a href="{router page='top'}topic/30d/">{$aLang.blog_menu_top_period_30d}</a></li>
			<li {if $aParams[0] and $aParams[0]=='all'}class="active"{/if}><a href="{router page='top'}topic/all/">{$aLang.blog_menu_top_period_all}</a><em></em></li>
		</ul>
	</div>

	{include file='topic_list.tpl'}


{include file='footer.tpl'}