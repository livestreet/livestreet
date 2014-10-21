{$value = $property->getValue()}

<div class="property">
    <div class="property-list-item-label">
        {$property->getTitle()}
    </div>

    {if $oUserCurrent || ! $property->getParam('access_only_auth')}
        <a href="{router page="property/download"}{$value->getValueVarchar()}/">{$value->getValueForDisplay()}</a>
    {else}
        Для доступа к файлу <a href="#" class="js-modal-toggle-login">необходимо авторизоваться</a>
    {/if}
</div>