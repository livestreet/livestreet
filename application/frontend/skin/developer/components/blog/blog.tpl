{**
 * Блог
 *
 * @param object $blog       Блог
 * @param string $mods       Модификаторы
 * @param string $attributes Дополнительные атрибуты основного блока
 * @param string $classes    Дополнительные классы
 *
 * TODO: Сделать универсальным
 *}

{* Название компонента *}
{$component = 'blog'}

{* Переменные *}
{$blog = $smarty.local.blog}

{* Подключаем модальное окно удаления блога если пользователь админ *}
{if $oUserCurrent and $oUserCurrent->isAdministrator()}
	{include './modal.blog-delete.tpl'}
{/if}

{* Является ли пользователь администратором или управляющим блога *}
{$isBlogAdmin = $oUserCurrent && ($oUserCurrent->getId() == $blog->getOwnerId() || $oUserCurrent->isAdministrator() || $blog->getUserIsAdministrator())}


{* Блог *}
<div class="{$component} {mod name=$component mods=$mods default=$defaultMod} {$smarty.local.classes}" data-id="{$blog->getId()}">
	<header class="{$component}-header">
		{* Голосование *}
		{block 'blog_vote'}
			{include 'components/vote/vote.tpl'
				 	 classes   = 'js-vote-blog'
				 	 mods      = 'large'
					 target    = $blog
					 isLocked  = $isBlogAdmin
					 showLabel = true}
		{/block}

		{* Заголовок *}
		<h2 class="page-header blog-title">
			{if $blog->getType() == 'close'}
				<i title="{$aLang.blog.private}" class="icon icon-lock"></i>
			{/if}

			{$blog->getTitle()|escape}
		</h2>
	</header>


	{* Информация о блоге *}
	<div class="{$component}-content">
		{* Описание *}
		<div class="{$component}-description text">
			{$blog->getDescription()}
		</div>

		{* Информация *}
		{include 'components/info-list/info-list.tpl' aInfoList=[
			[ 'label' => $aLang.blog.date_created, 'content' => "{date_format date=$blog->getDateAdd() hours_back='12' minutes_back='60' now='60' day='day H:i' format='j F Y'}" ],
			[ 'label' => $aLang.blog.topics_total, 'content' => $blog->getCountTopic() ],
			[ 'label' => $aLang.blog.rating_limit, 'content' => $blog->getLimitRatingTopic() ]
		]}
	</div>


	{* Управление *}
	{if $oUserCurrent && $isBlogAdmin}
		{$aActionbarItems = [ [ 'icon' => 'icon-edit', 'url' => "{router page='blog'}edit/{$blog->getId()}/", 'text' => $aLang.common.edit ] ]}

		{if $oUserCurrent->isAdministrator()}
			{$aActionbarItems[] = [
				'icon'       => 'icon-trash',
				'attributes' => 'data-type="modal-toggle" data-modal-target="modal-blog-delete"',
				'text'       => $aLang.common.remove
			]}
		{else}
			{$aActionbarItems[] = [
				'icon'    => 'icon-trash',
				'url'     => "{router page='blog'}delete/{$blog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}",
				'classes' => 'js-blog-remove',
				'text'    => $aLang.common.remove
			]}
		{/if}

		{include 'components/actionbar/actionbar.tpl' items=$aActionbarItems}
	{/if}
</div>