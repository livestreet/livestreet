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

class TopicEntity_FavouriteTopic extends Entity 
{    
    public function getTopicId() {
        return $this->_aData['topic_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getTopicPublish() {
        return $this->_aData['topic_publish'];
    }
    
    
	public function setTopicId($data) {
        $this->_aData['topic_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setTopicPublish($data) {
        $this->_aData['topic_publish']=$data;
    }
}
?>