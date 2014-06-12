{**
 * Чекбокс
 *}

{extends './field.tpl'}

{block 'field' prepend}
	{$_sMods = "$_sMods checkbox"}
	{$_sValue = ( $_sValue ) ? $_sValue : '1'}
{/block}

{block 'field_input'}
	<input type="checkbox" {field_input_attr_common} {if $smarty.local.bChecked}checked{else}{if $_aRequest[$smarty.local.sName] == 1}checked{/if}{/if} />
{/block}