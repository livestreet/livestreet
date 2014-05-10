# Компонент button

Кнопка


## Шаблоны

### button.tpl

| Опция         | Тип         | По&nbsp;умолчанию  | Описание |
| :------------ | :---------- | :----------------- | :------- |
| `sType`       | string      | 'submit'           | Тип кнопки (submit, button) |
| `sText`       | string      | null               | Текст кнопки |
| `sUrl`        | string      | null               | Ссылка |
| `sId`         | string      | null               | Атрибут id |
| `sName`       | string      | null               | Атрибут name |
| `bIsDisabled` | boolean     | false              | Атрибут disabled |
| `sForm`       | string      | null               | Селектор формы для сабмита |
| `sIcon`       | string      | null               | Класс иконки |
| `sClasses`    | string      | null               | Дополнительные классы (указываются через пробел) |
| `sMods`       | string      | null               | Список классов-модификаторов (указываются через пробел) |
| `sAttributes` | string      | null               | Атрибуты (указываются через пробел) |


## Использование

Кнопка с дефолтным оформлением:
```smarty
{include 'components/button/button.tpl' sText='Отправить'}
```

Primary-кнопка:
```smarty
{include 'components/button/button.tpl' sMods='primary' sText='Отправить'}
```

Кнопка-ссылка:
```smarty
{include 'components/button/button.tpl' sUrl='/topic/edit/1' sText='Редактировать'}
```