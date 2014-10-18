{**
 * Форма поиска по тегам
 *
 * @styles css/forms.css
 *}

{include 'components/search-form/search-form.tpl'
		name         = 'tags'
		mods         = $smarty.local.mods
		placeholder  = {lang 'tags.search.label'}
		classes      = 'js-tag-search-form'
		inputClasses = 'autocomplete-tags js-tag-search'
		inputName    = 'tag'
		value        = $sTag|escape}