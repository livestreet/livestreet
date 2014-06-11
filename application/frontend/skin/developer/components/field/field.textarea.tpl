{**
 * Текстовое поле
 *}

{extends './field.tpl'}

{block 'field_input'}
	<textarea {field_input_attr_common bUseValue=false} rows="{$iRows}">{field_input_attr_value}</textarea>
{/block}