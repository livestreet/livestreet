{**
 * Выбор файла
 *}

{extends './field.tpl'}

{block 'field' prepend}
	{$_sMods = "$_sMods file"}
{/block}

{block 'field_input'}
	<input type="file" {field_input_attr_common} />
{/block}