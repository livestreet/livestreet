			<div class="block tags">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<ul class="cloud">						
						{foreach from=$aTags item=oTag}
							<li><a class="w{$oTag->getSize()}" rel="tag" href="{router page='tag'}{$oTag->getText()|escape:'html'}/">{$oTag->getText()|escape:'html'}</a></li>	
						{/foreach}
					</ul>
					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>