{**
 * Действия
 *}

{capture 'block_content'}
    {* Список экшенов *}
    {$actions = []}

    {* Вступить/покинуть *}
    {if $oUserCurrent && $oUserCurrent->getId() != $blog->getOwnerId() && $blog->getType() == 'open'}
        {$actions[] = [
            'classes' => 'js-blog-profile-join',
            'attributes' => [ 'data-blog-id' => $blog->getId() ],
            'text' => {($blog->getUserIsJoin()) ? {lang 'blog.actions.leave'} : {lang 'blog.actions.join'}}
        ]}
    {/if}

    {* Написать в блог *}
    {if $oUserCurrent && ( ( $blog->getUserIsJoin() && $oUserCurrent->getRating() >= $blog->getLimitRatingTopic() ) || $blog->isAllowEdit() )}
        {$topicType=$LS->Topic_GetTopicTypeFirst()}
        {if $topicType}
            {$actions[] = [
                'url' => "{$topicType->getUrlForAdd()}?blog_id={$blog->getId()}",
                'text' => {lang 'blog.actions.write'}
            ]}
        {/if}
    {/if}

    {* Подписаться через RSS *}
    {$actions[] = [
        'url' => "{router page='rss'}blog/{$blog->getUrl()}/",
        'text' => {lang 'blog.actions.rss'}
    ]}

    {if $blog->isAllowEdit()}
        {* Редактировать *}
        {$actions[] = [ 'url' => "{router page='blog'}edit/{$blog->getId()}/", 'text' => $aLang.common.edit ]}

        {* Удалить *}
        {if $oUserCurrent->isAdministrator()}
            {$actions[] = [
                'classes'    => 'js-modal-toggle-default',
                'attributes' => [ 'data-lsmodaltoggle-modal' => 'modal-blog-delete' ],
                'text'       => $aLang.common.remove
            ]}
        {else}
            {$actions[] = [
                'url'     => "{router page='blog'}delete/{$blog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}",
                'classes' => 'js-confirm-remove-default',
                'text'    => $aLang.common.remove
            ]}
        {/if}
    {/if}

    {component 'nav' hook='blog_actions' items=$actions mods='stacked' classes='profile-actions'}
{/capture}

{component 'block'
    mods    = 'nopadding user-actions'
    content = $smarty.capture.block_content}