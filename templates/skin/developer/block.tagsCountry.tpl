{if $aCountryList && count($aCountryList)>0}
	<div class="block tags">				
		<h3>{$aLang.block_country_tags}</h3>					
		<ul class="cloud">
			{foreach from=$aCountryList item=aCountry}
				<li><a class="w{$aCountry.size}" rel="tag" href="{router page='people'}country/{$aCountry.name|escape:'html'}/">{$aCountry.name|escape:'html'}</a></li>		
			{/foreach}					
		</ul>									
	</div>
{/if}