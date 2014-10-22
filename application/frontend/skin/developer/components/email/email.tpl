{**
 * Базовый шаблона e-mail'а
 *}

{$backgroundColor = 'F4F4F4'}           {* Цвет фона *}

{$containerBorderColor = 'D0D6E8'}      {* Цвет границ основного контейнера *}

{$headerBackgroundColor = '5C7DC4'}     {* Цвет фона шапки *}
{$headerTitleColor = 'FFFFFF'}          {* Цвет заголовка в шапке *}
{$headerDescriptionColor = 'B8C5E1'}    {* Цвет описания в шапке *}

{$contentBackgroundColor = 'FFFFFF'}    {* Цвет фона содержимого письма *}
{$contentTitleColor = '000000'}         {* Цвет заголовка *}
{$contentTextColor = '4f4f4f'}          {* Цвет текста *}

{$footerBackgroundColor = 'fafafa'}     {* Цвет фона футера *}
{$footerTextColor = '949fa3'}           {* Цвет текста в футере *}
{$footerLinkColor = '949fa3'}           {* Цвет ссылки в футере *}

{* Путь до папки с изображенями *}
{$imagesDir = "{Config::Get('path.skin.web')}/components/email/images"}


{* Фон *}
<table width="100%" align="center" bgcolor="#{$backgroundColor}" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
	<tr><td>
		<br />
		<br />

		{* Основной контейнер *}
		<table width="573" align="center" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font: normal 13px/1.4em Verdana, Arial; color: #4f4f4f; border: 1px solid #{$containerBorderColor};">
			{* Шапка *}
			<tr>
				<td>
					<table width="100%" bgcolor="#{$headerBackgroundColor}" cellpadding="50" cellspacing="0" style="border-collapse: collapse;">
						<tr>
							<td style="font-size: 11px; line-height: 1em;">
								<span style="font: normal 29px Arial, sans-serif; line-height: 1em; color: #{$headerTitleColor}"><strong>{Config::Get('view.name')}</strong></span>
								<div style="line-height: 0; height: 10px;"><img src="{$imagesDir}/blank.gif" width="10" height="10"/></div>
								<span style="color: #{$headerDescriptionColor}">{Config::Get('view.description')}</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			{* Контент *}
			<tr>
				<td>
					<table width="100%" cellpadding="50" cellspacing="0" bgcolor="#{$contentBackgroundColor}" style="border-collapse: collapse;">
						<tr>
							<td valign="top">
								<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font: normal 13px/1.4em Verdana, Arial; color: #{$contentTextColor};">
									{* Заголовок *}
									{if $sTitle}
										<tr>
											<td valign="top">
												<span style="font: normal 19px Arial; line-height: 1.3em; color: #{$contentTitleColor}">{$title}</span>
											</td>
										</tr>
										<tr><td height="10"><div style="line-height: 0;"><img src="{$imagesDir}/blank.gif" width="15" height="15"/></div></td></tr>
									{/if}

									{* Текст *}
									<tr>
										<td valign="top">
											{block 'content'}{/block}
											<br>
											<br>
											{$aLang.emails.common.regards} <a href="{Router::GetPath('/')}">{Config::Get('view.name')}</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>

					{* Подвал *}
					<table width="100%" bgcolor="#{$footerBackgroundColor}" cellpadding="20" cellspacing="0" style="border-collapse: collapse; font: normal 11px Verdana, Arial; line-height: 1.3em; color: #{$footerTextColor};">
						<tr>
							<td valign="center">
								<img src="{$imagesDir}/blank.gif" width="27" height="10" style="vertical-align: middle">
								<a href="{Router::GetPath('/')}" style="color: #{$footerLinkColor} !important;">{Config::Get('view.name')}</a>
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