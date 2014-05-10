{**
 * Скрытое поле
 *
 * @param sName       string  (null)     Атрибут name
 * @param sId         string  (null)     Атрибут id
 * @param sValue      string  (null)     Атрибут name
 * @param sClasses    string  (null)     Дополнительные классы (указываются через пробел)
 * @param sAttributes string  (null)     Атрибуты (указываются через пробел)
 *}

{extends './field.tpl'}

{block 'field'}
    <input type="hidden" {field_input_attr_common} />
{/block}