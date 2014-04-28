# Компонент vote

Голосование


## Зависимости

* jquery
* jquery.widget
* tooltip
* ls.utils
* ls.ajax


## Шаблоны

### vote.tpl
Основной шаблон с блоком голосования.

| Опция         | Тип         | По&nbsp;умолчанию  | Описание |
| :------------ | :---------- | :----------------- | :------- |
| `sHeading`    | string      | null               | Заголовок |
| `sClasses`    | string      | null               | Дополнительные классы (указываются через пробел) |
| `sMods`       | string      | null               | Список классов-модификаторов (указываются через пробел) |
| `sAttributes` | string      | null               | Атрибуты (указываются через пробел) |
| `bShowRating` | boolean     | true               | Показывать рейтинг или нет, если false, то значение рейтинга заменяется на _"?"_ |
| `bIsLocked`   | boolean     | false              | Блокировка голосования, если true, то кнопки голосования не будут показываться |

### vote.info.tpl
Шаблон с информацией о голосовании выводимая в тултипе, который появляется при наведении на блок голосования.

| Опция         | Тип         | По умолчанию | Описание                   |
| :------------ | :---------- | :----------- | :------------------------- |
| `oObject`     | object      | null         | Объект с инфо-ей о голосовании |



## Стили

Список модификаторов основного блока

| Мод-ор              | Описание |
| :---------------- | :------- |
| `voted`           | Пользователь проголосовал |
| `not-voted`       | Не проголосовал |
| `voted-up`        | Понравилось |
| `voted-down`      | Не понравилось |
| `voted-zero`      | Воздержался |
| `count-positive`  | Рейтинг больше нуля |
| `count-negative`  | Меньше нуля |
| `count-zero`      | Равен нулю |
| `rating-hidden`   | Рейтинг скрыт |
| `large`           | Большой блок голосования |
| `small`           | Маленький блок голосования |



## Скрипты

### vote.js

Основные опции

| Опция              | Тип         | По&nbsp;умолчанию | Описание                   |
| :----------------- | :---------- | :---------------- | :------------------------- |
| `params`           | object      | null              | Параметры отправляемые при аякс запросе |
| `tooltip_options`  | object      | null              | Опции тултипа с информацией о голосовании |

Ссылки

| Опция         | Тип         | По&nbsp;умолчанию | Описание |
| :------------ | :---------- | :----------- | :------------ |
| `urls.vote`   | string      | null         | Голосование |
| `urls.info`   | string      | null         | Информация о голосовании |

Селекторы

| Опция                | Тип         | По&nbsp;умолчанию | Описание |
| :------------------- | :---------- | :---------------- | :------- |
| `selectors.item`     | string      | '.js-vote-item'   | Кнопки голосования |
| `selectors.rating`   | string      | '.js-vote-rating' | Блок с рейтингом |

Классы

| Опция                    | Тип         | По&nbsp;умолчанию      | Описание                   |
| :----------------------- | :---------- | :--------------------- | :------------------------- |
| `classes.voted`          | string      | 'vote--voted'          | Пользователь проголосовал  |
| `classes.not_voted`      | string      | 'vote--not-voted'      | Не проголосовал |
| `classes.voted_up`       | string      | 'vote--voted-up'       | Понравилось |
| `classes.voted_down`     | string      | 'vote--voted-down'     | Не понравилось |
| `classes.voted_zero`     | string      | 'vote--voted-zero'     | Воздержался |
| `classes.count_positive` | string      | 'vote--count-positive' | Рейтинг больше нуля |
| `classes.count_negative` | string      | 'vote--count-negative' | Меньше нуля |
| `classes.count_zero`     | string      | 'vote--count-zero'     | Равен нулю |
| `classes.rating_hidden`  | string      | 'vote--rating-hidden'  | Рейтинг скрыт |


## Использование

Пример использования в плагине.

_Шаблон с изображением_ **image.tpl**
```smarty
...
{include 'components/vote/vote.tpl' sMods='small' sClasses='js-plugin-gallery-image-vote'}
...
```

_Файл иниц-ии js плагина_ **init.js**
```js
$('.js-plugin-gallery-image-vote').vote({
    urls: {
        vote: aRouter['gallery'] + 'vote/image/',
        info: aRouter['gallery'] + 'vote/info/'
    }
});
```
