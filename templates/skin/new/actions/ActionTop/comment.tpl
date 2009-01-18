{include file='header.tpl' menu="blog" showWhiteBack=true}

			<div class="page people top-blogs">
				<h1>TOP комментариев</h1>				
				<ul class="block-nav">
					<li {if $aParams[0] and $aParams[0]=='24h'}class="active"{/if}><strong></strong><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/comment/24h/">{$aLang.blog_menu_top_period_24h}</a></li>
					<li {if $aParams[0] and $aParams[0]=='7d'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/comment/7d/">{$aLang.blog_menu_top_period_7d}</a></li>
					<li {if $aParams[0] and $aParams[0]=='30d'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/comment/30d/">{$aLang.blog_menu_top_period_30d}</a></li>
					<li {if $aParams[0] and $aParams[0]=='all'}class="active"{/if}><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TOP}/comment/all/">{$aLang.blog_menu_top_period_all}</a><em></em></li>
				</ul>
			

				{include file='comment_list.tpl'}
			</div>

{include file='footer.tpl'}