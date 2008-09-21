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

class TalkEntity_TalkUser extends Entity 
{    
    public function getTalkId() {
        return $this->_aData['talk_id'];
    }  
    public function getUserId() {
        return $this->_aData['user_id'];
    }
    public function getDateLast() {
        return $this->_aData['date_last'];
    }
    
        
    public function getTalkTitle() {
        return $this->_aData['talk_title'];
    }
    public function getUserLogin() {
        return $this->_aData['user_login'];
    }   
	    
  
    
	public function setTalkId($data) {
        $this->_aData['talk_id']=$data;
    }
    public function setUserId($data) {
        $this->_aData['user_id']=$data;
    }
    public function setDateLast($data) {
        $this->_aData['date_last']=$data;
    }    
}
?>