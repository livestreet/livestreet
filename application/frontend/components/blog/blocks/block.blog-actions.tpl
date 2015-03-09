{**
 * Действия
 *
 * TODO: Уни-ать список экшенов
 *}

{capture 'block_content'}
    <ul class="profile-actions" id="profile_actions">
        {* Список экшенов *}
        {$actions = []}

        {* Вступить/покинуть *}
        {if $oUserCurrent && $oUserCurrent->getId() != $blog->getOwnerId() && $blog->getType() == 'open'}
            {$actions[] = [
                'classes' => 'js-blog-join',
                'attributes' => "data-blog-id=\"{$blog->getId()}\"",
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
            {$actions[] = [ 'icon' => 'icon-edit', 'url' => "{router page='blog'}edit/{$blog->getId()}/", 'text' => $aLang.common.edit ]}

            {* Удалить *}
            {if $oUserCurrent->isAdministrator()}
                {$actions[] = [
                    'icon'       => 'icon-trash',
                    'attributes' => 'data-type="modal-toggle" data-modal-target="modal-blog-delete"',
                    'text'       => $aLang.common.remove
                ]}
            {else}
                {$actions[] = [
                    'icon'    => 'icon-trash',
                    'url'     => "{router page='blog'}delete/{$blog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}",
                    'classes' => 'js-blog-remove',
                    'text'    => $aLang.common.remove
                ]}
            {/if}
        {/if}

        {* Вывод экшенов *}
        {foreach $actions as $item}
            <li>
                {if $item[ 'url' ]}
                    <a href="{$item[ 'url' ]}" class="{$item[ 'classes' ]}" {$item[ 'attributes' ]}>
                        {$item[ 'text' ]}
                    </a>
                {else}
                    <span class="{$item[ 'classes' ]}" {$item[ 'attributes' ]}>
                        {$item[ 'text' ]}
                    </span>
                {/if}
            </li>
        {/foreach}
    </ul>
{/capture}

{component 'block'
    mods    = 'nopadding transparent user-actions'
    content = $smarty.capture.block_content}