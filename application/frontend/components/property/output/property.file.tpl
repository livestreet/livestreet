{$value = $property->getValue()}
{$valueType = $value->getValueTypeObject()}

<div class="property">
    <div class="property-list-item-label">
        {$property->getTitle()}
    </div>

    {if $value->getValueVarchar()}
        {if $oUserCurrent || ! $property->getParam('access_only_auth')}
            <a href="{router page="property/download"}{$value->getValueVarchar()}/">{$value->getValueForDisplay()}</a>
            {if $valueType->getCountDownloads()}
                <br/>Загрузок: {$valueType->getCountDownloads()}
            {/if}
        {else}
            Для доступа к файлу <a href="#" class="js-modal-toggle-login">необходимо авторизоваться</a>
        {/if}
    {else}
        файла нет
    {/if}
</div>