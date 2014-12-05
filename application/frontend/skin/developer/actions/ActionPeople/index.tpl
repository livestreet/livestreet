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
    {include 'components/user/search-form.users.tpl'}

    {* Сортировка *}
    {include 'components/sort/sort.ajax.tpl'
        classes = 'js-search-sort'
        items = [
            [ name => 'user_rating',        text => $aLang.sort.by_rating, order => 'asc' ],
            [ name => 'user_login',         text => $aLang.sort.by_login ],
            [ name => 'user_date_register', text => $aLang.sort.by_date_registration ]
        ]}

    <div class="js-search-ajax-users">
        {include 'components/user/user-list.tpl' users=$users useMore=true}
    </div>
{/block}