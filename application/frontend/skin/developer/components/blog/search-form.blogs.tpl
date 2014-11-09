{**
 * Форма поиска блогов
 *}

{include 'components/search-form/search-form.tpl'
    name            = 'blog'
    method          = 'post'
    placeholder     = $aLang.blog.search.placeholder
    inputClasses    = 'js-search-text-main'
    inputName       = 'blog_title'
    noSubmitButton  = true}