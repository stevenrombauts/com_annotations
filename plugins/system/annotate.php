<?php
/**
 * @version     $Id$
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemAnnotate extends JPlugin
{
    public function __construct($subject, $config = array())
	{
		JHTML::_('behavior.mootools');

		$app = JFactory::getApplication();
		if($app->getName() == 'site') {
			return;
		}
		
		JHTML::_('behavior.keepalive');

		$doc = JFactory::getDocument();
		$doc->addScript(JURI::root().'media/plg_annotate/js/annotation.js');
		$doc->addStyleSheet(JURI::root().'media/plg_annotate/css/annotate.css');
		$doc->addScriptDeclaration('window.addEvent(\'domready\', function() { Annotations._token = \''.JUtility::getToken().'\'}); Annotations._baseUrl = \''.JURI::root().'administrator/\'');
	    
		parent::__construct($subject, $config);
	}
}
