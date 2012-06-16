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
 * Обработка главной страницы, т.е. УРЛа вида /index/
 *
 * @package actions
 * @since 1.0
 */
class ActionIndex extends Action {
	/**
	 * Главное меню
	 *
	 * @var string
	 */
	protected $sMenuHeadItemSelect='blog';
	/**
	 * Меню
	 *
	 * @var string
	 */
	protected $sMenuItemSelect='index';
	/**
	 * Субменю
	 *
	 * @var string
	 */
	protected $sMenuSubItemSelect='good';
	/**
	 * Число новых топиков
	 *
	 * @var int
	 */
	protected $iCountTopicsNew=0;
	/**
	 * Число новых топиков в коллективных блогах
	 *
	 * @var int
	 */
	protected $iCountTopicsCollectiveNew=0;
	/**
	 * Число новых топиков в персональных блогах
	 *
	 * @var int
	 */
	protected $iCountTopicsPersonalNew=0;

	/**
	 * Инициализация
	 *
	 */
	public function Init() {
		/**
		 * Подсчитываем новые топики
		 */
		$this->iCountTopicsCollectiveNew=$this->Topic_GetCountTopicsCollectiveNew();
		$this->iCountTopicsPersonalNew=$this->Topic_GetCountTopicsPersonalNew();
		$this->iCountTopicsNew=$this->iCountTopicsCollectiveNew+$this->iCountTopicsPersonalNew;
	}
	/**
	 * Регистрация евентов
	 *
	 */
	protected function RegisterEvent() {
		$this->AddEventPreg('/^(page(\d+))?$/i','EventIndex');
		$this->AddEventPreg('/^new$/i','/^(page(\d+))?$/i','EventNew');
		$this->AddEventPreg('/^newall$/i','/^(page(\d+))?$/i','EventNewAll');
		$this->AddEventPreg('/^discussed/i','/^(page(\d+))?$/i','EventDiscussed');
		$this->AddEventPreg('/^top/i','/^(page(\d+))?$/i','EventTop');
	}


	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	/**
	 * Вывод рейтинговых топиков
	 */
	protected function EventTop() {
		$sPeriod=1; // по дефолту 1 день
		if (in_array(getRequest('period'),array(1,7,30,'all'))) {
			$sPeriod=getRequest('period');
		}
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='top';
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;
		if ($iPage==1 and !getRequest('period')) {
			$this->Viewer_SetHtmlCanonical(Router::GetPath('index').'top/');
		}
		/**
		 * Получаем список топиков
		 */
		$aResult=$this->Topic_GetTopicsTop($iPage,Config::Get('module.topic.per_page'),$sPeriod=='all' ? null : $sPeriod*60*60*24);
		/**
		 * Если нет топиков за 1 день, то показываем за неделю (7)
		 */
		if (!$aResult['count'] and $iPage==1 and !getRequest('period')) {
			$sPeriod=7;
			$aResult=$this->Topic_GetTopicsTop($iPage,Config::Get('module.topic.per_page'),$sPeriod=='all' ? null : $sPeriod*60*60*24);
		}
		$aTopics=$aResult['collection'];
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('index').'top',array('period'=>$sPeriod));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('sPeriodSelectCurrent',$sPeriod);
		$this->Viewer_Assign('sPeriodSelectRoot',Router::GetPath('index').'top/');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	/**
	 * Вывод обсуждаемых топиков
	 */
	protected function EventDiscussed() {
		$sPeriod=1; // по дефолту 1 день
		if (in_array(getRequest('period'),array(1,7,30,'all'))) {
			$sPeriod=getRequest('period');
		}
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='discussed';
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;
		if ($iPage==1 and !getRequest('period')) {
			$this->Viewer_SetHtmlCanonical(Router::GetPath('index').'discussed/');
		}
		/**
		 * Получаем список топиков
		 */
		$aResult=$this->Topic_GetTopicsDiscussed($iPage,Config::Get('module.topic.per_page'),$sPeriod=='all' ? null : $sPeriod*60*60*24);
		/**
		 * Если нет топиков за 1 день, то показываем за неделю (7)
		 */
		if (!$aResult['count'] and $iPage==1 and !getRequest('period')) {
			$sPeriod=7;
			$aResult=$this->Topic_GetTopicsDiscussed($iPage,Config::Get('module.topic.per_page'),$sPeriod=='all' ? null : $sPeriod*60*60*24);
		}
		$aTopics=$aResult['collection'];
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('index').'discussed',array('period'=>$sPeriod));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('sPeriodSelectCurrent',$sPeriod);
		$this->Viewer_Assign('sPeriodSelectRoot',Router::GetPath('index').'discussed/');
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	/**
	 * Вывод новых топиков
	 */
	protected function EventNew() {
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'new/',Config::Get('view.name'));
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='new';
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;
		/**
		 * Получаем список топиков
		 */
		$aResult=$this->Topic_GetTopicsNew($iPage,Config::Get('module.topic.per_page'));
		$aTopics=$aResult['collection'];
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('index').'new');
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	/**
	 * Вывод ВСЕХ новых топиков
	 */
	protected function EventNewAll() {
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'new/',Config::Get('view.name'));
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='new';
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetParamEventMatch(0,2) ? $this->GetParamEventMatch(0,2) : 1;
		/**
		 * Получаем список топиков
		 */
		$aResult=$this->Topic_GetTopicsNewAll($iPage,Config::Get('module.topic.per_page'));
		$aTopics=$aResult['collection'];
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('index').'newall');
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	/**
	 * Вывод интересных на главную
	 *
	 */
	protected function EventIndex() {
		$this->Viewer_SetHtmlRssAlternate(Router::GetPath('rss').'index/',Config::Get('view.name'));
		/**
		 * Меню
		 */
		$this->sMenuSubItemSelect='good';
		/**
		 * Передан ли номер страницы
		 */
		$iPage=$this->GetEventMatch(2) ? $this->GetEventMatch(2) : 1;
		/**
		 * Устанавливаем основной URL для поисковиков
		 */
		if ($iPage==1) {
			$this->Viewer_SetHtmlCanonical(Config::Get('path.root.web').'/');
		}
		/**
		 * Получаем список топиков
		 */
		$aResult=$this->Topic_GetTopicsGood($iPage,Config::Get('module.topic.per_page'));
		$aTopics=$aResult['collection'];
		/**
		 * Вызов хуков
		 */
		$this->Hook_Run('topics_list_show',array('aTopics'=>$aTopics));
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging($aResult['count'],$iPage,Config::Get('module.topic.per_page'),Config::Get('pagination.pages.count'),Router::GetPath('index'));
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aTopics',$aTopics);
		$this->Viewer_Assign('aPaging',$aPaging);
		/**
		 * Устанавливаем шаблон вывода
		 */
		$this->SetTemplateAction('index');
	}
	/**
	 * При завершении экшена загружаем переменные в шаблон
	 *
	 */
	public function EventShutdown() {
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuSubItemSelect',$this->sMenuSubItemSelect);
		$this->Viewer_Assign('iCountTopicsNew',$this->iCountTopicsNew);
		$this->Viewer_Assign('iCountTopicsCollectiveNew',$this->iCountTopicsCollectiveNew);
		$this->Viewer_Assign('iCountTopicsPersonalNew',$this->iCountTopicsPersonalNew);
	}
}
?>