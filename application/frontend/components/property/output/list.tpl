{component_define_params params=[ 'properties' ]}

{if $properties}
    <div class="ls-property-list">
        {foreach $properties as $property}
            {component 'property' template='output.item' property=$property}
        {/foreach}
    </div>
{/if}