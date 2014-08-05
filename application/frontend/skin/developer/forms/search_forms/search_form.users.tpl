{**
 * Форма поиска блогов
 *
 * @styles css/forms.css
 *}

{include 'components/search_form/search_form.tpl'
		sName            = 'blog'
		sMethod          = 'post'
		sPlaceholder     = $aLang.user.search.placeholder
		sClasses         = 'js-tag-search-form'
		sInputClasses    = 'js-search-ajax-option js-search-text-main'
		sInputAttributes = 'data-type="users"'
		sInputName       = 'user_login'
		bNoSubmitButton  = true}