{**
 * Выбор местоположения
 *
 * @param string $name
 * @param string $targetType
 * @param object $place
 * @param array  $countries
 * @param array  $regions
 * @param array  $cities
 *}

{extends './field.tpl'}

{block 'field_options' append}
    {$_mods = "$_mods geo"}

    {if $smarty.local.targetType}
        {$_attributes = array_merge( $smarty.local.attributes|default:[], [ 'data-type' => $smarty.local.targetType ] )}
    {/if}
{/block}

{block 'field_input'}
    {$place = $smarty.local.place}
    {$name = $smarty.local.name|default:'geo'}

    <select class="js-field-geo-country" name="{$name}_country">
        <option value="">{$aLang.field.geo.select_country}</option>

        {if $smarty.local.countries}
            {foreach $smarty.local.countries as $country}
                <option value="{$country->getId()}" {if $place && $place->getCountryId() == $country->getId()}selected="selected"{/if}>{$country->getName()}</option>
            {/foreach}
        {/if}
    </select>

    <select class="js-field-geo-region" name="{$name}_region" {if ! $place or ! $place->getCountryId()}style="display:none;"{/if}>
        <option value="">{$aLang.field.geo.select_region}</option>

        {if $smarty.local.regions}
            {foreach $smarty.local.regions as $region}
                <option value="{$region->getId()}" {if $place && $place->getRegionId() == $region->getId()}selected="selected"{/if}>{$region->getName()}</option>
            {/foreach}
        {/if}
    </select>

    <select class="js-field-geo-city" name="{$name}_city" {if ! $place or ! $place->getRegionId()}style="display:none;"{/if}>
        <option value="">{$aLang.field.geo.select_city}</option>

        {if $smarty.local.cities}
            {foreach $smarty.local.cities as $city}
                <option value="{$city->getId()}" {if $place && $place->getCityId() == $city->getId()}selected="selected"{/if}>{$city->getName()}</option>
            {/foreach}
        {/if}
    </select>
{/block}