{if $aCityList && count($aCityList)>0}
	<div class="block tags">				
		<h3>{$aLang.block_city_tags}</h3>					
		<ul class="cloud">
			{foreach from=$aCityList item=aCity}
				<li><a class="w{$aCity.size}" rel="tag" href="{router page='people'}city/{$aCity.name|escape:'html'}/" >{$aCity.name|escape:'html'}</a></li>	
			{/foreach}					
		</ul>									
	</div>
{/if}