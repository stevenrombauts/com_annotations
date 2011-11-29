<?php 
class ComAnnotationsDispatcher extends ComDefaultDispatcher
{
	protected function _actionDispatch(KCommandContext $context)
	{
		$view = KRequest::get('get.view', 'cmd', $this->_controller);

		if($view == 'annotations' 
			&& !KRequest::has('get.page')
			&& KRequest::type() != 'AJAX'
		   )
		{
			$annotation = $this->getService('com://admin/annotations.model.annotations')->getList()->top();
			
			$url = clone(KRequest::url());
            $url->query['view'] = $view;
            $url->query['page'] = $annotation->page;
           
            JFactory::getApplication()->redirect($url);
		}
	
		return parent::_actionDispatch($context);
	}
}