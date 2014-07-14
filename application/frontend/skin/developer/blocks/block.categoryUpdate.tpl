
{*
	Вывод категорий на странице создания нового объекта
*}

{$aCategoriesCurrentId=[]}
{if $aCategoryParams.form_fill_current_from_request && $_aRequest[$aCategoryParams.form_field]}
	{$aCategoriesCurrentId=$_aRequest[$aCategoryParams.form_field]}
{else}
	{if $aCategoriesCurrent}
		{foreach $aCategoriesCurrent as $oCategoryCurrent}
			{$aCategoriesCurrentId[]=$oCategoryCurrent->getId()}
		{/foreach}
	{/if}
{/if}

Категория:
<select name="{$aCategoryParams.form_field}[]" {if $aCategoryParams.multiple}multiple="multiple" style="height: 200px;"{/if}>
	{if !$aCategoryParams.validate_require}
		<option value="">&mdash;</option>
	{/if}
	{foreach $aCategories as $aCategory}
		{$oCategory=$aCategory.entity}
		<option value="{$oCategory->getId()}" {if in_array($oCategory->getId(),$aCategoriesCurrentId)}selected="selected"{/if} style="margin-left: {$oCategory->getLevel()*10}px;">{''|str_pad:(2*$aCategory.level):'-'|cat:$oCategory->getTitle()}</option>
	{/foreach}
</select>
