{**
 * Выбор даты
 *}

{extends './field.text.tpl'}

{block 'field_options' append}
    {$_inputClasses = "{$_inputClasses} width-150"}

    <script>
        jQuery(function($) {
            {if $smarty.local.useTime}
                $( '#{$_uid}' ).datetimepicker();
            {else}
                $( '#{$_uid}' ).datepicker();
            {/if}
        });
    </script>
{/block}