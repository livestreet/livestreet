					<ul class="stream-content">
						{foreach from=$oTopics item=oTopic name="cmt"}
							<li {if $smarty.foreach.cmt.iteration % 2 == 1}class="even"{/if}>
								<a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_PROFILE}/{$oTopic->getUserLogin()}/" class="stream-author">{$oTopic->getUserLogin()}</a>&nbsp;&#8594;
								<span class="stream-topic-icon"></span><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_BLOG}/{if $oTopic->getBlogUrl()}{$oTopic->getBlogUrl()}/{/if}{$oTopic->getId()}.html" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
								<span>{$oTopic->getCountComment()}</span> в <a href="{$oTopic->getBlogUrlFull()}" class="stream-blog">{$oTopic->getBlogTitle()|escape:'html'}</a>
							</li>						
						{/foreach}				
					</ul>