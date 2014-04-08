{**
 * Список блогов
 *
 * @styles css/blog.css
 * @scripts <frontend>/common/js/blog.js
 *}


{* Список блогов *}
{if $aBlogs}
	{if $iSearchCount}
		<h3 class="h3">Найдено {$iSearchCount} блогов</h3>
	{/if}

	{* Список блогов *}
	<ul class="object-list object-list-actions blog-list js-more-blogs-container">
		{foreach $aBlogs as $oBlog}
			<li class="object-list-item">
				{* Аватар *}
				<a href="{$oBlog->getUrlFull()}">
					<img src="{$oBlog->getAvatarPath(100)}" width="100" height="100" alt="{$oBlog->getTitle()|escape}" class="object-list-item-image" />
				</a>

				{* Заголовок *}
				<h2 class="object-list-item-title">
					{if $oBlog->getType() == 'close'}
						<i title="{$aLang.blog.private}" class="icon-lock"></i>
					{/if}

					<a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape}</a>
				</h2>

				{* Описание *}
				<p class="object-list-item-description">{$oBlog->getDescription()|strip_tags|truncate:120}</p>

				{* Информация *}
				{$aBlogInfo = [
					[ 'label' => "{$aLang.blog.users.readers_total}:", 'content' => $oBlog->getCountUser() ],
					[ 'label' => "{$aLang.vote.rating}:",              'content' => $oBlog->getRating() ],
					[ 'label' => "{$aLang.blog.topics_total}:",        'content' => $oBlog->getCountTopic() ]
				]}

				{include 'info_list.tpl' aInfoList=$aBlogInfo sInfoListClasses='object-list-item-info'}

				{* Действия *}
				<div class="object-list-item-actions">
					{* Вступить/покинуть блог *}
					{include 'actions/ActionBlog/button_join.tpl'}
				</div>
			</li>
		{/foreach}
	</ul>


	{if $bUseMore}
		{if !$bHideMore}
			{include 'components/more/more.tpl'
					 sClasses    = 'js-more-search'
					 sTarget     = '.js-more-blogs-container'
					 sAttributes = 'data-search-type="blogs" data-proxy-page-next="2"'}
		{/if}
	{else}
		{include 'pagination.tpl' aPaging=$aPaging}
	{/if}

{else}
	{include 'alert.tpl' mAlerts=(($sBlogsEmptyList) ? $sBlogsEmptyList : $aLang.blog.alerts.empty) sAlertStyle='empty'}
{/if}