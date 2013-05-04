{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='options'}
	{assign var='noNav' value=true}
	{assign var='noFooter' value=true}
{/block}

{block name='title'}{$aLang.user_stats}{/block}

{block name='content'}
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
{/block}