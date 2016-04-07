{**
 * Черный список
 *
 * @param array $users
 *}

{component_define_params params=[ 'users' ]}

{component 'user-list-add'
    title   = $aLang.talk.blacklist.title
    note    = $aLang.talk.blacklist.note
    classes = 'js-user-list-add-blacklist'}