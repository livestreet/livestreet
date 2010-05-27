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

require_once(Config::Get('path.root.engine').'/lib/external/phpMailer/class.phpmailer.php');

/**
 * Модуль для отправки почты(e-mail) через phpMailer
 *
 */
class ModuleMail extends Module {
	protected $oMailer;		
	/**
	 * Настройки SMTP сервера для отправки писем	
	 * 
	 */
	protected $sHost;
	protected $iPort;
	protected $sUsername;
	protected $sPassword;
	protected $bSmtpAuth;
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
	protected $sSubject='';
	protected $sBody='';
	
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
		$this->oMailer->Mailer=$this->sMailerType;
		$this->oMailer->WordWrap=$this->iWordWrap;
		$this->oMailer->CharSet=$this->sCharSet;
		
		$this->oMailer->From=$this->sFrom;
		$this->oMailer->FromName=$this->sFromName;			
	}
	
	/**
	 * Устанавливает тему сообщения
	 *
	 * @param string $sText
	 */
	public function SetSubject($sText) {
		$this->sSubject=$sText;
	}
	
	/**
	 * Устанавливает текст сообщения
	 *
	 * @param string $sText
	 */
	public function SetBody($sText) {
		$this->sBody=$sText;
	}
	
	/**
	 * Добавляем новый адрес получателя
	 *
	 * @param string $sMail
	 * @param string $sName
	 */
	public function AddAdress($sMail,$sName=null) {
		$this->oMailer->AddAddress($sMail,$sName);
	}
	
	/**
	 * Отправляет сообщение(мыло)
	 *
	 * @return unknown
	 */
	public function Send() {
		$this->oMailer->Subject=$this->sSubject;
		$this->oMailer->Body=$this->sBody;
		return $this->oMailer->Send();
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
	 * @param string $sMail
	 * @param string $sName
	 */
	public function SetAdress($sMail,$sName=null) {
		$this->ClearAddresses();
		$this->oMailer->AddAddress($sMail,$sName);
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
}
?>