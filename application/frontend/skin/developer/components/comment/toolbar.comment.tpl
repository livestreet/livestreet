{**
 * Тулбар
 * Кнопка обновления комментариев
 *}

{extends 'components/toolbar/toolbar.item.tpl'}

{block 'toolbar_item_options' append}
    {$_mods = 'comments'}
    {$_bShow = !! $oUserCurrent}
    {$_classes = 'js-comments-toolbar'}
    {$_attributes = [ 'data-target' => '.js-comment' ]}
{/block}

{block 'toolbar_item'}
    <div class="toolbar-comments-update js-toolbar-comments-update"><i></i></div>
    <div class="toolbar-comments-count js-toolbar-comments-count" style="display: none;" title="{$aLang.comments.comment.count_new}"></div>
{/block}