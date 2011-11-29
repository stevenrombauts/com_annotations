<?php
class ComAnnotationsControllerToolbarAnnotations extends ComDefaultControllerToolbarDefault
{
    public function getCommands()
    {
        $state = $this->getController()->getModel()->getState();
        
        $this->reset()
             ->addDelete();
                 
        return parent::getCommands();
    }
}