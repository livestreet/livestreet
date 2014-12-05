{**
 * Форма основного поиска (по топикам и комментариям)
 *
 * @styles css/forms.css
 *}

{include 'components/search-form/search-form.tpl' name='main' action="{router page='search'}{$smarty.local.searchType|default:'topics'}" mods=$smarty.local.mods}