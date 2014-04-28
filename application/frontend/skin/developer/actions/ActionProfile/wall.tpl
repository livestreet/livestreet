{**
 * Стена
 *}

{extends file='layouts/layout.user.tpl'}

{block name='layout_user_page_title'}{$aLang.user_menu_profile_wall}{/block}

{block name='layout_content'}
	{* Форма добавления записи *}
	{if $oUserCurrent}
		{include 'actions/ActionProfile/wall.form.tpl'}
	{else}
		{include 'components/alert/alert.tpl' sMods='info' sClasses='mt-15' mAlerts=$aLang.wall_add_quest}
	{/if}

	{if ! count($aWall)}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.wall_list_empty sMods='empty' sClasses='mt-15' sAttributes='id="wall-empty"'}
	{/if}

	{* Список записей *}
	<div class="js-wall-entry-container" data-id="0">
		{include 'actions/ActionProfile/wall.posts.tpl'}
	</div>

	{* Кнопка подгрузки записей *}
	{if $iCountWall - count($aWall)}
		{include 'components/more/more.tpl'
				 sClasses    = 'js-more-wall'
				 iCount      = $iCountWall - count($aWall)
				 sAttributes = "data-more-target=\".js-wall-entry-container[data-id=0]\" data-proxy-i-last-id=\"{$iWallLastId}\" "
		}
	{/if}
{/block}