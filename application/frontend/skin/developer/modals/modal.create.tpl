{**
 * Модальное с меню "Создать"
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-write{/block}
{block 'modal_class'}modal-write js-modal-default{/block}
{block 'modal_title'}{lang 'modal_create.title'}{/block}

{block 'modal_content'}
	{function modal_create_item}
		<li class="write-item-type-{$item}">
			{$sUrl = "{if ! $url}{router page=$item}add{else}{$url}{/if}"}

			<a href="{$sUrl}" class="write-item-image"></a>
			<a href="{$sUrl}" class="write-item-link">{$sTitle}</a>
		</li>
	{/function}

	<ul class="write-list clearfix">
		{$types = $LS->Topic_GetTopicTypes()}

		{foreach $types as $type}
			{modal_create_item item='topic' url=$type->getUrlForAdd() sTitle=$type->getName()}
		{/foreach}

		{modal_create_item item='blog' sTitle={lang 'modal_create.items.blog'}}
		{modal_create_item item='talk' sTitle={lang 'modal_create.items.talk'}}
		{modal_create_item item='draft' url="{router page='content'}drafts/" sTitle="{$aLang.topic.drafts} {if $iUserCurrentCountTopicDraft}({$iUserCurrentCountTopicDraft}){/if}"}

		{hook run='write_item' isPopup=true}
	</ul>
{/block}

{block 'modal_footer'}{/block}