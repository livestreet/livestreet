
		<ul class="menu">
			<li class="active"><font color="#333333">{$aLang.talk_menu_inbox}</font>
				<ul class="sub-menu">					
					<li {if $sEvent=='inbox'}class="active"{/if}><div><a href="{router page='talk'}">{$aLang.talk_menu_inbox_list}</a></div></li>
					<li {if $sEvent=='add'}class="active"{/if}><div><a href="{router page='talk'}add/">{$aLang.talk_menu_inbox_create}</a></div></li>
					<li {if $sEvent=='favourites'}class="active"{/if}><div><a href="{router page='talk'}favourites/">{$aLang.talk_menu_inbox_favourites}</a>{if $iCountTalkFavourite} ({$iCountTalkFavourite}){/if}</div></li>
					{hook run='menu_talk_talk_item'}
				</ul>
			</li>
			{hook run='menu_talk'}
		</ul>