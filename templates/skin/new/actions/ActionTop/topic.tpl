{include file='header.tpl' menu="blog" showWhiteBack=true}

			<div class="topic top">
				<h1>TOP топиков</h1>				
				<ul class="block-nav">
					<li {if $aParams[0] and $aParams[0]=='24h'}class="active"{/if}><strong></strong><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/topic/24h/">{$aLang.blog_menu_top_period_24h}</a></li>
					<li {if $aParams[0] and $aParams[0]=='7d'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/topic/7d/">{$aLang.blog_menu_top_period_7d}</a></li>
					<li {if $aParams[0] and $aParams[0]=='30d'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/topic/30d/">{$aLang.blog_menu_top_period_30d}</a></li>
					<li {if $aParams[0] and $aParams[0]=='all'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/topic/all/">{$aLang.blog_menu_top_period_all}</a><em></em></li>
				</ul>
			</div>

{include file='topic_list.tpl'}


{include file='footer.tpl'}