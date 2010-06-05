<div class="block">
	<h2>{$aLang.user_stats}</h2>
	
	<ul>
		<li>{$aLang.user_stats_all}: <strong>{$aStat.count_all}</strong></li>
		<li>{$aLang.user_stats_active}: <strong>{$aStat.count_active}</strong></li>
		<li>{$aLang.user_stats_noactive}: <strong>{$aStat.count_inactive}</strong></li>
	</ul>
	
	<br />
	
	<ul>
		<li>{$aLang.user_stats_sex_man}: <strong>{$aStat.count_sex_man}</strong></li>
		<li>{$aLang.user_stats_sex_woman}: <strong>{$aStat.count_sex_woman}</strong></li>
		<li>{$aLang.user_stats_sex_other}: <strong>{$aStat.count_sex_other}</strong></li>
	</ul>
</div>

{insert name="block" block='tagsCountry'}
{insert name="block" block='tagsCity'}