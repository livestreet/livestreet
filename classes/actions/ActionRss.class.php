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
 * Обрабатывает RSS
 * Автор класса vovazol(http://livestreet.ru/profile/vovazol/)
 *
 */
class ActionRss extends Action {

	public function Init() {		
		$this->SetDefaultEvent('index');
		Router::SetIsShowStats(false);
	}

	/**
	 * Указывает браузеру правильный content type в случае вывода RSS-ленты
	 */
	protected function InitRss() {
		header('Content-Type: application/rss+xml; charset=utf-8');	
	}
	
	protected function RegisterEvent() {
		$this->AddEvent('index','RssGood');
		$this->AddEvent('new','RssNew');
		$this->AddEvent('allcomments','RssComments');
		$this->AddEvent('comments','RssTopicComments');
		$this->AddEvent('tag','RssTag');
		$this->AddEvent('blog','RssColectiveBlog');
		$this->AddEvent('personal_blog','RssPersonalBlog');
	}

	protected function RssGood() {
		$aResult=$this->Topic_GetTopicsGood(1,Config::Get('module.topic.per_page')*2,false);
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=Config::Get('view.name');
		$aChannel['link']=Config::Get('path.root.web');
		$aChannel['description']=Config::Get('view.name').' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=Config::Get('general.rss_editor_mail');
		$aChannel['generator']=Config::Get('view.name');
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getTextShort();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUser()->getLogin();
			$item['category']=htmlspecialchars($oTopic->getTags());
			$topics[]=$item;
		}
		
		$this->InitRss();
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssNew() {
		$aResult=$this->Topic_GetTopicsNew(1,Config::Get('module.topic.per_page')*2,false);			
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=Config::Get('view.name');
		$aChannel['link']=Config::Get('path.root.web');
		$aChannel['description']=Config::Get('path.root.web').' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=Config::Get('general.rss_editor_mail');
		$aChannel['generator']=Config::Get('path.root.web');
		
		$topics = array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl(); 
			$item['description']=$oTopic->getTextShort();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUser()->getLogin();
			$item['category']=htmlspecialchars($oTopic->getTags());
			$topics[]=$item;
		}
		
		$this->InitRss();
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssComments() {
		/**
		 * Вычисляем топики из закрытых блогов, чтобы исключить их из выдачи
		 */
		$aCloseTopics = $this->Topic_GetTopicsCloseByUser();		
		
		$aResult=$this->Comment_GetCommentsAll('topic',1,Config::Get('module.comment.per_page')*2,$aCloseTopics);
		$aComments=$aResult['collection'];
		
		$aChannel['title']=Config::Get('view.name');
		$aChannel['link']=Config::Get('path.root.web');
		$aChannel['description']=Config::Get('path.root.web').' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=Config::Get('general.rss_editor_mail');
		$aChannel['generator']=Config::Get('path.root.web');
		
		$comments=array();
		foreach ($aComments as $oComment){
			$item['title']='Comments: '.$oComment->getTarget()->getTitle();
			$item['guid']=$oComment->getTarget()->getUrl().'#comment'.$oComment->getId();
			$item['link']=$oComment->getTarget()->getUrl().'#comment'.$oComment->getId();
			$item['description']=$oComment->getText();
			$item['pubDate']=$oComment->getDate();
			$item['author']=$oComment->getUser()->getLogin(); 
			$item['category']='comments';
			$comments[]=$item;
		}
		
		$this->InitRss();
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$comments);
		$this->SetTemplateAction('index');
	}

	protected function RssTopicComments() {
		$sTopicId=$this->GetParam(0);
		
		if (!($oTopic=$this->Topic_GetTopicById($sTopicId)) or !$oTopic->getPublish() or $oTopic->getBlog()->getType()=='close') {
			return parent::EventNotFound();
		}
		
		$aComments=$this->Comment_GetCommentsByTargetId($oTopic->getId(),'topic');
		$aComments=$aComments['comments'];
		
		$aChannel['title']=Config::Get('view.name');
		$aChannel['link']=Config::Get('path.root.web');
		$aChannel['description']=Config::Get('path.root.web').' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=Config::Get('general.rss_editor_mail');
		$aChannel['generator']=Config::Get('path.root.web');
		
		$comments=array();
		foreach ($aComments as $oComment){
			$item['title']='Comments: '.$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl().'#comment'.$oComment->getId();
			$item['link']=$oTopic->getUrl().'#comment'.$oComment->getId();
			$item['description']=$oComment->getText();
			$item['pubDate']=$oComment->getDate();
			$item['author']=$oComment->getUser()->getLogin();
			$item['category']='comments';
			$comments[]=$item;
		}
		
		$this->InitRss();
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$comments);
		$this->SetTemplateAction('index');	
	}

	protected function RssTag() {
		$sTag=urldecode($this->GetParam(0));
		$aResult=$this->Topic_GetTopicsByTag($sTag,1,Config::Get('module.topic.per_page')*2,false);
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=Config::Get('view.name');
		$aChannel['link']=Config::Get('path.root.web');
		$aChannel['description']=Config::Get('path.root.web').' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=Config::Get('general.rss_editor_mail');
		$aChannel['generator']=Config::Get('path.root.web');
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getTextShort();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUser()->getLogin();
			$item['category']=htmlspecialchars($oTopic->getTags());
			$topics[]=$item;
		}
		
		$this->InitRss();
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssColectiveBlog() {
		$sBlogUrl=$this->GetParam(0);		
		if (!$sBlogUrl or !($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl)) or $oBlog->getType()=="close") {			
			return parent::EventNotFound();
		}else{	
			$aResult=$this->Topic_GetTopicsByBlog($oBlog,1,Config::Get('module.topic.per_page')*2,'good');
		}
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=Config::Get('view.name');
		$aChannel['link']=Config::Get('path.root.web');
		$aChannel['description']=Config::Get('path.root.web').' / '.$oBlog->getTitle().' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=Config::Get('general.rss_editor_mail');
		$aChannel['generator']=Config::Get('path.root.web');
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getTextShort();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUser()->getLogin();
			$item['category']=htmlspecialchars($oTopic->getTags());
			$topics[]=$item;
		}
		
		$this->InitRss();
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssPersonalBlog() {
		$this->sUserLogin=$this->GetParam(0);
		if(!$this->sUserLogin){
			/**
			 * RSS-лента всех записей из персональных блогов
			 */
			$aResult=$this->Topic_GetTopicsPersonal(1,Config::Get('module.topic.per_page')*2);
		}elseif(!$oUser=$this->User_GetUserByLogin($this->sUserLogin)){
			return parent::EventNotFound();
		}else{
			/**
			 * RSS-лента записей персонального блога указанного пользователя
			 */			
			$aResult=$this->Topic_GetTopicsPersonalByUser($oUser->getId(),1,1,Config::Get('module.topic.per_page')*2);
		}
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=Config::Get('view.name');
		$aChannel['link']=Config::Get('path.root.web');
		$aChannel['description']=($this->sUserLogin)
			? Config::Get('path.root.web').' / '.$oUser->getLogin().' / RSS channel'
			: Config::Get('path.root.web').' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=Config::Get('general.rss_editor_mail');
		$aChannel['generator']=Config::Get('path.root.web');
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getTextShort();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUser()->getLogin();
			$item['category']=htmlspecialchars($oTopic->getTags());
			$topics[]=$item;
		}
		
		$this->InitRss();
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}
}
?>