{**
 * Форма основного поиска (по топикам и комментариям)
 *
 * @styles css/forms.css
 *}

{component 'search-form' name='main' action="{router page='search'}{$smarty.local.searchType|default:'topics'}" mods=$smarty.local.mods}