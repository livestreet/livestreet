{**
 * Форма поиска блогов
 *}

{include 'components/search-form/search-form.tpl'
    name            = 'blog'
    method          = 'post'
    placeholder     = $aLang.user.search.placeholder
    classes         = 'js-tag-search-form'
    inputClasses    = 'js-search-text-main'
    inputName       = 'user_login'
    noSubmitButton  = true}