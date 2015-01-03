{**
 * Выбор блогов для чтения в ленте
 *
 * @param array $types
 * @param array $typesActive
 *}

{if $oUserCurrent}
    <div class="feed-blogs js-feed-blogs">
        {$blogsSubscribed = $smarty.local.blogsSubscribed}

        <p class="text-help">
            {$aLang.feed.blogs.note}
        </p>

        {if $smarty.local.blogsJoined}
            <div class="field-checkbox-group">
                {foreach $smarty.local.blogsJoined as $blog}
                    {component 'field' template='checkbox'
                        inputClasses    = 'js-feed-blogs-subscribe'
                        inputAttributes = [ 'data-id' => $blog->getId() ]
                        checked         = isset($blogsSubscribed[ $blog->getId() ])
                        label           = "<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>"}
                {/foreach}
            </div>
        {else}
            {component 'alert' text=$aLang.feed.blogs.empty mods='info'}
        {/if}
    </div>
{/if}