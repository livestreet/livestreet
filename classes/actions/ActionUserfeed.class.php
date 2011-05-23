<?php

class ActionUserfeed extends Action
{
    protected $oUserCurrent;

    public function Init()
    {
        $this->oUserCurrent = $this->User_getUserCurrent();
        if (!$this->oUserCurrent) {
            parent::EventNotFound();
        }
        $this->SetDefaultEvent('index');
    }

    protected function RegisterEvent()
    {
        $this->AddEvent('index', 'EventIndex');
    }

    protected function EventIndex()
    {
        $aTopics = $this->Userfeed_read($this->oUserCurrent->getId());
        $this->Viewer_Assign('aTopics', $aTopics);
        $this->SetTemplateAction('list');
    }

    protected function subscribe()
    {

    }

    protected function unsubscribe()
    {

    }

    protected function updateSubscribes()
    {

    }
}