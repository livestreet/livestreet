{**
 * Выбор местоположения
 *}

{extends './field.tpl'}

{block 'field_classes'}js-geo-select{/block}

{block 'field_input'}
    {$place = $smarty.local.place}

    <p class="mb-15"><select class="js-geo-country width-200" name="{$smarty.local.sName}_country">
        <option value="">{$aLang.field.geo.select_country}</option>

        {if $aGeoCountries}
            {foreach $aGeoCountries as $country}
                <option value="{$country->getId()}" {if $place and $place->getCountryId() == $country->getId()}selected="selected"{/if}>{$country->getName()}</option>
            {/foreach}
        {/if}
    </select></p>

    <p class="mb-15"><select class="js-geo-region width-200" name="{$smarty.local.sName}_region" {if ! $place or ! $place->getCountryId()}style="display:none;"{/if}>
        <option value="">{$aLang.field.geo.select_region}</option>

        {if $aGeoRegions}
            {foreach $aGeoRegions as $region}
                <option value="{$region->getId()}" {if $place and $place->getRegionId() == $region->getId()}selected="selected"{/if}>{$region->getName()}</option>
            {/foreach}
        {/if}
    </select></p>

    <p><select class="js-geo-city width-200" name="{$smarty.local.sName}_city" {if ! $place or ! $place->getRegionId()}style="display:none;"{/if}>
        <option value="">{$aLang.field.geo.select_city}</option>

        {if $aGeoCities}
            {foreach $aGeoCities as $city}
                <option value="{$city->getId()}" {if $place and $place->getCityId() == $city->getId()}selected="selected"{/if}>{$city->getName()}</option>
            {/foreach}
        {/if}
    </select></p>
{/block}