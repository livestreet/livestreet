{**
 * Выбор файла
 *}

{extends './field.tpl'}

{block 'field' prepend}
	{$_mods = "$_mods file"}
{/block}

{block 'field_input'}
	<input type="file" {field_input_attr_common} />
{/block}