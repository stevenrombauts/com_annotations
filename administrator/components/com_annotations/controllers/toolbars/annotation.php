<?php
class ComAnnotationsControllerToolbarAnnotation extends ComAnnotationsControllerToolbarHelper
{
	public function onAfterControllerBrowse(KEvent $event)
	{
		parent::onAfterControllerBrowse($event);
		
		$this->reset()
				->addDelete();
	}
}