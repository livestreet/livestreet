{if $oUserCurrent}
<div class="block stream-settings">
    <div class="tl"><div class="tr"></div></div>
    <div class="cl"><div class="cr">

        <h1>{$aLang.userfeed_block_blogs_title}</h1>
		
		<p class="sp-note">{$aLang.userfeed_settings_note_follow_blogs}</p>

        {if count($aUserfeedBlogs)}
            <ul class="stream-settings-blogs">
                {foreach from=$aUserfeedBlogs item=oBlog}
                    {assign var=iBlogId value=$oBlog->getId()}
                    <li><input class="userfeedBlogCheckbox input-checkbox"
                                type="checkbox"
                                {if isset($aUserfeedSubscribedBlogs.$iBlogId)} checked="checked"{/if}
                                onClick="if ($(this).get('checked')) { lsUserfeed.subscribe('blogs',{$iBlogId}) } else { lsUserfeed.unsubscribe('blogs',{$iBlogId}) } " />
                        <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
                    </li>
                {/foreach}
            </ul>
        {else}
             <p>{$aLang.userfeed_no_blogs}</p>
        {/if}
    </div></div>
    <div class="bl"><div class="br"></div></div>
</div>
{/if}