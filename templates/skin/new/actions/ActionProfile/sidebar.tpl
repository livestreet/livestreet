			{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
			<div class="block actions white">
				<div class="tl"><div class="tr"></div></div>

				<div class="cl"><div class="cr">					
					<ul>
						{if $oUserProfile->getUserIsFrend()}
							<li class="del"><a href="#"  title="{$aLang.user_friend_del}" onclick="ajaxToggleUserFrend(this,{$oUserProfile->getId()}); return false;">{$aLang.user_friend_del}</a></li>
						{else}
							<li class="add"><a href="#"  title="{$aLang.user_friend_add}" onclick="ajaxToggleUserFrend(this,{$oUserProfile->getId()}); return false;">{$aLang.user_friend_add}</a></li>
						{/if}
						<li><a href="{$DIR_WEB_ROOT}/{$ROUTE_PAGE_TALK}/add/?talk_users={$oUserProfile->getLogin()}">{$aLang.user_write_prvmsg}</a></li>						
					</ul>
				</div></div>

				<div class="bl"><div class="br"></div></div>
			</div>
			{/if}
			
			<div class="block contacts nostyle">
				{if $oUserProfile->getProfileIcq()}
				<strong>Контакты и социальные сервисы</strong>
				<ul>
					{if $oUserProfile->getProfileIcq()}
						<li class="icq"><a href="http://wwp.icq.com/scripts/contact.dll?msgto={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank">{$oUserProfile->getProfileIcq()}</a></li>
					{/if}					
				</ul>
				{/if}
				
				{if $oUserProfile->getProfileFoto()}
				<img src="{$DIR_WEB_ROOT}{$oUserProfile->getProfileFoto()}" alt="photo" />
				{/if}
			</div>			