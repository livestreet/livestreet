{include file='header.tpl'}

{include file='menu.blog.tpl'}

<br>

 <table width="100%" border="0" cellspacing="4" cellpadding="4" class="beach_party_ireland">
    <tr>

     <td nowrap align="left"><span class="headline_tags">
     <img src="{$DIR_STATIC_SKIN}/img/tagcloud.gif" border="0" width="19" height="17" title="облачко" alt="" >&nbsp;&#8594;</span></td>
     <form action="" method="GET" style="margin: 0px;"  onsubmit="return submitTags(this.tag.value);">
     <input type="hidden" name="mode" value="blog">
     <td width="100%"><input type="text" name="tag" value="{$sTag|escape:'html'}" style="width: 100%; padding-left: 4px; padding-right: 4px;" class="tag_form"></td>
     </form>
     
    </tr>
   </table>



{include file='topic_list.tpl'}


{include file='footer.tpl'}

