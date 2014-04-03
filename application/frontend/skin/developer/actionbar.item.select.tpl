{**
 * Экшнбар / Контрол выбора объектов
 *}

{extends 'actionbar.item.tpl'}

{block 'actionbar_item_classes'}
	js-dropdown-default
{/block}

{block 'actionbar_item_attributes'}
	data-type="dropdown-toggle"
	data-dropdown-target="js-dropdown-select-{$sName}"
{/block}

{block 'actionbar_item_text'}
	Выбрать
{/block}

{block 'actionbar_item'}
	{$smarty.block.parent}

	<ul class="dropdown-menu" id="js-dropdown-select-{$sName}">
		<li><a href="#" data-select-item="{$sItemSelector}">Все</a></li>
		<li><a href="#" data-select-item="{$sItemSelector}" data-select-filter=":not(*)">Убрать выделение</a></li>
		<li><a href="#" data-select-item="{$sItemSelector}" data-select-filter=":not(.selected)">Инвертировать</a></li>

		{if $aItems}
			<li class="dropdown-menu-separator"></li>
		{/if}

		{foreach $aItems as $aItem}
			<li><a href="#" data-select-item="{$sItemSelector}" data-select-filter="{$aItem['filter']}">{$aItem['text']}</a></li>
		{/foreach}
	</ul>
{/block}

