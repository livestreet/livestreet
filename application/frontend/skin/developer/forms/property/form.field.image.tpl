{$value = $oProperty->getValue()}
{$valueType = $value->getValueTypeObject()}

{include 'components/field/field.file.tpl'
		 name    = "property[{$oProperty->getId()}][file]"
		 classes = 'width-300'
		 note    = $oProperty->getDescription()
		 label   = $oProperty->getTitle()}

{$file = $value->getDataOne('file')}

{if $file}
	<a href="{$valueType->getImageWebPath()}" class="js-lbx" target="_blank"><img src="{$valueType->getImageWebPath($valueType->getImageSizeFirst())}" ></a> <br/>
	<label>
		<input type="checkbox" name="property[{$oProperty->getId()}][remove]" value="1"> &mdash; удалить изображение
	</label>
	<br/>
{/if}