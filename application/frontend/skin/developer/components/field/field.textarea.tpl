{**
 * Текстовое поле
 *}

{extends './field.tpl'}

{block 'field_input'}
	<textarea {field_input_attr_common useValue=false} rows="{$rows}">{field_input_attr_value}</textarea>
{/block}