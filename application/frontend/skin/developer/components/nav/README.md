# Компонент nav

Навигация


## Использование

Простое вложенное меню:

```smarty
{include 'components/nav/nav.tpl' sMods='main' sActiveItem='home' aItems=[
    [ 'name' => 'home',    'text' => 'Главная',  'url' => '/' ],
    [ 'name' => 'contact', 'text' => 'Контакты', 'url' => '/contact' ],
    [ 'name' => 'about',   'text' => 'О нас',    'url' => '/about', 'menu' => [
        [ 'name' => 'about_company', 'text' => 'О компании', 'url' => '/about/company' ],
        [ 'name' => 'about_team',    'text' => 'О команде',  'url' => '/about/team' ]
    ] ]
]}
```