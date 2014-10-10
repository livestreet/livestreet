{**
 * Выбор блогов для чтения в ленте
 *
 * @param array $types
 * @param array $typesActive
 *}

{if $oUserCurrent}
	<div class="feed-blogs js-feed-blogs">
		{$blogsSubscribed = $smarty.local.blogsSubscribed}

		<small class="note mb-15">
			{$aLang.feed.blogs.note}
		</small>

		{if $smarty.local.blogsJoined}
			{foreach $smarty.local.blogsJoined as $blog}
				{include 'components/field/field.checkbox.tpl'
						 sInputClasses    = 'js-feed-blogs-subscribe'
						 sInputAttributes = "data-id=\"{$blog->getId()}\""
						 bChecked         = isset($blogsSubscribed[ $blog->getId() ])
						 sLabel           = "<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>"}
			{/foreach}
		{else}
			{include 'components/alert/alert.tpl' text=$aLang.feed.blogs.empty mods='info'}
		{/if}
	</div>
{/if}