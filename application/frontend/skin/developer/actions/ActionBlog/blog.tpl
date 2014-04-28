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
	{$bUserIsAdministrator = $oUserCurrent && ($oUserCurrent->getId() == $oBlog->getOwnerId() || $oUserCurrent->isAdministrator() || $oBlog->getUserIsAdministrator())}


	<div class="blog">
		<header class="blog-header">
			{* Голосование *}
			{include 'components/vote/vote.tpl'
				 	 sClasses   = 'js-vote-blog'
				 	 sMods      = 'large'
					 oObject    = $oBlog
					 bIsLocked  = $bUserIsAdministrator
					 bShowLabel = true}

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
				[ 'label' => $aLang.blog.date_created, 'content' => "{date_format date=$oBlog->getDateAdd() hours_back='12' minutes_back='60' now='60' day='day H:i' format='j F Y'}" ],
				[ 'label' => $aLang.blog.topics_total, 'content' => $oBlog->getCountTopic() ],
				[ 'label' => $aLang.blog.rating_limit, 'content' => $oBlog->getLimitRatingTopic() ]
			]}

			{include 'components/info_list/info_list.tpl' aInfoList=$aBlogInfo}
		</div>

		{* Управление *}
		{if $oUserCurrent && $bUserIsAdministrator}
			{$aActionbarItems = [ [ 'icon' => 'icon-edit', 'url' => "{router page='blog'}edit/{$oBlog->getId()}/", 'text' => $aLang.common.edit ] ]}

			{if $oUserCurrent->isAdministrator()}
				{$aActionbarItems[] = [
					'icon'       => 'icon-trash',
					'attributes' => 'data-type="modal-toggle" data-modal-target="modal-blog-delete"',
					'text'       => $aLang.common.remove
				]}
			{else}
				{$aActionbarItems[] = [
					'icon'    => 'icon-trash',
					'url'     => "{router page='blog'}delete/{$oBlog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}",
					'classes' => 'js-blog-remove',
					'text'    => $aLang.common.remove
				]}
			{/if}

			{include 'components/actionbar/actionbar.tpl' aItems=$aActionbarItems}
		{/if}
	</div>


	{* Сообщение для забаненного пользователя *}
	{* TODO: Вывод сообщения о бане *}
	{if false}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.blog.alerts.banned sMods='error'}
	{/if}

	{* Навигация по топикам блога *}
	<div class="nav-group">
		{include 'navs/nav.topics.sub.tpl'}
	</div>

	{* Список топиков *}
	{if $bPrivateBlog}
		{include 'components/alert/alert.tpl' mAlerts=$aLang.blog.alerts.private sMods='error'}
	{else}
		{include 'topics/topic_list.tpl'}
	{/if}
{/block}