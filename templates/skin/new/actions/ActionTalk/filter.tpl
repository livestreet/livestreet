			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.talk_filter_title}</h1>
					
					<div class="block-content">
						<form action="" method="GET" name="talk_filter_form">
							<p><label for="topic_title">{$aLang.talk_filter_label_sender}:</label><br />
							<input type="text" id="talk_filter_sender" name="sender" value="{$_aRequest.sender}" class="w100p" /><br />
       						<span class="form_note">{$aLang.talk_filter_notice_sender}</span>
							</p>						
							<p><label for="topic_title">{$aLang.talk_filter_label_keyword}:</label><br />
							<input type="text" id="talk_filter_keyword" name="keyword" value="{$_aRequest.keyword}" class="w100p" /><br />
       						<span class="form_note">{$aLang.talk_filter_notice_keyword}</span>
							</p>
							
							<p><label for="topic_title">{$aLang.talk_filter_label_date}:</label><br />
							<input type="text" id="talk_filter_start" name="start" value="{$_aRequest.start}" class="w100p" style="width: 45%" /> &mdash; 
							<input type="text" id="talk_filter_end" name="end" value="{$_aRequest.end}" class="w100p" style="width: 45%" /><br />
       						<span class="form_note">{$aLang.talk_filter_notice_date}</span>
							</p>	
							<p class="buttons">								
								<input type="submit" name="submit_talk_filter" value="{$aLang.talk_filter_submit}"/>
							</p>													
						</form>
					</div>
					<div class="right"><a href="{router page='talk'}">{$aLang.talk_filter_erase}</a></div>					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>