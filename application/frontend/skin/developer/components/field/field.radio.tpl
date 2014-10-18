{**
 * 
 *}

{extends './field.tpl'}

{block 'field' prepend}
	{$_mods = "$_mods checkbox"}
{/block}

{block 'field_input'}
	<input type="radio" {field_input_attr_common} {if $checked}checked{else}{if $_aRequest[$name] == 1}checked{/if}{/if} />
{/block}