{**
 * Выбор местоположения
 *}

{extends file='forms/form.field.base.tpl'}

{block name='field_classes'}js-geo-select{/block}
{block name='field_holder' prepend}
    <select class="js-geo-country width-full" name="{$sFieldNamePrefix}_country">
        <option value="">{$aLang.geo_select_country}</option>

        {if $aGeoCountries}
            {foreach $aGeoCountries as $oGeoCountry}
                <option value="{$oGeoCountry->getId()}" {if $oFieldGeoTarget and $oFieldGeoTarget->getCountryId() == $oGeoCountry->getId()}selected="selected"{/if}>{$oGeoCountry->getName()}</option>
            {/foreach}
        {/if}
    </select>

    <select class="js-geo-region width-full mt-15" name="{$sFieldNamePrefix}_region" {if ! $oFieldGeoTarget or ! $oFieldGeoTarget->getCountryId()}style="display:none;"{/if}>
        <option value="">{$aLang.geo_select_region}</option>

        {if $aGeoRegions}
            {foreach $aGeoRegions as $oGeoRegion}
                <option value="{$oGeoRegion->getId()}" {if $oFieldGeoTarget and $oFieldGeoTarget->getRegionId() == $oGeoRegion->getId()}selected="selected"{/if}>{$oGeoRegion->getName()}</option>
            {/foreach}
        {/if}
    </select>

    <select class="js-geo-city width-full mt-15" name="{$sFieldNamePrefix}_city" {if ! $oFieldGeoTarget or ! $oFieldGeoTarget->getRegionId()}style="display:none;"{/if}>
        <option value="">{$aLang.geo_select_city}</option>

        {if $aGeoCities}
            {foreach $aGeoCities as $oGeoCity}
                <option value="{$oGeoCity->getId()}" {if $oFieldGeoTarget and $oFieldGeoTarget->getCityId() == $oGeoCity->getId()}selected="selected"{/if}>{$oGeoCity->getName()}</option>
            {/foreach}
        {/if}
    </select>
{/block}