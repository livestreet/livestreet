{**
 * Блок с фотографией пользователя в профиле
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
    {$mods = "{$mods} user-photo"}
    {$classes = "{$classes} js-ajax-photo-upload"}
{/block}

{block 'block_content'}
    {$session = $oUserProfile->getSession()}

    {* Статус онлайн\оффлайн *}
    {if $session}
        {if $oUserProfile->isOnline() &&  $smarty.now - strtotime($session->getDateLast()) < 60*5}
            <div class="user-status user-status--online">{$aLang.user.status.online}</div>
        {else}
            <div class="user-status user-status--offline">
                {$date = {date_format date=$session->getDateLast() hours_back="12" minutes_back="60" day_back="8" now="60*5" day="day H:i" format="j F в G:i"}|lower}

                {if $oUserProfile->getProfileSex() != 'woman'}
                    {lang 'user.status.was_online_male' date=$date}
                {else}
                    {lang 'user.status.was_online_female' date=$date}
                {/if}
            </div>
        {/if}
    {/if}

    {include 'components/photo/photo.tpl'
        classes      = 'js-user-photo'
        hasPhoto     = $oUserProfile->getProfileFoto()
        editable     = $oUserProfile->isAllowEdit()
        targetId     = $oUserProfile->getId()
        url          = $oUserProfile->getUserWebPath()
        photoPath    = $oUserProfile->getProfileFotoPath()
        photoAltText = $oUserProfile->getDisplayName()}
{/block}