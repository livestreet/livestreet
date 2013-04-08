{extends file='modals/modal_base.tpl'}

{block name='id'}modal-blog-delete{/block}
{block name='class'}modal-blog-delete js-modal-default{/block}
{block name='title'}{$aLang.blog_admin_delete_title}{/block}

{block name='content'}
	<form action="{router page='blog'}delete/{$oBlog->getId()}/" method="POST" id="js-blog-delete-form">
		<p><label for="topic_move_to">{$aLang.blog_admin_delete_move}:</label>
		<select name="topic_move_to" id="topic_move_to" class="input-width-full">
			<option value="-1">{$aLang.blog_delete_clear}</option>
			{if $aBlogs}
				<optgroup label="{$aLang.blogs}">
					{foreach from=$aBlogs item=oBlogDelete}
						<option value="{$oBlogDelete->getId()}">{$oBlogDelete->getTitle()|escape:'html'}</option>
					{/foreach}
				</optgroup>
			{/if}
		</select></p>

		<input type="hidden" value="{$LIVESTREET_SECURITY_KEY}" name="security_ls_key" />
	</form>
{/block}

{block name='footer'}
	<button type="submit" class="button button-primary" onclick="jQuery('#js-blog-delete-form').submit()">{$aLang.blog_delete}</button>
{/block}