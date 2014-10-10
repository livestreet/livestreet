{**
 * Список блогов
 *
 * @styles css/blog.css
 * @scripts <frontend>/common/js/blog.js
 *}


{* Список блогов *}
{if $aBlogs}
	{if $iSearchCount}
		<h3 class="h3">{lang name='blog.search.result_title' count=$iSearchCount plural=true}</h3>
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
				{$info = [
					[ 'label' => "{$aLang.blog.users.readers_total}:", 'content' => $oBlog->getCountUser() ],
					[ 'label' => "{$aLang.vote.rating}:",              'content' => $oBlog->getRating() ],
					[ 'label' => "{$aLang.blog.topics_total}:",        'content' => $oBlog->getCountTopic() ]
				]}

				{include 'components/info-list/info-list.tpl' aInfoList=$info sInfoListClasses='object-list-item-info'}

				{* Действия *}
				<div class="object-list-item-actions">
					{* Вступить/покинуть блог *}
					{include './join.tpl'}
				</div>
			</li>
		{/foreach}
	</ul>


	{if $bUseMore}
		{if ! $bHideMore}
			{include 'components/more/more.tpl'
					 classes    = 'js-more-search'
					 target     = '.js-more-blogs-container'
					 attributes = 'data-search-type="blogs" data-proxy-page-next="2"'}
		{/if}
	{else}
		{include 'components/pagination/pagination.tpl' aPaging=$aPaging}
	{/if}

{else}
	{include 'components/alert/alert.tpl' text=(($sBlogsEmptyList) ? $sBlogsEmptyList : $aLang.blog.alerts.empty) mods='empty'}
{/if}