{$oValue = $oProperty->getValue()}
{$oValueType = $oValue->getValueTypeObject()}

{include file="components/field/field.file.tpl"
		 sName    = "property[{$oProperty->getId()}][file]"
		 sClasses = 'width-300'
		 sNote = $oProperty->getDescription()
		 sLabel   = $oProperty->getTitle()}

{$aFile=$oValue->getDataOne('file')}
{if $aFile}
	<a href="{$oValueType->getImageWebPath()}" class="js-lbx" target="_blank"><img src="{$oValueType->getImageWebPath($oValueType->getImageSizeFirst())}" ></a> <br/>
	<label>
		<input type="checkbox" name="property[{$oProperty->getId()}][remove]" value="1"> &mdash; удалить изображение
	</label>
	<br/>
{/if}