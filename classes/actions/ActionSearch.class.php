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
 * Экшен обработки поиска по сайту через поисковый движок Sphinx
 *
 * @package actions
 * @since 1.0
 */
class ActionSearch extends Action {
	/**
	 * Допустимые типы поиска с параметрами
	 *
	 * @var array
	 */
	protected $sTypesEnabled = array('topics' => array('topic_publish' => 1), 'comments' => array('comment_delete' => 0));
	/**
	 * Массив результата от Сфинкса
	 *
	 * @var null|array
	 */
	protected $aSphinxRes = null;
	/**
	 * Поиск вернул результат или нет
	 *
	 * @var bool
	 */
	protected $bIsResults = FALSE;

	/**
	 * Инициализация
	 */
	public function Init() {
		$this->SetDefaultEvent('index');
		$this->Viewer_AddHtmlTitle($this->Lang_Get('search'));
	}
	/**
	 * Регистрация евентов
	 */
	protected function RegisterEvent() {
		$this->AddEvent('index','EventIndex');
		$this->AddEvent('topics','EventTopics');
		$this->AddEvent('comments','EventComments');
		$this->AddEvent('opensearch','EventOpenSearch');
	}
	/**
	 * Отображение формы поиска
	 */
	function EventIndex(){
	}
	/**
	 * Обработка стандарта для браузеров Open Search
	 */
	function EventOpenSearch(){
		Router::SetIsShowStats(false);
		$this->Viewer_Assign('sAdminMail', Config::Get('sys.mail.from_email'));
	}
	/**
	 * Поиск топиков
	 *
	 */
	function EventTopics(){
		/**
		 * Ищем
		 */
		$aReq = $this->PrepareRequest();
		$aRes = $this->PrepareResults($aReq, Config::Get('module.topic.per_page'));
		if(FALSE === $aRes) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}
		/**
		 * Если поиск дал результаты
		 */
		if($this->bIsResults){
			/**
			 * Получаем топик-объекты по списку идентификаторов
			 */
			$aTopics = $this->Topic_GetTopicsAdditionalData(array_keys($this->aSphinxRes['matches']));
			/**
			 * Конфигурируем парсер jevix
			 */
			$this->Text_LoadJevixConfig('search');
			/**
			 *  Делаем сниппеты
			 */
			foreach($aTopics AS $oTopic){
				/**
				 * Т.к. текст в сниппетах небольшой, то можно прогнать через парсер
				 */
				$oTopic->setTextShort($this->Text_JevixParser($this->Sphinx_GetSnippet(
																  $oTopic->getText(),
																  'topics',
																  $aReq['q'],
																  '<span class="searched-item">',
																  '</span>'
															  )));
			}
			/**
			 *  Отправляем данные в шаблон
			 */
			$this->Viewer_Assign('bIsResults', TRUE);
			$this->Viewer_Assign('aRes', $aRes);
			$this->Viewer_Assign('aTopics', $aTopics);
		}
	}
	/**
	 * Поиск комментариев
	 *
	 */
	function EventComments(){
		/**
		 * Ищем
		 */
		$aReq = $this->PrepareRequest();
		$aRes = $this->PrepareResults($aReq, Config::Get('module.comment.per_page'));
		if(FALSE === $aRes) {
			$this->Message_AddErrorSingle($this->Lang_Get('system_error'));
			return Router::Action('error');
		}
		/**
		 * Если поиск дал результаты
		 */
		if($this->bIsResults){
			/**
			 *  Получаем топик-объекты по списку идентификаторов
			 */
			$aComments = $this->Comment_GetCommentsAdditionalData(array_keys($this->aSphinxRes['matches']));
			/**
			 * Конфигурируем парсер jevix
			 */
			$this->Text_LoadJevixConfig('search');
			/**
			 * Делаем сниппеты
			 */
			foreach($aComments AS $oComment){
				$oComment->setText($this->Text_JevixParser($this->Sphinx_GetSnippet(
															   htmlspecialchars($oComment->getText()),
															   'comments',
															   $aReq['q'],
															   '<span class="searched-item">',
															   '</span>'
														   )));
			}
			/**
			 *  Отправляем данные в шаблон
			 */
			$this->Viewer_Assign('aRes', $aRes);
			$this->Viewer_Assign('aComments', $aComments);
		}
	}
	/**
	 * Подготовка запроса на поиск
	 *
	 * @return array
	 */
	private function PrepareRequest(){
		$aReq['q'] = getRequest('q');
		if (!func_check($aReq['q'],'text', 2, 255)) {
			/**
			 * Если запрос слишком короткий перенаправляем на начальную страницу поиска
			 * Хотя тут лучше показывать юзеру в чем он виноват
			 */
			Router::Location(Router::GetPath('search'));
		}
		$aReq['sType'] = strtolower(Router::GetActionEvent());
		/**
		 * Определяем текущую страницу вывода результата
		 */
		$aReq['iPage'] = intval(preg_replace('#^page(\d+)$#', '\1', $this->getParam(0)));
		if(!$aReq['iPage']) { $aReq['iPage'] = 1; }
		/**
		 *  Передача данных в шаблонизатор
		 */
		$this->Viewer_Assign('aReq', $aReq);
		return $aReq;
	}
	/**
	 * Поиск и формирование результата
	 *
	 * @param array $aReq
	 * @param int $iLimit
	 * @return array|bool
	 */
	protected function PrepareResults($aReq, $iLimit){
		/**
		 *  Количество результатов по типам
		 */
		foreach($this->sTypesEnabled as $sType => $aExtra){
			$aRes['aCounts'][$sType] = intval($this->Sphinx_GetNumResultsByType($aReq['q'], $sType, $aExtra));
		}
		if($aRes['aCounts'][$aReq['sType']] == 0){
			/**
			 *  Объектов необходимого типа не найдено
			 */
			unset($this->sTypesEnabled[$aReq['sType']]);
			/**
			 * Проверяем отсальные типы
			 */
			foreach(array_keys($this->sTypesEnabled) as $sType){
				if($aRes['aCounts'][$sType])
					Router::Location(Router::GetPath('search').$sType.'/?q='.$aReq['q']);
			}
		} elseif(($aReq['iPage']-1)*$iLimit <= $aRes['aCounts'][$aReq['sType']]) {
			/**
			 * Ищем
			 */
			$this->aSphinxRes = $this->Sphinx_FindContent(
				$aReq['q'],
				$aReq['sType'],
				($aReq['iPage']-1)*$iLimit,
				$iLimit,
				$this->sTypesEnabled[$aReq['sType']]
			);
			/**
			 * Возможно демон Сфинкса не доступен
			 */
			if (FALSE === $this->aSphinxRes) {
				return FALSE;
			}

			$this->bIsResults = TRUE;
			/**
			 * Формируем постраничный вывод
			 */
			$aPaging = $this->Viewer_MakePaging(
				$aRes['aCounts'][$aReq['sType']],
				$aReq['iPage'],
				$iLimit,
				Config::Get('pagination.pages.count'),
				Router::GetPath('search').$aReq['sType'],
				array(
					'q' => $aReq['q']
				)
			);
			$this->Viewer_Assign('aPaging', $aPaging);
		}

		$this->SetTemplateAction('results');
		$this->Viewer_AddHtmlTitle($aReq['q']);
		$this->Viewer_Assign('bIsResults', $this->bIsResults);
		return $aRes;
	}
}
?>