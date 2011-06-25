<div class="block">
    <div class="tl"><div class="tr"></div></div>
    <div class="cl"><div class="cr">

        <h1>{$aLang.userfeed_block_blogs_title}</h1>

        {if count($aUserfeedBlogs)}
            <ul>
                {foreach from=$aUserfeedBlogs item=oBlog}
                    {assign var=iBlogId value=$oBlog->getId()}
                    <li><input class="userfeedBlogCheckbox"
                                type="checkbox"
                                {if isset($aUserfeedSubscribedBlogs.$iBlogId)} checked="checked"{/if}
                                onClick="if (jQuery(this).attr('checked')) { ls.userfeed.subscribe('blogs',{$iBlogId}) } else { ls.userfeed.unsubscribe('blogs',{$iBlogId}) } " />
                        <a href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()}</a>
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div></div>
    <div class="bl"><div class="br"></div></div>
</div>
