		{if $aCityList && count($aCityList)>0}
			<div class="block white tags">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">					
					<h1>Города</h1>					
					<ul class="cloud">
						{foreach from=$aCityList item=aCity}
							<li><a class="w{$aCity.size}" rel="tag" href="#" onclick="return false;">{$aCity.name}</a></li>	
						{/foreach}					
					</ul>									
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
		{/if}