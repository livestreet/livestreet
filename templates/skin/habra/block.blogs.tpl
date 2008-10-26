		<div class="habrablock">		
			<h3 class="new_group_sections">Популярные блоги&nbsp;&#8595;</h3>			
			<div class="companyblock">
				  <table width="100%" border="0" cellpadding="5" cellspacing="1">
				   <tr>
				    <td width="5%"></td>
				    <td width=""></td>
				    <td width="22%" align="center" class="company_page_tb_header">Рейтинг</td>
				   </tr>
				   {foreach from=$aBlogs item=oBlog}
				   <tr>
				   	<td>&nbsp;</td>
				    <td valign="middle"><a href="{$DIR_WEB_ROOT}/blog/{$oBlog->getUrl()}/" class="company_name_big_2">{$oBlog->getTitle()|escape:'html'}</a></td>
				    <td nowrap align="center"><span class="company_rating_2">&nbsp;{$oBlog->getRating()}&nbsp;</span></td>
				   </tr>				  
					{/foreach}
				  </table>	<br>
				
			</div>	

			<div class="live_section_title_all" align="right">
				<span style="color:#666666">&#187;</span> <a href="{$DIR_WEB_ROOT}/blogs/">все блоги</a>
			</div>		
		</div>