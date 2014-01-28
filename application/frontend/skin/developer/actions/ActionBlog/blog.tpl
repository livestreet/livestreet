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

{block 'layout_content'}
	{* Подключаем модальное окно удаления блога если пользователь админ *}
	{if $oUserCurrent and $oUserCurrent->isAdministrator()}
		{include 'modals/modal.blog_delete.tpl'}
	{/if}

	{* Является ли пользователь администратором или управляющим блога *}
	{$bUserIsAdministrator = $oUserCurrent->getId() == $oBlog->getOwnerId() || $oUserCurrent->isAdministrator() || $oBlog->getUserIsAdministrator()}


	<div class="blog">
		<header class="blog-header">
			{* Голосование *}
			{include 'vote.tpl' 
					 sVoteType      = 'blog'
					 sVoteClasses   = 'vote-large vote-white'
					 oVoteObject    = $oBlog
					 bVoteIsLocked  = $bUserIsAdministrator
					 bVoteShowLabel = true}

			{* Заголовок *}
			<h2 class="page-header blog-title">
				{if $oBlog->getType() == 'close'}<i title="{$aLang.blog.private}" class="icon icon-lock"></i>{/if}
				{$oBlog->getTitle()|escape}
			</h2>
		</header>


		{* Информация о блоге *}
		<div class="blog-content">
			{* Описание *}
			<div class="blog-description text">{$oBlog->getDescription()}</div>

			{* Информация *}
			{$aBlogInfo = [
				$aLang.blog.date_created => "{date_format date=$oBlog->getDateAdd() hours_back='12' minutes_back='60' now='60' day='day H:i' format='j F Y'}",
				$aLang.blog.topics_total => $oBlog->getCountTopic(),
				$aLang.blog.rating_limit => $oBlog->getLimitRatingTopic()
			]}

			<ul class="blog-info">
				{foreach $aBlogInfo as $aBlogInfoItem}
					<li class="blog-info-item">
						<span class="blog-info-item-label">{$aBlogInfoItem@key}:</span>
						<strong class="blog-info-item-content">{$aBlogInfoItem@value}</strong>
					</li>
				{/foreach}
			</ul>
		</div>


		{* Управление *}
		{if $oUserCurrent && $bUserIsAdministrator}
			<ul class="actions">
				<li>
					<i class="icon-edit icon-white"></i>
					<a href="{router page='blog'}edit/{$oBlog->getId()}/">{$aLang.common.edit}</a>
				</li>

				<li>
					<i class="icon-trash icon-white"></i>

					{if $oUserCurrent->isAdministrator()}
						<a href="#" data-type="modal-toggle" data-modal-target="modal-blog-delete">{$aLang.common.remove}</a>
					{else}
						<a href="{router page='blog'}delete/{$oBlog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}" class="js-blog-remove">{$aLang.common.remove}</a>
					{/if}
				</li>
			</ul>
		{/if}
	</div>


	{* Сообщение для забаненного пользователя *}
	{* TODO: Вывод сообщения о бане *}
	{if false}
		{include 'alert.tpl' mAlerts=$aLang.blog.alerts.banned sAlertStyle='error'}
	{/if}

	{* Навигация по топикам блога *}
	<div class="nav-group">
		{include 'navs/nav.topics.sub.tpl'}
	</div>

	{* Список топиков *}
	{if $bPrivateBlog}
		{include 'alert.tpl' mAlerts=$aLang.blog.alerts.private sAlertStyle='error'}
	{else}
		{include 'topics/topic_list.tpl'}
	{/if}
{/block}