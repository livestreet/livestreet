{**
 * Статистика по пользователям
 *
 * @styles css/blocks.css
 *}

{extends file='blocks/block.aside.base.tpl'}

{block name='block_title'}{$aLang.user_stats}{/block}

{block name='block_content'}
	{* Кол-во пользователей *}
	{$aUsersInfo = [
		[ 'label' => "{$aLang.user_stats_all}:",      'content' => $aStat.count_all ],
		[ 'label' => "{$aLang.user_stats_active}:",   'content' => $aStat.count_active ],
		[ 'label' => "{$aLang.user_stats_noactive}:", 'content' => $aStat.count_inactive ]
	]}

	{include 'components/info_list/info_list.tpl' aInfoList=$aUsersInfo iInfoListLabelWidth=200}

	{* Пол *}
	{$aUsersInfo = [
		[ 'label' => "{$aLang.user_stats_sex_man}:",   'content' => $aStat.count_sex_man ],
		[ 'label' => "{$aLang.user_stats_sex_woman}:", 'content' => $aStat.count_sex_woman ],
		[ 'label' => "{$aLang.user_stats_sex_other}:", 'content' => $aStat.count_sex_other ]
	]}

	{include 'components/info_list/info_list.tpl' aInfoList=$aUsersInfo iInfoListLabelWidth=200}
{/block}