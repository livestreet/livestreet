		{if $aCountryList && count($aCountryList)>0}
			<div class="block white tags">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">					
					<h1>{$aLang.block_country_tags}</h1>					
					<ul class="cloud">
						{foreach from=$aCountryList item=aCountry}
							<li><a class="w{$aCountry.size}" rel="tag" href="{router page='people'}country/{$aCountry.name|escape:'html'}/">{$aCountry.name|escape:'html'}</a></li>	
						{/foreach}					
					</ul>									
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
		{/if}