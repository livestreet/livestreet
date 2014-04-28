{**
 * Экшнбар / Контрол выбора объектов
 *}

{extends './actionbar.item.tpl'}

{block 'actionbar_item'}
	{* Дефолтные пункты *}
	{$_aItems = [
		[ 'name' => 'all',      'text' => 'Все',  'attributes' => "data-select-item=\"{$sItemSelector}\"" ],
		[ 'name' => 'deselect', 'text' => 'Убрать выделение', 'attributes' => "data-select-item=\"{$sItemSelector}\" data-select-filter=\":not(*)\"" ],
		[ 'name' => 'invert',   'text' => 'Инвертировать', 'attributes' => "data-select-item=\"{$sItemSelector}\" data-select-filter=\":not(.selected)\"" ],
		[ 'name' => '-',        'is_enabled' => !! $smarty.local.aItems ]
	]}

	{* Кастомные пункты *}
	{foreach $smarty.local.aItems as $aItem}
		{$_aItems[] = [ 'text'=> $aItem['text'], 'attributes' => "data-select-item=\"{$sItemSelector}\" data-select-filter=\"{$aItem['filter']}\"" ]}
	{/foreach}

	{include 'components/dropdown/dropdown.tpl'
			sName = 'actionbar_item_select'
			sClasses = 'actionbar-item-link'
			sText = 'Выбрать'
			aMenu = $_aItems}
{/block}