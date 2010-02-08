<ul class="menu">
	<li class="active"><a href="{router page='talk'}">{$aLang.talk_menu_inbox}</a>
		<ul class="sub-menu">					
			<li {if $sEvent=='inbox'}class="active"{/if}><a href="{router page='talk'}">{$aLang.talk_menu_inbox_list}</a></li>
			<li {if $sEvent=='add'}class="active"{/if}><a href="{router page='talk'}add/">{$aLang.talk_menu_inbox_create}</a></li>
			<li {if $sEvent=='favourites'}class="active"{/if}><a href="{router page='talk'}favourites/">{$aLang.talk_menu_inbox_favourites}</a>{if $iCountTalkFavourite} ({$iCountTalkFavourite}){/if}</li>
		</ul>
	</li>
</ul>