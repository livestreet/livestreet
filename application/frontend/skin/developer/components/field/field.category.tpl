{**
 * Вывод категорий на странице создания нового объекта
 *}

{$params = $smarty.local.params}
{$categoriesSelected = $smarty.local.categoriesSelected}
{$categories = $smarty.local.categories}

{* Получаем id выделеных категорий *}
{if $params.form_fill_current_from_request && $_aRequest[ $params.form_field ]}
	{$selected = $_aRequest[ $params.form_field ]}
{elseif $categoriesSelected}
	{$selected = []}

	{foreach $categoriesSelected as $category}
		{$selected[] = $category->getId()}
	{/foreach}
{/if}

{* Формируем список категорий для select'а *}
{$items = []}

{if ! $params.validate_require}
	{$items[] = [ 'value' => '', 'text' => '&mdash;' ]}
{/if}

{foreach $categories as $category}
	{$entity = $category.entity}
	{$items[] = [ 'value' => $entity->getId(), 'text' => $entity->getTitle(), 'level' => $category.level ]}
{/foreach}

{* Селект *}
{* TODO: i18n *}
{component 'field' template='select' name="{$params.form_field}[]" items=$items label='Категория' selectedValue=$selected}