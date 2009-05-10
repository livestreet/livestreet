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

class CommentEntity_TopicCommentOnline extends Entity 
{          
    public function getTopicId() {
        return $this->_aData['topic_id'];
    }
	public function getCommentId() {
        return $this->_aData['comment_id'];
    }
    
    	
    public function setTopicId($data) {
        $this->_aData['topic_id']=$data;
    }
    public function setCommentId($data) {
        $this->_aData['comment_id']=$data;
    }
}
?>