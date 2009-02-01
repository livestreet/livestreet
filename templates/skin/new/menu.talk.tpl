
		<ul class="menu">
			<li class="active"><font color="#333333">Почтовый ящик</font>
				<ul class="sub-menu">					
					<li {if $sEvent=='inbox'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/">Переписка</a></div></li>
					<li {if $sEvent=='add'}class="active"{/if}><div><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/add/">Новое письмо</a></div></li>
				</ul>
			</li>
		</ul>