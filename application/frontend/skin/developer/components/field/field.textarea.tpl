{**
 * Текстовое поле
 *}

{extends './field.tpl'}

{block 'field_input'}
	<textarea {field_input_attr_common} rows="{$iRows}">{field_input_attr_value}</textarea>
{/block}