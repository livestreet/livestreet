# Компонент favourite

Избранное


## Использование

Пример использования в плагине.

_Шаблон с изображением_ **image.tpl**
```smarty
...
{include 'components/favourite/favourite.tpl' classes='js-plugin-gallery-image-favourite' target=$image}
...
```

_Файл иниц-ии js плагина_ **init.js**
```js
$('.js-plugin-gallery-image-favourite').lsFavourite({
    urls: {
        toggle: aRouter['gallery'] + 'image/favourite/',
    }
});
```