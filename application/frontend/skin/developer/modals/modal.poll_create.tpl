{**
 * Создание опроса
 *
 * @styles css/modals.css
 *}

{extends 'components/modal/modal.tpl'}

{block 'modal_id'}modal-poll-create{/block}
{block 'modal_class'}modal-poll-create js-modal-default{/block}
{block 'modal_title'}
	{if $oPoll}
		{$aLang.poll.form.title.edit}
	{else}
		{$aLang.poll.form.title.add}
	{/if}
{/block}

{block 'modal_content'}
	{include 'components/poll/poll.form.tpl'}
{/block}

{block 'modal_footer_begin'}
	{include 'components/button/button.tpl'
			 form    = 'js-poll-form'
			 text    =  ($oPoll) ? $aLang.common.save : $aLang.common.add
			 classes = 'js-poll-form-submit'
			 mods    = 'primary'}
{/block}