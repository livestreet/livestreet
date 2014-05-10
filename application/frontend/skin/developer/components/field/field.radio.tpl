{**
 * 
 *}

{extends './field.tpl'}

{block 'field' prepend}
	{$_sMods = "$_sMods checkbox"}
{/block}

{block 'field_input'}
	<input type="radio" {field_input_attr_common} {if $bChecked}checked{else}{if $_aRequest[$sName] == 1}checked{/if}{/if} />
{/block}