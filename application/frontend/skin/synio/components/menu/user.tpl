{**
 * Меню пользователя
 *
 * 
 *}
{if is_array($params.items)}
    {$params.items[] = [ 
        'name' => 'logout',     
        'text' => {lang name='auth.logout'},                   
        'url' => "{router page='auth'}logout/?security_ls_key={$LIVESTREET_SECURITY_KEY}" ]}
{/if}
 
{component 'nav'
    classes = 'ls-userbar-user-nav-menu js-userbar-user-nav-menu'
    hook = 'user'
    hookParams = [ user => $oUserCurrent ]
    params = $params}
