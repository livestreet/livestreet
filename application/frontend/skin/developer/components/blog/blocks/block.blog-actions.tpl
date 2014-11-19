{**
 * Действия
 *
 * TODO: Уни-ать список экшенов
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
   {* TODO: Fix styles *}
   {$mods = "{$mods} user-actions"}
{/block}

{block 'block_content'}
    {$blog = $oBlog}

    <ul class="profile-actions" id="profile_actions">
        {* Является ли пользователь администратором или управляющим блога *}
        {$isBlogAdmin = $oUserCurrent && ( $oUserCurrent->getId() == $blog->getOwnerId() || $oUserCurrent->isAdministrator() || $blog->getUserIsAdministrator() )}

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
        {if $oUserCurrent && ( ( $blog->getUserIsJoin() && $oUserCurrent->getRating() >= $blog->getLimitRatingTopic() ) || $isBlogAdmin )}
            {$actions[] = [
                'url' => "{$LS->Topic_GetTopicType('topic')->getUrlForAdd()}?blog_id={$blog->getId()}",
                'text' => {lang 'blog.actions.write'}
            ]}
        {/if}

        {* Подписаться через RSS *}
        {$actions[] = [
            'url' => "{router page='rss'}blog/{$blog->getUrl()}/",
            'text' => {lang 'blog.actions.rss'}
        ]}

        {if $oUserCurrent && $isBlogAdmin}
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
{/block}