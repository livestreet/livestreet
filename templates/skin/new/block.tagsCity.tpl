		{if $aCityList && count($aCityList)>0}
			<div class="block white tags">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">					
					<h1>{$aLang.block_city_tags}</h1>					
					<ul class="cloud">
						{foreach from=$aCityList item=aCity}
							<li><a class="w{$aCity.size}" rel="tag" href="{router page='people'}city/{$aCity.name|escape:'html'}/" >{$aCity.name|escape:'html'}</a></li>	
						{/foreach}					
					</ul>									
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
		{/if}