			{if $oUserCurrent}
			<div class="block actions white">
				<div class="tl"><div class="tr"></div></div>

				<div class="cl"><div class="cr">					
					<ul>
						<li class="add"><a href="#">Добавить в друзья</a></li>
						<li><a href="#">Написать сообщение</a></li>						
					</ul>
				</div></div>

				<div class="bl"><div class="br"></div></div>
			</div>
			{/if}
			
			<div class="block contacts nostyle">
				<strong>Контакты и социальные сервисы</strong>
				<ul>
					{if $oUserProfile->getProfileIcq()}
						<li class="icq"><a href="http://wwp.icq.com/scripts/contact.dll?msgto={$oUserProfile->getProfileIcq()|escape:'html'}" target="_blank">{$oUserProfile->getProfileIcq()}</a></li>
					{/if}
					
					<li class="jabber"><a href="#">user@jabber.org</a></li>

					<li class="skype"><a href="#">user</a></li>
					<li class="lj"><a href="#">user.livejournal.com</a></li>
					<li class="vk"><a href="#">Вконтакте</a></li>
					
					<li class="phone">+7 888 999 0000</li>
				</ul>

				<img src="{$DIR_STATIC_SKIN}/images/photo.jpg" alt="photo" />
			</div>			