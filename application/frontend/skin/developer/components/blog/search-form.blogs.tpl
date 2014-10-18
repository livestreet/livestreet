{**
 * Форма поиска блогов
 *
 * @styles css/forms.css
 *}

{include 'components/search-form/search-form.tpl'
		name            = 'blog'
		method          = 'post'
		placeholder     = $aLang.blog.search.placeholder
		classes         = 'js-tag-search-form'
		inputClasses    = 'js-search-ajax-option js-search-text-main'
		inputAttributes = 'data-type="blogs"'
		inputName       = 'blog_title'
		noSubmitButton  = true}