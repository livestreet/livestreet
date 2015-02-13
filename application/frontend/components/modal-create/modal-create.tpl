{**
 * Модальное с меню "Создать"
 *}

{capture 'modal_content'}
    {function modal_create_item}
        <li class="write-item-type-{$item}">
            {$url = "{if ! $url}{router page=$item}add{else}{$url}{/if}"}

            <a href="{$url}" class="write-item-image"></a>
            <a href="{$url}" class="write-item-link">{$title}</a>
        </li>
    {/function}

    <ul class="write-list clearfix">
        {foreach $LS->Topic_GetTopicTypes() as $type}
            {modal_create_item item='topic' url=$type->getUrlForAdd() title=$type->getName()}
        {/foreach}

        {modal_create_item item='blog' title={lang 'modal_create.items.blog'}}
        {modal_create_item item='talk' title={lang 'modal_create.items.talk'}}
        {modal_create_item item='draft' url="{router page='content'}drafts/" title="{$aLang.topic.drafts} {if $iUserCurrentCountTopicDraft}({$iUserCurrentCountTopicDraft}){/if}"}

        {hook run='write_item' isPopup=true}
    </ul>
{/capture}

{component 'modal'
    title   = {lang 'modal_create.title'}
    content = $smarty.capture.modal_content
    classes = 'js-modal-default'
    mods    = 'create'
    id      = 'modal-write'}