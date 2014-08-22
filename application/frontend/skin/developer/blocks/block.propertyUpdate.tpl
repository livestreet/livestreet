
{*
	Вывод дополнительных полей для ввода данных на странице создания нового объекта
*}

{if $aProperties}
	{foreach $aProperties as $oProperty}
		{include "forms/property/form.field_render.tpl" oProperty=$oProperty}
	{/foreach}
{/if}
