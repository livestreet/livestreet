<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

require_once(Config::Get('path.framework.libs_vendor.server').'/phpMailer/class.phpmailer.php');

/**
 * Модуль для отправки почты(e-mail) через phpMailer
 * <pre>
 * $this->Mail_SetAdress('claus@mail.ru','Claus');
 * $this->Mail_SetSubject('Hi!');
 * $this->Mail_SetBody('How are you?');
 * $this->Mail_setHTML();
 * $this->Mail_Send();
 * </pre>
 *
 * @package engine.modules
 * @since 1.0
 */
class ModuleMail extends Module {
	/**
	 * Основной объект рассылбщика
	 *
	 * @var phpmailer
	 */
	protected $oMailer;
	/**
	 * Настройки SMTP сервера для отправки писем
	 *
	 */
	/**
	 * Хост smtp
	 *
	 * @var string
	 */
	protected $sHost;
	/**
	 * Порт smtp
	 *
	 * @var int
	 */
	protected $iPort;
	/**
	 * Логин smtp
	 *
	 * @var string
	 */
	protected $sUsername;
	/**
	 * Пароль smtp
	 *
	 * @var string
	 */
	protected $sPassword;
	/**
	 * Треубется или нет авторизация на smtp
	 *
	 * @var bool
	 */
	protected $bSmtpAuth;
	/**
	 * Префикс соединения к smtp - "", "ssl" или "tls"
	 *
	 * @var string
	 */
	protected $sSmtpSecure;
	/**
	 * Метод отправки почты
	 *
	 * @var string
	 */
	protected $sMailerType;
	/**
	 * Кодировка писем
	 *
	 * @var string
	 */
	protected $sCharSet;
	/**
	 * Делать или нет перенос строк в письме
	 *
	 * @var int
	 */
	protected $iWordWrap=0;

	/**
	 * Мыло от кого отправляется вся почта
	 *
	 * @var string
	 */
	protected $sFrom;
	/**
	 * Имя от кого отправляется вся почта
	 *
	 * @var string
	 */
	protected $sFromName;
	/**
	 * Тема письма
	 *
	 * @var string
	 */
	protected $sSubject='';
	/**
	 * Текст письма
	 *
	 * @var string
	 */
	protected $sBody='';
	/**
	 * Строка последней ошибки
	 * 
	 * @var string
	 */
	protected $sError;
	
	/**
	 * Инициализация модуля
	 *
	 */
	public function Init() {
		/**
		 * Настройки SMTP сервера для отправки писем
		 */
		$this->sHost     = Config::Get('sys.mail.smtp.host');
		$this->iPort     = Config::Get('sys.mail.smtp.port');
		$this->sUsername = Config::Get('sys.mail.smtp.user');
		$this->sPassword = Config::Get('sys.mail.smtp.password');
		$this->bSmtpAuth = Config::Get('sys.mail.smtp.auth');
		$this->sSmtpSecure = Config::Get('sys.mail.smtp.secure');
		/**
		 * Метод отправки почты
		 */
		$this->sMailerType=Config::Get('sys.mail.type');
		/**
		 * Кодировка писем
		 */
		$this->sCharSet=Config::Get('sys.mail.charset');
		/**
		 * Мыло от кого отправляется вся почта
		 */
		$this->sFrom=Config::Get('sys.mail.from_email');
		/**
		 * Имя от кого отправляется вся почта
		 */
		$this->sFromName=Config::Get('sys.mail.from_name');

		/**
		 * Создаём объект phpMailer и устанвливаем ему необходимые настройки
		 */
		$this->oMailer = new phpmailer();
		$this->oMailer->Host=$this->sHost;
		$this->oMailer->Port=$this->iPort;
		$this->oMailer->Username=$this->sUsername;
		$this->oMailer->Password=$this->sPassword;
		$this->oMailer->SMTPAuth=$this->bSmtpAuth;
		$this->oMailer->SMTPSecure=$this->sSmtpSecure;
		$this->oMailer->Mailer=$this->sMailerType;
		$this->oMailer->WordWrap=$this->iWordWrap;
		$this->oMailer->CharSet=$this->sCharSet;

		$this->oMailer->From=$this->sFrom;
		$this->oMailer->Sender=$this->sFrom;
		$this->oMailer->FromName=$this->sFromName;
	}
	/**
	 * Устанавливает тему сообщения
	 *
	 * @param string $sText	Тема сообщения
	 */
	public function SetSubject($sText) {
		$this->sSubject=$sText;
	}
	/**
	 * Устанавливает текст сообщения
	 *
	 * @param string $sText	Текст сообщения
	 */
	public function SetBody($sText) {
		$this->sBody=$sText;
	}
	/**
	 * Добавляем новый адрес получателя
	 *
	 * @param string $sMail	Емайл
	 * @param string $sName	Имя
	 */
	public function AddAdress($sMail,$sName=null) {
		ob_start();
		$this->oMailer->AddAddress($sMail,$sName);
		$this->sError = ob_get_clean();
	}
	/**
	 * Добавляем прикрепляемый файл
	 *
	 * @param string $sPath	Абсолютный путь к файлу
	 * @param string $sName	Свое имя файла
	 * @param string $sEncoding Кодированик файла
	 * @param string $sType	Расширение файла (MIME).
	 */
	public function AddAttachment($sPath,$sName='',$sEncoding='base64',$sType='application/octet-stream') {
		ob_start();
		$this->oMailer->AddAttachment($sPath,$sName,$sEncoding,$sType);
		$this->sError = ob_get_clean();
	}
	/**
	 * Отправляет сообщение(мыло)
	 *
	 * @return bool
	 */
	public function Send() {
		$this->oMailer->Subject=$this->sSubject;
		$this->oMailer->Body=$this->sBody;
		ob_start();
		$bResult = $this->oMailer->Send();
		$this->sError = ob_get_clean();
		return $bResult;
	}
	/**
	 * Очищает все адреса получателей
	 *
	 */
	public function ClearAddresses() {
		$this->oMailer->ClearAddresses();
	}
	/**
	 * Устанавливает единственный адрес получателя
	 *
	 * @param string $sMail	Емайл
	 * @param string $sName	Имя
	 */
	public function SetAdress($sMail,$sName=null) {
		$this->ClearAddresses();
		ob_start();
		$this->oMailer->AddAddress($sMail,$sName);
		$this->sError = ob_get_clean();
	}
	/**
	 * Устанавливает режим отправки письма как HTML
	 *
	 */
	public function setHTML() {
		$this->oMailer->IsHTML(true);
	}
	/**
	 * Устанавливает режим отправки письма как Text(Plain)
	 *
	 */
	public function setPlain() {
		$this->oMailer->IsHTML(false);
	}
	
	/**
	 * Возвращает строку последней ошибки
	 * 
	 * @return string
	 */
	public function GetError(){
		return $this->sError;
	}
}
?>
