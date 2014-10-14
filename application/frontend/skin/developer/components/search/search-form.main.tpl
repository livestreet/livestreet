{**
 * Форма основного поиска (по топикам и комментариям)
 *
 * @styles css/forms.css
 *}

{include 'components/search-form/search-form.tpl' sName='main' sAction="{router page='search'}{$sSearchType|default:'topics'}" sMods=$smarty.local.sMods}