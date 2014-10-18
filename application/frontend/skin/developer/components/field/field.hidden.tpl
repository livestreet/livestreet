{**
 * Скрытое поле
 *
 * @param name       string  (null)     Атрибут name
 * @param id         string  (null)     Атрибут id
 * @param value      string  (null)     Атрибут name
 * @param classes    string  (null)     Дополнительные классы (указываются через пробел)
 * @param attributes string  (null)     Атрибуты (указываются через пробел)
 *}

{extends './field.tpl'}

{block 'field'}
    <input type="hidden" {field_input_attr_common} />
{/block}