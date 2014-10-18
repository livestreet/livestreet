{**
 * E-mail
 *}

{extends './field.text.tpl'}

{block 'field_options' append}
	{$name = $name|default:'mail'}
	{$label = $label|default:{lang name='field.email.label'}}
	{$_rules = array_merge([ 'required' => true, 'type'=> 'email' ], $_rules)}
{/block}