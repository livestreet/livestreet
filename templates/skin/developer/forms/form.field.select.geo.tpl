{**
 * Выбор местоположения
 *}

{extends file='forms/form.field.select.tpl'}

{block name='field_item_classes'}js-geo-select{/block}
{block name='field_holder_input'}
    <select class="js-geo-country width-full" name="{$sFieldNamePrefix}_country">
        <option value="">{$aLang.geo_select_country}</option>

        {if $aGeoCountries}
            {foreach $aGeoCountries as $oGeoCountry}
                <option value="{$oGeoCountry->getId()}" {if $oInputGeoTarget and $oInputGeoTarget->getCountryId() == $oGeoCountry->getId()}selected="selected"{/if}>{$oGeoCountry->getName()}</option>
            {/foreach}
        {/if}
    </select>

    <select class="js-geo-region width-full mt-15" name="{$sFieldNamePrefix}_region" {if !$oInputGeoTarget or ! $oInputGeoTarget->getCountryId()}style="display:none;"{/if}>
        <option value="">{$aLang.geo_select_region}</option>

        {if $aGeoRegions}
            {foreach $aGeoRegions as $oGeoRegion}
                <option value="{$oGeoRegion->getId()}" {if $oInputGeoTarget and $oInputGeoTarget->getRegionId() == $oGeoRegion->getId()}selected="selected"{/if}>{$oGeoRegion->getName()}</option>
            {/foreach}
        {/if}
    </select>

    <select class="js-geo-city width-full mt-15" name="{$sFieldNamePrefix}_city" {if !$oInputGeoTarget or ! $oInputGeoTarget->getRegionId()}style="display:none;"{/if}>
        <option value="">{$aLang.geo_select_city}</option>

        {if $aGeoCities}
            {foreach $aGeoCities as $oGeoCity}
                <option value="{$oGeoCity->getId()}" {if $oInputGeoTarget and $oInputGeoTarget->getCityId() == $oGeoCity->getId()}selected="selected"{/if}>{$oGeoCity->getName()}</option>
            {/foreach}
        {/if}
    </select>
{/block}