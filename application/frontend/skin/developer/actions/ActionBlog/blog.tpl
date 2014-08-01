{**
 * Блог
 *
 * @param object  $oBlog                     Блог
 * @param array   $aBlogUsers                Читатели блога
 * @param array   $aBlogModerators           Модераторы блога
 * @param array   $aBlogAdministrators       Администраторы блога
 * @param integer $iCountBlogUsers           Кол-во читателей
 * @param integer $iCountBlogModerators      Кол-во модераторов
 * @param integer $iCountBlogAdministrators  Кол-во администраторов
 * @param boolean $bPrivateBlog              Закрытый блог или нет
 *
 * @styles css/blog.css
 * @scripts <framework>/js/livestreet/blog.js
 *
 * TODO: Fix alerts
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_options'}
	{$sNav = 'topics.sub'}
{/block}

{block 'layout_content_header'}
	{include 'components/blog/blog.tpl' blog=$oBlog}

	{$smarty.block.parent}

	{* Сообщение для забаненного пользователя *}
	{* TODO: Вывод сообщения о бане *}
	{if false}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.blog.alerts.banned sMods='error'}
	{/if}

	{* Список топиков *}
	{if $bPrivateBlog}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.blog.alerts.private sMods='error'}
	{else}
		{include 'components/topic/topic-list.tpl' topics=$aTopics paging=$aPaging}
	{/if}
{/block}