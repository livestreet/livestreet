{if $smarty.local.properties}
    <div class="property-list">
        {foreach $smarty.local.properties as $property}
            {include './item.tpl' property=$property}
        {/foreach}
    </div>
{/if}