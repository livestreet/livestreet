					<ul class="stream-content">
						{foreach from=$aComments item=oComment name="cmt"}
							<li {if $smarty.foreach.cmt.iteration % 2 == 1}class="even"{/if}>
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oComment->getUserLogin()}/" class="stream-author">{$oComment->getUserLogin()}</a>&nbsp;&#8594;
								<span class="stream-comment-icon"></span><a href="{$oComment->getTopicUrl()}#comment{$oComment->getId()}" class="stream-comment">{$oComment->getTopicTitle()|escape:'html'}</a>
								<span> {$oComment->getTopicCountComment()}</span> в <a href="{$oComment->getBlogUrlFull()}" class="stream-blog">{$oComment->getBlogTitle()|escape:'html'}</a>
							</li>						
						{/foreach}				
					</ul>