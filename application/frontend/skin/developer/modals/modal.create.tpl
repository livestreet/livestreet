{**
 * Модальное с меню "Создать"
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block name='modal_id'}modal-write{/block}
{block name='modal_class'}modal-write js-modal-default{/block}
{block name='modal_title'}{$aLang.block_create}{/block}

{block name='modal_content'}
	{function modal_create_item}
		<li class="write-item-type-{$sName}">
			{$sUrl = "{if ! $url}{router page=$sName}add{else}{$url}{/if}"}

			<a href="{$sUrl}" class="write-item-image"></a>
			<a href="{$sUrl}" class="write-item-link">{$sTitle}</a>
		</li>
	{/function}

	<ul class="write-list clearfix">
		{$aTopicTypes=$LS->Topic_GetTopicTypes()}
		{foreach $aTopicTypes as $oTopicType}
			{modal_create_item sName='topic' url=$oTopicType->getUrlForAdd() sTitle=$oTopicType->getName()}
		{/foreach}
		{modal_create_item sName='blog' sTitle=$aLang.block_create_blog}
		{modal_create_item sName='talk' sTitle=$aLang.block_create_talk}
		{modal_create_item sName='draft' url="{router page='content'}drafts/" sTitle="{$aLang.topic_menu_drafts} {if $iUserCurrentCountTopicDraft}({$iUserCurrentCountTopicDraft}){/if}"}

		{hook run='write_item' isPopup=true}
	</ul>
{/block}

{block name='modal_footer'}{/block}