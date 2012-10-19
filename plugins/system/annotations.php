<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemAnnotations extends JPlugin
{	
	function onAfterDispatch()
	{	
		$app = JFactory::getApplication();
		if($app->getName() == 'site') {
			return;
		}
		
		if(JFactory::getUser()->gid < 25) {
			return;
		}
		
		JHTML::_('behavior.keepalive');
		JHTML::_('behavior.mootools');
		jimport( 'joomla.application.router' );
		
		$token = JUtility::getToken();
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
		
		Annotations._mode = 'manage';
		Annotations._token = '{$token}';
		Annotations._baseUrl = '{$baseUrl}';
		Annotations._identifier = '{$identifier}';
END;
		
		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root().'media/plg_annotate/js/annotation.js');
		$doc->addStyleSheet(JURI::root().'media/plg_annotate/css/annotation.css');
		$doc->addScriptDeclaration($js);
	}
}
