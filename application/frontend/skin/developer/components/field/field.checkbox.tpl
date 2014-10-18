{**
 * Чекбокс
 *}

{extends './field.tpl'}

{block 'field' prepend}
	{$_mods = "$_mods checkbox"}
	{$_value = ( $_value ) ? $_value : '1'}
{/block}

{block 'field_input'}
	<input type="checkbox" {field_input_attr_common} {if $smarty.local.checked}checked{else}{if $_aRequest[$smarty.local.name] == 1}checked{/if}{/if} />
{/block}