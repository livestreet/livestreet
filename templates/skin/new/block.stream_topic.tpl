					<ul class="stream-content">
						{foreach from=$oTopics item=oTopic name="cmt"}
							{assign var="oUser" value=$oTopic->getUser()}							
							{assign var="oBlog" value=$oTopic->getBlog()}
							
							<li {if $smarty.foreach.cmt.iteration % 2 == 1}class="even"{/if}>
								<a href="{$oUser->getUserWebPath()}" class="stream-author">{$oUser->getLogin()}</a>&nbsp;&#8594;
								<span class="stream-topic-icon"></span><a href="{$oTopic->getUrl()}" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
								<span>{$oTopic->getCountComment()}</span> в <a href="{$oBlog->getUrlFull()}" class="stream-blog">{$oBlog->getTitle()|escape:'html'}</a>
							</li>						
						{/foreach}				
					</ul>

					<div class="right"><a href="{router page='new'}">{$aLang.block_stream_topics_all}</a> | <a href="{router page='rss'}new/">RSS</a></div>
					