{**
 * Создание опроса
 *
 * @styles css/modals.css
 *}

{extends file='modals/modal_base.tpl'}

{block name='modal_id'}modal-poll-create{/block}
{block name='modal_class'}modal-poll-create js-modal-default{/block}
{block name='modal_title'}
	{if $oPoll}
		{$aLang.poll.form.title.edit}
	{else}
		{$aLang.poll.form.title.add}
	{/if}
{/block}

{block name='modal_content'}
	{include 'polls/poll.form.tpl'}
{/block}

{block name='modal_footer_begin'}
	{include file='forms/fields/form.field.button.tpl'
			 sFieldAttributes = 'data-button-submit-form="form-poll-create"'
			 sFieldText       =  ($oPoll) ? $aLang.common.save : $aLang.common.add
			 sFieldClasses    = 'js-poll-form-submit'
			 sFieldStyle      = 'primary'}
{/block}