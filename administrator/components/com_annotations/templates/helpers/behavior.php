<?php
class ComAnnotationsTemplateHelperBehavior extends KTemplateHelperBehavior
{
    public function annotations($config = array())
    {
    	if(strstr(KRequest::url(), '/administrator') === false) {
    		return;
    	}
    	
    	if(isset(self::$_loaded['annotations'])) {
    		return;
    	}
    	
    	$config = new KConfig($config);
    	
    	$view = $this->getTemplate()->getView();
    	$layout = $view->getLayout();
    	$identifier = ((string) $view->getIdentifier()) . '.' . $layout;
    	
    	$config->append(array(
    			'mode' 			=> (JFactory::getUser()->gid == 25 ? 'manage' : 'view'),
    			'identifier'  	=> $identifier
    	));
		
		$this->mootools();
		
		$baseUrl = KRequest::base().'/';
		$token = $this->getService('application.session')->getToken();
		
		$html = <<<END
		<script src="media://com_annotations/js/annotation.js" />
		<style src="media://com_annotations/css/annotation.css" />
		<script>
		var Annotations = Annotations || {};
		
		Annotations._mode = '{$config->mode}';
		Annotations._baseUrl = '{$baseUrl}';
		Annotations._token = '{$token}';
		Annotations._identifier = '{$identifier}';
		
		window.addEvent('domready', function() {
		
			if(Annotations._mode == 'view')
			{
				$('command-help').addEvent('click', function(e) { 
					e.stop();
					Annotations.assistent.toggle.bind(Annotations.assistent)(); 
	   			});
	   		}
		});
		</script>
END;
		
		self::$_loaded['annotations'] = true;
		
		return $html;
    }
}