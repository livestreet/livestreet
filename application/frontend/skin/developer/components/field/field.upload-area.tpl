{**
 * Drag & drop загрузка
 *}

{$component = 'field-upload-area'}

<label class="{$component} {mod name=$component mods=$smarty.local.mods} {$smarty.local.classes}" {$smarty.local.attributes}>
	<span>{$smarty.local.label|default:{lang name='field.upload_area.label'}}</span>
	<input type="file" name="{$smarty.local.inputName|default:'file'}" class="{$smarty.local.inputClasses}" {$smarty.local.inputAttributes} {$smarty.local.isMultiple|default:'multiple'}>
</label>