<?
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

	protected function RegisterEvent() {
		$this->AddEvent('index','RssGood');
		$this->AddEvent('new','RssNew');
		$this->AddEvent('allcomments','RssComments');
		$this->AddEvent('comments','RssTopicComments');
		$this->AddEvent('tag','RssTag');
		$this->AddEvent('blog','RssColectiveBlog');
		$this->AddEvent('log','RssPersonalBlog');
	}

	protected function RssGood() {
		$aResult=$this->Topic_GetTopicsGood(1,BLOG_TOPIC_PER_PAGE*2);
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=SITE_NAME;
		$aChannel['link']=DIR_WEB_ROOT;
		$aChannel['description']=SITE_NAME.' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=RSS_EDITOR_MAIL;
		$aChannel['generator']=SITE_NAME;
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getText();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUserLogin();
			$item['category']=$oTopic->getTags();
			$topics[]=$item;
		}
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssNew() {
		$aResult=$this->Topic_GetTopicsNew(1,BLOG_TOPIC_PER_PAGE*2);			
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=SITE_NAME;
		$aChannel['link']=DIR_WEB_ROOT;
		$aChannel['description']=SITE_NAME.' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=RSS_EDITOR_MAIL;
		$aChannel['generator']=SITE_NAME;
		
		$topics = array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl(); 
			$item['description']=$oTopic->getText();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUserLogin();
			$item['category']=$oTopic->getTags();
			$topics[]=$item;
		}
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssComments() {
		$aResult=$this->Comment_GetCommentsAll(0,1,BLOG_COMMENT_PER_PAGE*2);
		$aComments=$aResult['collection'];
		
		$aChannel['title']=SITE_NAME;
		$aChannel['link']=DIR_WEB_ROOT;
		$aChannel['description']=SITE_NAME.' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=RSS_EDITOR_MAIL;
		$aChannel['generator']=SITE_NAME;
		
		$comments=array();
		foreach ($aComments as $oComment){
			$item['title']='коментар до: '.$oComment->getTopicTitle();
			$item['guid']=$oComment->getTopicUrl().'#comment'.$oComment->getId();
			$item['link']=$oComment->getTopicUrl().'#comment'.$oComment->getId();
			$item['description']=$oComment->getText();
			$item['pubDate']=$oComment->getDate();
			$item['author']=$oComment->getUserLogin(); 
			$item['category']='comments';
			$comments[]=$item;
		}
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$comments);
		$this->SetTemplateAction('index');
	}

	protected function RssTopicComments() {
		$sTopicId=$this->GetParam(0);
		
		if (!($oTopic=$this->Topic_GetTopicById($sTopicId))) {
			return parent::EventNotFound();
		}
		
		$aComments=$this->Comment_GetCommentsByTopicId($oTopic->getId());
		
		$aChannel['title']=SITE_NAME;
		$aChannel['link']=DIR_WEB_ROOT;
		$aChannel['description']=SITE_NAME.' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=RSS_EDITOR_MAIL;
		$aChannel['generator']=SITE_NAME;
		
		$comments=array();
		foreach ($aComments as $oComment){
			$item['title']='коментар до: '.$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl().'#comment'.$oComment->getId();
			$item['link']=$oTopic->getUrl().'#comment'.$oComment->getId();
			$item['description']=$oComment->getText();
			$item['pubDate']=$oComment->getDate();
			$item['author']=$oComment->getUserLogin();
			$item['category']='comments';
			$comments[]=$item;
		}
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$comments);
		$this->SetTemplateAction('index');	
	}

	protected function RssTag() {
		$sTag=urldecode($this->GetParam(0));
		$aResult=$this->Topic_GetTopicsByTag($sTag,0,1,BLOG_TOPIC_PER_PAGE*2);
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=SITE_NAME;
		$aChannel['link']=DIR_WEB_ROOT;
		$aChannel['description']=SITE_NAME.' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=RSS_EDITOR_MAIL;
		$aChannel['generator']=SITE_NAME;
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getText();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUserLogin();
			$item['category']=$oTopic->getTags();
			$topics[]=$item;
		}
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssColectiveBlog() {
		$sBlogUrl=$this->GetParam(0);		
		if (!$sBlogUrl or !($oBlog=$this->Blog_GetBlogByUrl($sBlogUrl))) {			
			return parent::EventNotFound();
		}else{	
			$aResult=$this->Topic_GetTopicsByBlogGood($oBlog,0,1,BLOG_TOPIC_PER_PAGE*2);
		}
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=SITE_NAME;
		$aChannel['link']=DIR_WEB_ROOT;
		$aChannel['description']=SITE_NAME.' / '.$oBlog->getTitle().' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=RSS_EDITOR_MAIL;
		$aChannel['generator']=SITE_NAME;
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getText();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUserLogin();
			$item['category']=$oTopic->getTags();
			$topics[]=$item;
		}
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

	protected function RssPersonalBlog() {
		$this->sUserLogin=$this->GetParam(0);		
		if (!$this->sUserLogin or !($oUser=$this->User_GetUserByLogin($this->sUserLogin))) {			
			return parent::EventNotFound();
		}else{	
			$aResult=$this->Topic_GetTopicsPersonalByUser($oUser->getId(),1,0,1,BLOG_TOPIC_PER_PAGE*2);
		}
		$aTopics=$aResult['collection'];
		
		$aChannel['title']=SITE_NAME;
		$aChannel['link']=DIR_WEB_ROOT;
		$aChannel['description']=SITE_NAME.' / '.$oUser->getLogin().' / RSS channel';
		$aChannel['language']='ru';
		$aChannel['managingEditor']=RSS_EDITOR_MAIL;
		$aChannel['generator']=SITE_NAME;
		
		$topics=array();
		foreach ($aTopics as $oTopic){
			$item['title']=$oTopic->getTitle();
			$item['guid']=$oTopic->getUrl();
			$item['link']=$oTopic->getUrl();
			$item['description']=$oTopic->getText();
			$item['pubDate']=$oTopic->getDateAdd();
			$item['author']=$oTopic->getUserLogin();
			$item['category']=$oTopic->getTags();
			$topics[]=$item;
		}
		$this->Viewer_Assign('aChannel',$aChannel);
		$this->Viewer_Assign('aItems',$topics);
		$this->SetTemplateAction('index');
	}

}
?>