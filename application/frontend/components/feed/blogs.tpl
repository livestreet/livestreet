{**
 * Выбор блогов для чтения в ленте
 *
 * @param array $blogsSubscribed
 * @param array $blogsJoined
 *}

{component_define_params params=[ 'blogsSubscribed', 'blogsJoined' ]}

{if $oUserCurrent}
    <div class="ls-feed-blogs js-feed-blogs">
        <p class="text-help">
            {$aLang.feed.blogs.note}
        </p>

        {if $blogsJoined}
            <div class="ls-field-checkbox-group">
                {foreach $blogsJoined as $blog}
                    {component 'field' template='checkbox'
                        inputClasses    = 'js-feed-blogs-subscribe'
                        inputAttributes = [ 'data-id' => $blog->getId() ]
                        checked         = isset($blogsSubscribed[ $blog->getId() ])
                        label           = "<a href=\"{$blog->getUrlFull()}\">{$blog->getTitle()|escape}</a>"}
                {/foreach}
            </div>
        {else}
            {component 'blankslate' text=$aLang.feed.blogs.empty}
        {/if}
    </div>
{/if}