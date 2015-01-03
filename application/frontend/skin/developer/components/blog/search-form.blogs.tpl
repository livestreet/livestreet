{**
 * Форма поиска блогов
 *}

{component 'search-form'
    name            = 'blog'
    method          = 'post'
    placeholder     = $aLang.blog.search.placeholder
    inputClasses    = 'js-search-text-main'
    inputName       = 'blog_title'
    noSubmitButton  = true}