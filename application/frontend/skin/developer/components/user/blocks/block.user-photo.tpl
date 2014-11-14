{**
 * Блок с фотографией пользователя в профиле
 *}

{extends 'components/block/block.tpl'}

{block 'block_options' append}
    {$mods = "{$mods} user-photo"}
    {$classes = "{$classes} js-ajax-photo-upload"}
{/block}

{block 'block_content'}
    <div class="user-photo {if ! $oUserProfile->getProfileFoto()}user-photo--nophoto{/if} js-user-photo" data-user-id="{$oUserProfile->getId()}">
        {* Статус онлайн\оффлайн *}
        {if $oSession}
            {if $oUserProfile->isOnline() &&  $smarty.now - strtotime($oSession->getDateLast()) < 60*5}
                <div class="user-status user-status--online">{$aLang.user.status.online}</div>
            {else}
                <div class="user-status user-status--offline">
                    {$date = {date_format date=$oSession->getDateLast() hours_back="12" minutes_back="60" day_back="8" now="60*5" day="day H:i" format="j F в G:i"}}

                    {if $oUserProfile->getProfileSex() != 'woman'}
                        {lang 'user.status.was_online_male' date=$date}
                    {else}
                        {lang 'user.status.was_online_female' date=$date}
                    {/if}
                </div>
            {/if}
        {/if}

        {* Фото *}
        <a href="{$oUserProfile->getUserWebPath()}">
            <img src="{$oUserProfile->getProfileFotoPath()}" alt="{$oUserProfile->getDisplayName()} photo" class="user-photo-image js-user-photo-image" />
        </a>

        {* Действия (редактировать/удалить) *}
        {if $oUserProfile->isAllowEdit()}
            <ul class="user-photo-actions">
                <li class="js-user-photo-actions-upload">
                    <label class="form-input-file">
                        <span class="js-user-photo-actions-upload-label">
                            {if $oUserProfile->getProfileFoto()}
                                {lang 'user.photo.actions.change_photo'}
                            {else}
                                {lang 'user.photo.actions.upload_photo'}
                            {/if}
                        </span>
                        <input type="file" name="photo" class="js-user-photo-actions-upload-input">
                    </label>
                </li>
                <li class="js-user-photo-actions-crop-avatar" style="{if !$oUserProfile->getProfileFoto()}display:none;{/if}">
                    {lang 'user.photo.actions.change_avatar'}
                </li>
                <li class="js-user-photo-actions-remove" style="{if !$oUserProfile->getProfileFoto()}display:none;{/if}">
                    {lang 'user.photo.actions.remove'}
                </li>
            </ul>
        {/if}
    </div>
{/block}