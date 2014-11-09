{**
 * Черный список
 *
 * @param array $users
 *}

{include 'components/user-list-add/user-list-add.tpl'
    users   = $smarty.local.users
    title   = $aLang.talk.blacklist.title
    note    = $aLang.talk.blacklist.note
    classes = 'js-user-list-add-blacklist'}