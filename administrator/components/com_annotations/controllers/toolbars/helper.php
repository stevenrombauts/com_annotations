<?php
class ComAnnotationsControllerToolbarHelper extends ComDefaultControllerToolbarDefault
{
	public function onAfterControllerBrowse(KEvent $event)
	{
		parent::onAfterControllerBrowse($event);
	
		$this->addSeparator()
			->addHelp();
	}
	
	public function onAfterControllerRead(KEvent $event)
	{
		parent::onAfterControllerRead($event);
	
		$this->addSeparator()
			->addHelp();
	}
	
	protected function _commandHelp(KControllerToolbarCommand $command)
	{
		$command->icon = 'icon-32-help';
	
		$command->append(array(
			'href'	  => '',
            'attribs'    => array(
                'class'  	=> array('annotation-invoker'),
            	'id'		=> 'annotation-invoker'
            )
		));
		
		$this->_loadAnnotations();
	}
	
	// @TODO use a separate, lightweight version of the annotations.js script
	protected function _loadAnnotations()
	{
		if(JFactory::getUser()->gid == 25) {
			return;
		}
		
		JHTML::_('behavior.mootools');
		jimport( 'joomla.application.router' );
		
		$baseUrl = JURI::root().'administrator/';
		
		$uri = clone(JURI::getInstance());
		$router = JRouter::getInstance('administrator');
		$parts = $router->parse($uri);
		
		$format = KRequest::get('request.format', 'cmd', 'html');
		$option = substr($parts['view'], 0, 4);
		
		if(empty($option) || empty($parts['view']) || $format != 'html') {
			return;
		}
		
		$view = KService::get('com://admin/'.$option.'.view.'.$parts['view'].'.'.$format);
		$layout = $view->getLayout();
		
		$identifier = 'com://admin/'.$option.'.view.'.$parts['view'].'.'.$format.'.'.$layout;
		
		$js = <<<END
		var Annotations = Annotations || {};
		
		Annotations._mode = 'view';
		Annotations._baseUrl = '{$baseUrl}';
		Annotations._identifier = '{$identifier}';
		
		window.addEvent('domready', function() {
			$('annotation-invoker').addEvent('click', function(e) { e.stop(); Annotations.assistent.toggle.bind(Annotations.assistent)(); });
		});
END;
		
		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root().'media/plg_annotate/js/annotation.js');
		$doc->addStyleSheet(JURI::root().'media/plg_annotate/css/annotation.css');
		$doc->addScriptDeclaration($js);
	}
}