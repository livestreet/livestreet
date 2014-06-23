{**
 * Создание опроса
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

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
	{include 'components/poll/poll.form.tpl'}
{/block}

{block name='modal_footer_begin'}
	{include 'components/button/button.tpl'
			 sForm    = '#js-poll-form'
			 sText    =  ($oPoll) ? $aLang.common.save : $aLang.common.add
			 sClasses = 'js-poll-form-submit'
			 sMods    = 'primary'}
{/block}