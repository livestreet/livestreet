{$oValue=$oPropertyItem->getValue()}
<div class="property-list-item">
    <div class="property-list-item-label">{$oPropertyItem->getTitle()}</div>
	{if $oUserCurrent or !$oPropertyItem->getParam('access_only_auth')}
    	<a href="{router page="property/download"}{$oValue->getValueVarchar()}/">{$oValue->getValueForDisplay()}</a>
	{else}
		Для доступа к файлу <a href="#" class="js-modal-toggle-login">необходимо авторизоваться</a>
	{/if}
</div>