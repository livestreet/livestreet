{if $aCityList && count($aCityList)>0}
	<section class="block">
		<header class="block-header">
			<h3>{$aLang.block_city_tags}</h3>
		</header>
		
		
		<div class="block-content">
			<ul class="tag-cloud">
				{foreach from=$aCityList item=aCity}
					<li><a class="tag-size-{$aCity.size}" href="{router page='people'}city/{$aCity.name|escape:'url'}/">{$aCity.name|escape:'html'}</a></li>	
				{/foreach}					
			</ul>	
		</div>		
	</section>
{/if}