{if $smarty.local.properties}
    <div class="ls-property-list">
        {foreach $smarty.local.properties as $property}
            {component 'property' template='output.item' property=$property}
        {/foreach}
    </div>
{/if}