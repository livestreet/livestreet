{**
 * Photo
 *
 * @param string  $url
 * @param integer $targetId
 * @param boolean $hasPhoto
 * @param boolean $photoPath
 * @param boolean $photoAltText
 * @param boolean $editable
 * @param boolean $useAvatar
 *}

{$component = 'ls-photo'}
{component_define_params params=[ 'url', 'photoPath', 'photoAltText', 'hasPhoto', 'useAvatar', 'targetId', 'editable', 'mods', 'classes', 'attributes' ]}

{$useAvatar = $useAvatar|default:true}

{if ! $hasPhoto}
    {$mods = "$mods nophoto"}
{/if}

<div class="{$component} {cmods name=$component mods=$mods} {$classes}"
    data-target-id="{$targetId}"
    {cattr list=$attributes}>

    {* Фото *}
    <a href="{$url}">
        <img src="{$photoPath}" alt="{$photoAltText}" class="{$component}-image js-photo-image" />
    </a>

    {* Действия *}
    {if $editable}
        <ul class="{$component}-actions">
            {* Загрузить *}
            <li class="{$component}-actions-upload js-photo-actions-upload">
                <label class="form-input-file">
                    <span class="js-photo-actions-upload-label">
                        {if $hasPhoto}
                            {lang 'user.photo.actions.change_photo'}
                        {else}
                            {lang 'user.photo.actions.upload_photo'}
                        {/if}
                    </span>
                    <input type="file" name="photo" class="js-photo-actions-upload-input">
                </label>
            </li>

            {* Изменить аватар *}
            {if $useAvatar}
                <li class="{$component}-actions-crop-avatar js-photo-actions-crop-avatar">
                    {lang 'user.photo.actions.change_avatar'}
                </li>
            {/if}

            {* Удалить фото *}
            <li class="{$component}-actions-remove js-photo-actions-remove">
                {lang 'user.photo.actions.remove'}
            </li>
        </ul>
    {/if}
</div>