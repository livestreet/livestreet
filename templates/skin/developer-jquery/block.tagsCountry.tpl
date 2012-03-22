{if $aCountryList && count($aCountryList)>0}
	<section class="block">
		<header class="block-header">
			<h3>{$aLang.block_country_tags}</h3>
		</header>
		
		
		<div class="block-content">
			<ul class="tag-cloud">
				{foreach from=$aCountryList item=aCountry}
					<li><a class="tag-size-{$aCountry.size}" href="{router page='people'}country/{$aCountry.name|escape:'url'}/">{$aCountry.name|escape:'html'}</a></li>	
				{/foreach}					
			</ul>	
		</div>		
	</section>
{/if}