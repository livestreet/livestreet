{**
 * Навгиация редактирования блога
 *}

{include 'components/nav/nav.tpl'
    name          = 'blog_edit'
    activeItem    = $sMenuItemSelect
    mods          = 'pills'
    items = [
        [ 'name' => 'profile', 'url' => "{router page='blog'}edit/{$oBlogEdit->getId()}/",  'text' => $aLang.blog.admin.nav.profile ],
        [ 'name' => 'admin',   'url' => "{router page='blog'}admin/{$oBlogEdit->getId()}/", 'text' => $aLang.blog.admin.nav.users ]
    ]}