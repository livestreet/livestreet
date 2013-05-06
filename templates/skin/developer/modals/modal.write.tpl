{**
 * Модальное с меню "Создать"
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_options'}
	{assign var='noModalFooter' value=true}
{/block}

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
			<li class="write-item-type-poll">
				<a href="{router page='question'}add" class="write-item-image"></a>
				<a href="{router page='question'}add" class="write-item-link">{$aLang.block_create_topic_question}</a>
			</li>
			<li class="write-item-type-link">
				<a href="{router page='link'}add" class="write-item-image"></a>
				<a href="{router page='link'}add" class="write-item-link">{$aLang.block_create_topic_link}</a>
			</li>
			<li class="write-item-type-photoset">
				<a href="{router page='photoset'}add" class="write-item-image"></a>
				<a href="{router page='photoset'}add" class="write-item-link">{$aLang.block_create_topic_photoset}</a>
			</li>
			<li class="write-item-type-blog">
				<a href="{router page='blog'}add" class="write-item-image"></a>
				<a href="{router page='blog'}add" class="write-item-link">{$aLang.block_create_blog}</a>
			</li>
			<li class="write-item-type-draft">
				<a href="{router page='topic'}saved/" class="write-item-image"></a>
				<a href="{router page='topic'}saved/" class="write-item-link">{$aLang.topic_menu_saved} {if $iUserCurrentCountTopicDraft}({$iUserCurrentCountTopicDraft}){/if}</a>
			</li>
			{hook run='write_item' isPopup=true}
		</ul>
	{/strip}
{/block}