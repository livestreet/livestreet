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
            <div class="field-checkbox-group">
                {foreach $smarty.local.blogsJoined as $blog}
                    {include 'components/field/field.checkbox.tpl'
                        inputClasses    = 'js-feed-blogs-subscribe'
                        inputAttributes = [ 'data-id' => $blog->getId() ]
                        checked         = isset($blogsSubscribed[ $blog->getId() ])
                        label           = "<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>"}
                {/foreach}
            </div>
        {else}
            {include 'components/alert/alert.tpl' text=$aLang.feed.blogs.empty mods='info'}
        {/if}
    </div>
{/if}