{**
 * Выбор местоположения
 *}

{extends './field.tpl'}

{block 'field_classes'}js-geo-select{/block}

{block 'field_input'}
    <p class="mb-15"><select class="js-geo-country width-200" name="{$smarty.local.sName}_country">
        <option value="">{$aLang.field.geo.select_country}</option>

        {if $aGeoCountries}
            {foreach $aGeoCountries as $oGeoCountry}
                <option value="{$oGeoCountry->getId()}" {if $oFieldGeoTarget and $oFieldGeoTarget->getCountryId() == $oGeoCountry->getId()}selected="selected"{/if}>{$oGeoCountry->getName()}</option>
            {/foreach}
        {/if}
    </select></p>

    <p class="mb-15"><select class="js-geo-region width-200" name="{$smarty.local.sName}_region" {if ! $oFieldGeoTarget or ! $oFieldGeoTarget->getCountryId()}style="display:none;"{/if}>
        <option value="">{$aLang.field.geo.select_region}</option>

        {if $aGeoRegions}
            {foreach $aGeoRegions as $oGeoRegion}
                <option value="{$oGeoRegion->getId()}" {if $oFieldGeoTarget and $oFieldGeoTarget->getRegionId() == $oGeoRegion->getId()}selected="selected"{/if}>{$oGeoRegion->getName()}</option>
            {/foreach}
        {/if}
    </select></p>

    <p><select class="js-geo-city width-200" name="{$smarty.local.sName}_city" {if ! $oFieldGeoTarget or ! $oFieldGeoTarget->getRegionId()}style="display:none;"{/if}>
        <option value="">{$aLang.field.geo.select_city}</option>

        {if $aGeoCities}
            {foreach $aGeoCities as $oGeoCity}
                <option value="{$oGeoCity->getId()}" {if $oFieldGeoTarget and $oFieldGeoTarget->getCityId() == $oGeoCity->getId()}selected="selected"{/if}>{$oGeoCity->getName()}</option>
            {/foreach}
        {/if}
    </select></p>
{/block}