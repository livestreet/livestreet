{**
 * Базовый шаблон топика
 *
 * @styles assets/css/topic.css
 * @scripts <framework>/js/livestreet/topic.js
 *}

{* Название компонента *}
{$component = 'article'}

{block 'article_options'}
	{$isPreview = $smarty.local.isPreview}
	{$user = $article->getUser()}
	{$type = ($article->getType()) ? $article->getType() : $smarty.local.type}
	{$isList = $smarty.local.isList}
	{$mods = $smarty.local.mods}
	{$classes = $smarty.local.classes}

	{if ! $isList}
		{$mods = "{$mods} single"}
	{/if}
{/block}

{block 'article'}
	<article class="{$component} {mod name=$component mods=$mods} {$classes}" {$smarty.local.attributes}>
		{**
		 * Хидер
		 *}
		{block 'article_header'}
			<header class="{$component}-header">
				{* Заголовок *}
				<h1 class="{$component}-title word-wrap">
					{block 'article_title'}
						{if $isList}
							<a href="{$article->getUrl()}">{$article->getTitle()|escape}</a>
						{else}
							{$article->getTitle()|escape}
						{/if}
					{/block}
				</h1>

				{* Информация *}
				<ul class="{$component}-info">
					{block 'article_header_info'}
						<li class="{$component}-info-item {$component}-info-item--date">
							<time datetime="{date_format date=$article->getDateAdd() format='c'}" title="{date_format date=$article->getDateAdd() format='j F Y, H:i'}">
								{date_format date=$article->getDateAdd() format="j F Y, H:i"}
							</time>
						</li>
					{/block}
				</ul>

				{* Управление *}
				{if $article->getIsAllowAction() && ! $isPreview}
					{block 'article_header_actions'}
						{$items = [
							[ 'icon' => 'icon-edit', 'url' => $article->getUrlEdit(), 'text' => $aLang.common.edit, 'show' => $article->getIsAllowEdit() ],
							[ 'icon' => 'icon-trash', 'url' => "{$article->getUrlDelete()}?security_ls_key={$LIVESTREET_SECURITY_KEY}", 'text' => $aLang.common.remove, 'show' => $article->getIsAllowDelete() ]
						]}
					{/block}

					{include 'components/actionbar/actionbar.tpl' items=$items}
				{/if}
			</header>
		{/block}


		{**
		 * Текст
		 *}
		{block 'article_body'}
			<div class="{$component}-content text">
				{block 'article_content_text'}
					{if $isList}
						{$article->getTextShort()}

						{* Кат *}
						{if $article->getTextShort() != $article->getText()}
	                        <br/>
	                        <a href="{$article->getUrl()}#cut" title="{$aLang.topic.read_more}">
								{$article->getCutText()|default:$aLang.topic.read_more}
	                        </a>
						{/if}
					{else}
						{$article->getText()}
					{/if}
				{/block}
			</div>
		{/block}


		{**
		 * Футер
		 *}
		{block 'article_footer'}
			<footer class="{$component}-footer">
				{* Информация *}
				{block 'article_footer_info'}
					<ul class="{$component}-info clearfix">
						{block 'article_footer_info_items'}
							{* Автор топика *}
							<li class="{$component}-info-item {$component}-info-item--author">
								{include 'components/user/user-item.tpl' oUser=$user iUserItemAvatarSize=48 sUserItemStyle='rounded'}
							</li>

							{* Ссылка на комментарии *}
							{* Не показываем если комментирование запрещено и кол-во комментариев равно нулю *}
							{if $isList && ( ! $article->getForbidComment() || ( $article->getForbidComment() && $article->getCountComment() ) )}
								<li class="{$component}-info-item {$component}-info-item--comments">
									<a href="{$article->getUrl()}#comments">
										{lang name='comments.comments_declension' count=$article->getCountComment() plural=true}
									</a>

									{if $article->getCountCommentNew()}<span>+{$article->getCountCommentNew()}</span>{/if}
								</li>
							{/if}
						{/block} {* /article_footer_info_items *}
					</ul>
				{/block} {* /article_footer_info *}
			</footer>
		{/block} {* /article_footer *}
	</article>
{/block}