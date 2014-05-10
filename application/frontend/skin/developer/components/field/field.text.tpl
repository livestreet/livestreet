{**
 * Текстовое поле
 *}

{extends './field.tpl'}

{block 'field_input'}
	<input type="{$smarty.local.sType|default:'text'}" {field_input_attr_common} />
{/block}