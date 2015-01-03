{**
 * Черный список
 *
 * @param array $users
 *}

{component 'user-list-add'
    users   = $smarty.local.users
    title   = $aLang.talk.blacklist.title
    note    = $aLang.talk.blacklist.note
    classes = 'js-user-list-add-blacklist'}