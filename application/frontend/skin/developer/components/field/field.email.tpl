{**
 * E-mail
 *}

{extends './field.text.tpl'}

{block 'field_options' append}
	{$name = $name|default:'mail'}
	{$label = $label|default:{lang name='field.email.label'}}
	{$_aRules = $_aRules|default:[ 'required' => true, 'type'=> 'email' ]}
{/block}