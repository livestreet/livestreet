{if $aCountryList && count($aCountryList)>0}
	<section class="block">				
		<h3>{$aLang.block_country_tags}</h3>
		
		<ul class="tag-cloud">
			{foreach from=$aCountryList item=aCountry}
				<li><a class="tag-size-{$aCountry.size}" href="{router page='people'}country/{$aCountry.name|escape:'url'}/">{$aCountry.name|escape:'html'}</a></li>	
			{/foreach}					
		</ul>									
	</section>
{/if}