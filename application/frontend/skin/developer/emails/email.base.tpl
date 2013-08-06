{**
 * Базовый шаблона e-mail'а
 *}

{$sBackgroundColor = 'F4F4F4'}           {* Цвет фона *}

{$sContainerBorderColor = 'D0D6E8'}      {* Цвет границ основного контейнера *}

{$sHeaderBackgroundColor = '5C7DC4'}     {* Цвет фона шапки *}
{$sHeaderTitleColor = 'FFFFFF'}          {* Цвет заголовка в шапке *}
{$sHeaderDescriptionColor = 'B8C5E1'}    {* Цвет описания в шапке *}

{$sContentBackgroundColor = 'FFFFFF'}    {* Цвет фона содержимого письма *}
{$sContentTitleColor = '000000'}         {* Цвет заголовка *}
{$sContentTextColor = '4f4f4f'}          {* Цвет текста *}

{$sFooterBackgroundColor = 'fafafa'}     {* Цвет фона футера *}
{$sFooterTextColor = '949fa3'}           {* Цвет текста в футере *}
{$sFooterLinkColor = '949fa3'}           {* Цвет ссылки в футере *}

{* Путь до папки с изображенями *}
{$sImagesDir = "{cfg name='path.static.assets'}/images/emails"}


{* Фон *}
<table width="100%" align="center" bgcolor="#{$sBackgroundColor}" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr><td>
		<br />
		<br />

		{* Основной контейнер *}
		<table width="573" align="center" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font: normal 13px/1.4em Verdana, Arial; color: #4f4f4f; border: 1px solid #{$sContainerBorderColor};">
			{* Шапка *}
			<tr>
				<td>
					<table width="100%" bgcolor="#{$sHeaderBackgroundColor}" cellpadding="50" cellspacing="0" style="border-collapse: collapse;">
						<tr>
							<td style="font-size: 11px; line-height: 1em;">	
								<span style="font: normal 29px Arial, sans-serif; line-height: 1em; color: #{$sHeaderTitleColor}"><strong>{cfg name='view.name'}</strong></span>
								<div style="line-height: 0; height: 10px;"><img src="{$sImagesDir}/blank.gif" width="10" height="10"/></div>
								<span style="color: #{$sHeaderDescriptionColor}">{cfg name='view.description'}</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			{* Контент *}
			<tr>
				<td>
					<table width="100%" cellpadding="50" cellspacing="0" bgcolor="#{$sContentBackgroundColor}" style="border-collapse: collapse;">
						<tr>
							<td valign="top">
								<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font: normal 13px/1.4em Verdana, Arial; color: #{$sContentTextColor};">
									{* Заголовок *}
									{if $sTitle}
										<tr>
											<td valign="top">
												<span style="font: normal 19px Arial; line-height: 1.3em; color: #{$sContentTitleColor}">{$sTitle}</span>
											</td>
										</tr>
										<tr><td height="10"><div style="line-height: 0;"><img src="{$sImagesDir}/blank.gif" width="15" height="15"/></div></td></tr>
									{/if}

									{* Текст *}
									<tr>
										<td valign="top">
											{block name='content'}{/block}
											<br>
											<br>
											{$aLang.notify_regards} <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					{* Подвал *}
					<table width="100%" bgcolor="#{$sFooterBackgroundColor}" cellpadding="20" cellspacing="0" style="border-collapse: collapse; font: normal 11px Verdana, Arial; line-height: 1.3em; color: #{$sFooterTextColor};">
						<tr>
							<td valign="center">
								<img src="{$sImagesDir}/blank.gif" width="27" height="10" style="vertical-align: middle">
								<a href="{cfg name='path.root.web'}" style="color: #{$sFooterLinkColor} !important;">{cfg name='view.name'}</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<br />
		<br />
	</td></tr>
</table>