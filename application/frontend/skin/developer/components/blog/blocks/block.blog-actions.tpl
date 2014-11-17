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
        {* Вступить/покинуть *}
        {if $oUserCurrent && $oUserCurrent->getId() != $blog->getOwnerId() && $blog->getType() == 'open'}
            <li>
                <span class="js-blog-join" data-blog-id="{$blog->getId()}">
                    {($blog->getUserIsJoin()) ? $aLang.blog.join.leave : $aLang.blog.join.join}
                </span>
            </li>
        {/if}

        {* Отправить сообщение *}
        <li>
            <a href="{router page='rss'}blog/{$blog->getUrl()}/">
                Подписаться через RSS
            </a>
        </li>

        {* Является ли пользователь администратором или управляющим блога *}
        {$isBlogAdmin = $oUserCurrent && ($oUserCurrent->getId() == $blog->getOwnerId() || $oUserCurrent->isAdministrator() || $blog->getUserIsAdministrator())}

        {if $oUserCurrent && $isBlogAdmin}
            {$actionbarItems = [ [ 'icon' => 'icon-edit', 'url' => "{router page='blog'}edit/{$blog->getId()}/", 'text' => $aLang.common.edit ] ]}

            {if $oUserCurrent->isAdministrator()}
                {$actionbarItems[] = [
                    'icon'       => 'icon-trash',
                    'attributes' => 'data-type="modal-toggle" data-modal-target="modal-blog-delete"',
                    'text'       => $aLang.common.remove
                ]}
            {else}
                {$actionbarItems[] = [
                    'icon'    => 'icon-trash',
                    'url'     => "{router page='blog'}delete/{$blog->getId()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}",
                    'classes' => 'js-blog-remove',
                    'text'    => $aLang.common.remove
                ]}
            {/if}

            {foreach $actionbarItems as $item}
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
        {/if}
    </ul>
{/block}