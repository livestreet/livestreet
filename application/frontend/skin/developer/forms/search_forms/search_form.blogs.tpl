{**
 * Форма поиска блогов
 *
 * @styles css/forms.css
 *}

{include 'components/search-form/search-form.tpl'
		sName            = 'blog'
		sMethod          = 'post'
		sPlaceholder     = $aLang.blog.search.placeholder
		sClasses         = 'js-tag-search-form'
		sInputClasses    = 'js-search-ajax-option js-search-text-main'
		sInputAttributes = 'data-type="blogs"'
		sInputName       = 'blog_title'
		bNoSubmitButton  = true}