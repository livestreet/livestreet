{if $oPropertyItem}
	{* Проверяем наличие катомного шаблона item.[type].[target_type].tpl *}
	{$sTemplateType="property/item.{$oPropertyItem->getType()}.{$oPropertyItem->getTargetType()}.tpl"}
	{if $LS->Viewer_TemplateExists($sTemplateType)}
		{include $sTemplateType}
	{else}
		{* Проверяем наличие катомного шаблона item.[type].tpl *}
		{$sTemplateType="property/item.{$oPropertyItem->getType()}.tpl"}
		{if $LS->Viewer_TemplateExists($sTemplateType)}
			{include $sTemplateType}
		{else}
			{* Показываем стандартный шаблон *}
			{include 'property/item.base.tpl'}
		{/if}
	{/if}
{/if}