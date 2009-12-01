					<ul class="stream-content">
						{foreach from=$aComments item=oComment name="cmt"}
							{assign var="oUser" value=$oComment->getUser()}
							{assign var="oTopic" value=$oComment->getTarget()}
							{assign var="oBlog" value=$oTopic->getBlog()}
							
							<li {if $smarty.foreach.cmt.iteration % 2 == 1}class="even"{/if}>
								<a href="{$oUser->getUserWebPath()}" class="stream-author">{$oUser->getLogin()}</a>&nbsp;&#8594;
								<span class="stream-comment-icon"></span><a href="{$oTopic->getUrl()}#comment{$oComment->getId()}" class="stream-comment">{$oTopic->getTitle()|escape:'html'}</a>
								<span> {$oTopic->getCountComment()}</span> в <a href="{$oBlog->getUrlFull()}" class="stream-blog">{$oBlog->getTitle()|escape:'html'}</a>
							</li>
						{/foreach}
					</ul>

					<div class="right"><a href="{router page='comments'}">{$aLang.block_stream_comments_all}</a> | <a href="{router page='rss'}allcomments/">RSS</a></div>
					