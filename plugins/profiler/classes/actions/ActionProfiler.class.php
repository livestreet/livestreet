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
 * Обрабатывает вывод отчетов профилирования
 *
 */
class PluginProfiler_ActionProfiler extends ActionPlugin {
	/**
	 * Текущий юзер
	 *
	 * @var ModuleUser_EntityUser
	 */
	protected $oUserCurrent=null;
		
	/**
	 * Инициализация 
	 *
	 * @return null
	 */
	public function Init() {	
		/**
		 * Проверяем авторизован ли юзер
		 */
		if (!$this->User_IsAuthorization()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'));
			return Router::Action('error'); 
		}
		/**
		 * Получаем текущего юзера
		 */
		$this->oUserCurrent=$this->User_GetUserCurrent();
		/**
		 * Проверяем является ли юзер администратором
		 */
		if (!$this->oUserCurrent->isAdministrator()) {
			$this->Message_AddErrorSingle($this->Lang_Get('not_access'));
			return Router::Action('error');
		}
		
		$this->SetDefaultEvent('report');
	}
	
	protected function RegisterEvent() {		
		$this->AddEvent('report','EventReport');
		$this->AddEvent('ajaxloadreport','EventAjaxLoadReport');
		$this->AddEvent('ajaxloadentriesbyfilter','EventAjaxLoadEntriesByFilter');
	}
		
	
	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */
	
	protected function EventReport() {				
		/**
		 * Обработка удаления отчетов профайлера
		 */
		if (isPost('submit_report_delete')) {
			$this->Security_ValidateSendForm();
			
			$aReportsId=getRequest('report_del');
			if (is_array($aReportsId)) {
				if($this->PluginProfiler_Profiler_DeleteEntryByRequestId(array_keys($aReportsId))) {
					$this->Message_AddNotice($this->Lang_Get('profiler_report_delete_success'), $this->Lang_Get('attention'));
				} else {
					$this->Message_AddError($this->Lang_Get('profiler_report_delete_error'), $this->Lang_Get('error'));
				}
			}
		}
		
		/**
		 * Если вызвана обработка upload`а логов в базу данных
		 */
		if(getRequest('submit_profiler_import') and getRequest('profiler_date_import')) {			
			$iCount = @$this->PluginProfiler_Profiler_UploadLog(date('Y-m-d H:i:s',strtotime(getRequest('profiler_date_import'))));
			if(!is_null($iCount)) {
				$this->Message_AddNotice($this->Lang_Get('profiler_import_report_success',array('count'=>$iCount)), $this->Lang_Get('attention'));
			} else {
				$this->Message_AddError($this->Lang_Get('profiler_import_report_error'), $this->Lang_Get('error'));
			}
		}
		
		/**
		 * Составляем фильтр для просмотра отчетов
		 */
		$aFilter=$this->BuildFilter();
		
		/**
		 * Передан ли номер страницы
		 */
		$iPage=preg_match("/^page(\d+)$/i",$this->getParam(0),$aMatch) ? $aMatch[1] : 1;				
		/**
		 * Получаем список отчетов
		 */		
		$aResult=$this->PluginProfiler_Profiler_GetReportsByFilter($aFilter,$iPage,Config::Get('plugin.profiler.per_page'));		
		$aReports=$aResult['collection'];
		/**
		 * Если был использован фильтр, выводим количество найденых по фильтру
		 */
		if(count($aFilter)) {
			$this->Message_AddNotice(
				($aResult['count'])
					? $this->Lang_Get('profiler_filter_result_count',array('count'=>$aResult['count']))
					: $this->Lang_Get('profiler_filter_result_empty')
			);
		}
		/**
		 * Формируем постраничность
		 */
		$aPaging=$this->Viewer_MakePaging(
			$aResult['count'],$iPage,Config::Get('plugin.profiler.per_page'),4,
			Router::GetPath('profiler').$this->sCurrentEvent,
			array_intersect_key(
				$_REQUEST,
				array_fill_keys(array('start','end','request_id','time','per_page'), '')
			)
		);
		
		/**
		 * Загружаем переменные в шаблон
		 */
		$this->Viewer_Assign('aPaging',$aPaging);
		$this->Viewer_Assign('aReports',$aReports);
		$this->Viewer_Assign('aDatabaseStat',($aData=$this->PluginProfiler_Profiler_GetDatabaseStat())?$aData:array('max_date'=>'','count'=>''));		
		$this->Viewer_AddBlock('right',$this->getTemplatePathPlugin().'/actions/ActionProfiler/sidebar.tpl');
		$this->Viewer_AddHtmlTitle($this->Lang_Get('profiler_report_page_title'));
	}
	
	/**
	 * Формирует из REQUEST массива фильтр для отбора отчетов
	 *
	 * @return array
	 */
	protected function BuildFilter() {
		$aFilter = array();
		
		if($start=getRequest('start')) {
			if(func_check($start,'text',6,10) && substr_count($start,'.')==2) {
				list($d,$m,$y)=explode('.',$start);
				if(@checkdate($m,$d,$y)) {
					$aFilter['date_min']="{$y}-{$m}-{$d}";
				} else {
					$this->Message_AddError(
						$this->Lang_Get('profiler_filter_error_date_format'), 
						$this->Lang_Get('profiler_filter_error')
					);
					unset($_REQUEST['start']);				
				}
			} else {
				$this->Message_AddError(
					$this->Lang_Get('profiler_filter_error_date_format'), 
					$this->Lang_Get('profiler_filter_error')
				);
				unset($_REQUEST['start']);				
			}			
		}
		
		if($end=getRequest('end')) {
			if(func_check($end,'text',6,10) && substr_count($end,'.')==2) {
				list($d,$m,$y)=explode('.',$end);
				if(@checkdate($m,$d,$y)) { 
					$aFilter['date_max']="{$y}-{$m}-{$d} 23:59:59";
				} else {
					$this->Message_AddError(
						$this->Lang_Get('profiler_filter_error_date_format'), 
						$this->Lang_Get('profiler_filter_error')
					);
					unset($_REQUEST['end']);
				}
			} else {
				$this->Message_AddError(
					$this->Lang_Get('profiler_filter_error_date_format'), 
					$this->Lang_Get('profiler_filter_error')
				);
				unset($_REQUEST['end']);				
			}
		}
		
		if($iTimeFull=getRequest('time') and $iTimeFull>0) {
			$aFilter['time']=$iTimeFull;
		}
		
		if($iPerPage=getRequest('per_page',0) and $iPerPage>0) {
			Config::Set('plugins.profiler.per_page',$iPerPage);
		}
		return $aFilter;
	}
	
	/**
	 * Подгрузка данных одного профиля по ajax-запросу
	 *
	 * @return 
	 */
	protected function EventAjaxLoadReport() {
		$this->Viewer_SetResponseAjax();
		
		$sReportId=str_replace('report_','',getRequest('reportId',null,'post'));
		$bTreeView=getRequest('bTreeView',false,'post');
		$sParentId=getRequest('parentId',null,'post');
		
		$oViewerLocal=$this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('oReport',$this->PluginProfiler_Profiler_GetReportById($sReportId,$sParentId));
		if(!$sParentId) $oViewerLocal->Assign('sAction','tree');
		
		$sTemplateName = ($bTreeView)
			? (($sParentId) 
				? 'level' 
				: 'tree')
			:'report';
		$this->Viewer_AssignAjax('sReportText',$oViewerLocal->Fetch($this->getTemplatePathPlugin()."/actions/ActionProfiler/ajax/{$sTemplateName}.tpl", 'profiler'));
	}

	/**
	 * Подгрузка данных одного профиля по ajax-запросу
	 *
	 * @return 
	 */	
	protected function EventAjaxLoadEntriesByFilter() {
		$this->Viewer_SetResponseAjax();
		
		$sAction = $this->GetParam(0);
		$sReportId=str_replace('report_','',getRequest('reportId',null,'post'));

		$oViewerLocal=$this->Viewer_GetLocalViewer();
		$oViewerLocal->Assign('sAction',$sAction);
		
		$oReport = $this->PluginProfiler_Profiler_GetReportById($sReportId,($sAction=='tree')?0:null);
		
		/**
		 * Преобразуем report взависимости от выбранного фильтра
		 */
		switch ($sAction) {
			case 'query':
				$oReport->setAllEntries($oReport->getEntriesByName('query'));
				break;
		}
		$oViewerLocal->Assign('oReport',$oReport);
		
		$sTemplateName=($sAction=='tree')?'tree':'report';
		$this->Viewer_AssignAjax('sReportText',$oViewerLocal->Fetch($this->getTemplatePathPlugin()."/actions/ActionProfiler/ajax/{$sTemplateName}.tpl", "profiler"));
	}
	
	/**
	 * Завершение работы Action`a
	 *
	 */
	public function EventShutdown() {

	}
}
?>