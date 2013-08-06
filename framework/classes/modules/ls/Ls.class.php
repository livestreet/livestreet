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

/**
 * Модуль Ls
 * Для выполнения служебных действий LiveStreet CMS.
 * В частности для отправки на сервер LiveStreet информации о домене сайта, версии плагинов и LS.
 * Эти данные не разглашаются и используются исключительно в целях развития LiveStreet CMS, оценки спроса, отслеживания интересов аудитории.
 * Так же вы можете благодаря этому получать уведомления о новых версиях установленных плагинов и шаблонов.
 * Вы всегда можете отключить передачу данных в конфиге, но просим этого не далать, тем самым вы поможете развитию LS CMS. Это важно для нас.
 *
 * @package engine.modules
 * @since 1.0
 */
class ModuleLs extends Module {
	/**
	 * Адрес шлюза
	 *
	 * @var string
	 */
	protected $sUrlLs='http://sender.livestreetcms.com/push/';
	/**
	 * Список данных для отправки
	 *
	 * @var array
	 */
	protected $aDataForSend=array();

	/**
	 * Инициализируем модуль
	 *
	 */
	public function Init() {

	}
	/**
	 * Запуск сбора данных
	 *
	 * @return bool
	 */
	public function SenderRun() {
		$this->CheckVerificationKey();
		if (!Config::Get('module.ls.send_general')) {
			return false;
		}
		/**
		 * Вставка счетчика
		 */
		if (Config::Get('module.ls.use_counter')) {
			// лучше вставлять в html_head_end, но здесь нужно постараться вставить код в самом конце, чтобы уменьшить вероятность повторного вызова GA, если сайт его использует
			$this->Hook_AddExecModule('template_body_end','Ls_InjectCounter',-10000);
		}
		/**
		 * Отправка данных
		 */
		$this->SendToLs();
	}
	/**
	 * Проверка ключа, в ответ браузеру выдается только сообщение "ok" или "no"
	 */
	public function CheckVerificationKey() {
		if (Router::GetAction()=='error' and isset($_GET['livestreet_check_verification_key'])) {
			$sKey=trim((string)Config::Get('module.ls.verification_key'));
			if ($sKey and $_GET['livestreet_check_verification_key']===$sKey) {
				echo('ok');
				exit();
			}
			echo('no');
			exit();
		}
	}
	/**
	 * Вставка счетчика GA с учетом его возможного повторного использования
	 *
	 * @return mixed
	 */
	public function InjectCounter() {
		/**
		 * Если _gaq уже определена, значит загружать js код GA не нужно
		 */
		$sCounter="
			<script type=\"text/javascript\">
			var _lsIsLoadGA=(typeof(window._gaq)=='undefined') ? false : true ;

			  var _gaq = _gaq || [];
			  _gaq.push(['lscounter._setAccount', 'UA-28922093-1']);
			  _gaq.push(['lscounter._trackPageview']);

			if (!_lsIsLoadGA) {
			  (function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			}
			</script>
		";

		return $sCounter;
	}
	/**
	 * Отправка данных на шлюз LS
	 */
	protected function SendToLs() {
		/**
		 * Ограничения на запуск отправки, чтобы не нагружать сайт
		 * Отправляем 1 раз в день ночью в промежутке 00:00-07:00, делаем по 20 попыток отправки в день
		 */
		if ((int)date('G')>=7) {
			return;
		}
		if ($aData=$this->GetMarkerFile(date("Y-m-d")) and (isset($aData['is_send']) or (isset($aData['count_try']) and $aData['count_try']>20))) {
			return;
		}

		$this->aDataForSend=$this->GetDataForSendToLs();

		$bOk=false;
		$sResponse=$this->getUrl($this->sUrlLs,$this->aDataForSend);
		if ($sResponse===false) {
			/**
			 * Отправка не удалась, скорее всего нет нужных расширений, пробуем передать данные через клиента инжекцией тега <img/>
			 * Такой способ отправки нужно делать только для админа сайта, чтобы не "засветить" данные третьим лицам.
			 */
			//$this->Hook_AddExecModule('template_body_end','Ls_InjectImgForSendToLs',-2000);
		} else {
			if ($sResponse=='accepted') {
				$bOk=true;
			} else {
				// очень странная ситуация, скорее всего временно не работает сервер
			}
		}
		/**
		 * Отмечаем факт отправки
		 */
		if ($bOk) {
			$this->SuccessfulSendToLs();
		} else {
			$this->ErrorSendToLs();
		}
	}
	/**
	 * Отмечает факт ошибки при отправки данных, увеличиваем число попыток
	 */
	protected function ErrorSendToLs() {
		if (!($aData=$this->GetMarkerFile(date("Y-m-d")))) {
			$aData=array();
		}
		if (isset($aData['count_try'])) {
			$aData['count_try']++;
		} else {
			$aData['count_try']=1;
		}
		$this->SetMarkerFile(date("Y-m-d"),$aData);
	}
	/**
	 * Отмечает факт успешной отправки данных
	 */
	protected function SuccessfulSendToLs() {
		$this->SetMarkerFile(date("Y-m-d"),array('is_send'=>1));
	}
	/**
	 * Читает данные из файла
	 *
	 * @param string $sDate	Дата под которой сохранен файл
	 * @return bool|mixed
	 */
	protected function GetMarkerFile($sDate) {
		$sFile=Config::Get('sys.cache.dir').'lssender-'.$sDate;
		if (!file_exists($sFile)) {
			return false;
		}
		if ($aData=@unserialize(file_get_contents($sFile))) {
			return $aData;
		}
		return false;
	}
	/**
	 * Записывает данные в файл
	 *
	 * @param string $sDate	Дата
	 * @param array $aData	Данные
	 * @return bool
	 */
	protected function SetMarkerFile($sDate,$aData) {
		$sFile=Config::Get('sys.cache.dir').'lssender-'.$sDate;
		if (@file_put_contents($sFile,serialize($aData))) {
			return true;
		}
		return false;
	}
	/**
	 * Возвращает строчку для инжекции в шаблон
	 *
	 * @return string
	 */
	public function InjectImgForSendToLs() {
		$this->SuccessfulSendToLs();
		$sUrl=$this->sUrlLs.'img/?'.$this->makeGetParams($this->aDataForSend);
		return '<img width="1" height="1" src="'.$sUrl.'">';
	}
	/**
	 * Возвращает данные для отправки
	 *
	 * @return array
	 */
	protected function GetDataForSendToLs() {
		/**
		 * Формируем данные для отправки
		 */
		$aData=array();
		$aData['ls_v']=LS_VERSION;
		/**
		 * Список плагинов с версиями
		 */
		$aPlugins=$this->Plugin_GetList();
		foreach($aPlugins as $aPlugin) {
			$aData['plugins']['code'][]=$aPlugin['code'];
			$aData['plugins']['ia'][]=$aPlugin['is_active'];
			$aData['plugins']['v'][]=$aPlugin['property']->version;
		}
		/**
		 * Домен
		 */
		$aData['domain']=Config::Get('path.root.web');
		/**
		 * Шаблон
		 */
		$aData['template']=Config::Get('view.skin');
		/**
		 * Ключ верификации (подтверждения прав на сайт)
		 */
		$aData['key']=(string)Config::Get('module.ls.verification_key');

		return $aData;
	}
	/**
	 * Чтение URL
	 *
	 * @param string $sUrl	Урл
	 * @param array $aParams	параметры
	 * @return bool|string
	 */
	protected function getUrl($sUrl,$aParams) {
		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $sUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$this->makeGetParams($aParams));
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

			$sData = curl_exec($ch);
			if (curl_errno($ch)) {
				curl_close($ch);
				return false;
			}
			curl_close($ch);
			return $sData;
		} else {
			$aUrl=parse_url($sUrl);
			$socket = @fsockopen($aUrl['host'], isset($aUrl['port']) ? $aUrl['port'] : 80, $errno, $errstr, 5);

			if(!$socket) {
				return false;
			}

			//собираем данные
			$data = $this->makeGetParams($aParams);

			fwrite($socket, "POST {$aUrl['path']} HTTP/1.1\r\n");
			fwrite($socket, "Host: {$aUrl['host']}\r\n");
			fwrite($socket,"Content-type: application/x-www-form-urlencoded\r\n");
			fwrite($socket,"Content-length:".strlen($data)."\r\n");
			fwrite($socket,"Accept:*/*\r\n");
			fwrite($socket,"User-agent:Opera 10.00\r\n");
			fwrite($socket,"Connection: Close\r\n");
			fwrite($socket,"\r\n");
			fwrite($socket,"$data\r\n");
			fwrite($socket,"\r\n");

			$sData = '';
			while ( ($line = fgets($socket, 4096)) !== false) {
				$sData.= $line;
			}
			//закрываем сокет
			fclose($socket);
			$sData = trim(substr($sData, strpos($sData, "\r\n\r\n") + 4));
			return $sData;
		}
	}
	/**
	 * Формирует строку GET параметров
	 *
	 * @param array $aParams	Параметры
	 * @return string
	 */
	protected function makeGetParams($aParams=array()) {
		$sGetParams='';
		if (is_string($aParams) or count($aParams)){
			$sGetParams=is_array($aParams) ? http_build_query($aParams,'','&') : $aParams;
		}
		return $sGetParams;
	}
}
?>