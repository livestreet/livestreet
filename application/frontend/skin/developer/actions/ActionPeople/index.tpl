{**
 * Список всех пользователей
 *
 * @param array   $users
 * @param integer $searchCount
 * @param array   $countriesUsed
 * @param array   $paging
 * @param array   $usersStat
 *}

{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    {$aLang.user.users}
{/block}

{block 'layout_content'}
    {component 'user' template='search-form'}

    {* Сортировка *}
    {component 'sort' template='ajax'
        classes = 'js-search-sort js-search-sort-menu'
        text = $aLang.sort.by_rating
        items = [
            [ name => 'user_rating',        text => $aLang.sort.by_rating, order => 'asc' ],
            [ name => 'user_login',         text => $aLang.sort.by_login ],
            [ name => 'user_date_register', text => $aLang.sort.by_date_registration ]
        ]}

    <div class="js-search-ajax-users">
        {component 'user' template='list' users=$users useMore=true}
    </div>
{/block}