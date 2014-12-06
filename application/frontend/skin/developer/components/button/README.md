# Компонент button

Кнопка


## Шаблоны

### button.tpl

| Опция         | Тип         | По&nbsp;умолчанию  | Описание |
| :------------ | :---------- | :----------------- | :------- |
| `type`        | string      | 'submit'           | Тип кнопки (submit, button) |
| `text`        | string      | null               | Текст кнопки |
| `url`         | string      | null               | Ссылка |
| `id`          | string      | null               | Атрибут id |
| `name`        | string      | null               | Атрибут name |
| `isDisabled`  | boolean     | false              | Атрибут disabled |
| `form`        | string      | null               | Селектор формы для сабмита |
| `icon`        | string      | null               | Класс иконки |
| `classes`     | string      | null               | Дополнительные классы (указываются через пробел) |
| `mods`        | string      | null               | Список классов-модификаторов (указываются через пробел) |
| `attributes`  | array       | null               | Атрибуты |


## Использование

Кнопка с дефолтным оформлением:
```smarty
{include 'components/button/button.tpl' text='Отправить'}
```

Primary-кнопка:
```smarty
{include 'components/button/button.tpl' mods='primary' text='Отправить'}
```

Кнопка-ссылка:
```smarty
{include 'components/button/button.tpl' url='/topic/edit/1' text='Редактировать'}
```