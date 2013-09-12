{**
 * Модальное с меню "Создать"
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-write{/block}
{block name='modal_class'}modal-write js-modal-default{/block}
{block name='modal_title'}{$aLang.block_create}{/block}

{block name='modal_content'}
	{strip}
		<ul class="write-list">
			<li class="write-item-type-topic">
				<a href="{router page='topic'}add" class="write-item-image"></a>
				<a href="{router page='topic'}add" class="write-item-link">{$aLang.block_create_topic_topic}</a>
			</li>
			<li class="write-item-type-blog">
				<a href="{router page='blog'}add" class="write-item-image"></a>
				<a href="{router page='blog'}add" class="write-item-link">{$aLang.block_create_blog}</a>
			</li>
			<li class="write-item-type-message">
				<a href="{router page='talk'}add" class="write-item-image"></a>
				<a href="{router page='talk'}add" class="write-item-link">{$aLang.block_create_talk}</a>
			</li>
			<li class="write-item-type-draft">
				<a href="{router page='topic'}drafts/" class="write-item-image"></a>
				<a href="{router page='topic'}drafts/" class="write-item-link">{$aLang.topic_menu_drafts} {if $iUserCurrentCountTopicDraft}({$iUserCurrentCountTopicDraft}){/if}</a>
			</li>
			{hook run='write_item' isPopup=true}
		</ul>
	{/strip}
{/block}

{block name='modal_footer'}{/block}