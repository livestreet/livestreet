{**
 * Выбор файла
 *}

{extends './field.tpl'}

{block 'field' prepend}
    {$_mods = "$_mods file"}
    {$uploadedFiles = $smarty.local.uploadedFiles}
{/block}

{block 'field_input'}
    <input type="file" {field_input_attr_common} />

    {if $uploadedFiles}
        <div class="field-file-info">
            {block 'field_file_info'}
                <p>Загружен файл: <strong>{$uploadedFiles.name}.{$uploadedFiles.extension}</strong></p>
            {/block}

            <label>
                <input type="checkbox" name="{$smarty.local.removeName}" value="1"> {lang 'common.remove'}
            </label>
        </div>
    {/if}
{/block}