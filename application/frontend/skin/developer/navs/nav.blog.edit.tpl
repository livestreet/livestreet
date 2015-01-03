{**
 * Навгиация редактирования блога
 *}

{component 'nav'
    name          = 'blog_edit'
    activeItem    = $sMenuItemSelect
    mods          = 'pills'
    items = [
        [ 'name' => 'profile', 'url' => "{router page='blog'}edit/{$blogEdit->getId()}/",  'text' => $aLang.blog.admin.nav.profile ],
        [ 'name' => 'admin',   'url' => "{router page='blog'}admin/{$blogEdit->getId()}/", 'text' => $aLang.blog.admin.nav.users ]
    ]}