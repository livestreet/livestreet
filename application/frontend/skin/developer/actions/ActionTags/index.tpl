{include file='header.tpl'}


{if !empty($aTags)}
    <ul>
        {foreach from=$aTags item=oTag}
            {assign var="sTag" value=$oTag->getText()}
            <li>
                <a rel="tag" href="{router page='tag'}{$sTag|escape:'url'}/">{$sTag|escape:'html'}</a>
                {if $oUserCurrent->getIsAdministrator()}<a href="#" data-tag="{$sTag|escape:'html'}" class="adminAutoTags">(найти топики)</a>{/if}
            </li>
        {/foreach}
    </ul>
{/if}

{include file='footer.tpl'}