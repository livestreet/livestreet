# Компонент pagination

Пагинация


## Шаблоны

### pagination.tpl

| Опция         | Тип         | По&nbsp;умолчанию  | Описание |
| :------------ | :---------- | :----------------- | :------- |
| `aPaging`     | boolean     | null               | Массив с параметрами пагинации |
| `sClasses`    | string      | null               | Дополнительные классы (указываются через пробел) |
| `sMods`       | string      | null               | Список классов-модификаторов (указываются через пробел) |
| `sAttributes` | string      | null               | Атрибуты (указываются через пробел) |

Параметры содержащиеся в массиве aPaging:

| Опция           | Тип          | По&nbsp;умолчанию | Описание |
| :-------------- | :----------- | :---------------- | :------- |
| `iCountPage`    | integer      | null              | Общее кол-во страниц |
| `iCurrentPage`  | integer      | null              | Текущая страница |
| `iPrevPage`     | integer      | null              | Предыдущая страница |
| `iNextPage`     | integer      | null              | Следующая страница |
| `aPagesLeft`    | array        | null              | Страницы слева от текущей |
| `aPagesRight`   | array        | null              | Страницы справа от текущей |


## Скрипты

TODO


## Использование

Пример использования.

_Шаблон со списком объектов_ **topic_list.tpl**
```smarty
{include 'components/pagination/pagination.tpl' aPaging=$aTopicPaging}
```