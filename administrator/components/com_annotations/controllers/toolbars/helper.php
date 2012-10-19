<?php
class ComAnnotationsControllerToolbarHelper extends ComDefaultControllerToolbarDefault
{
	protected function _commandHelp(KControllerToolbarCommand $command)
	{
		$command->icon = 'icon-32-help';
	
		$command->append(array(
			'href'	  => '',
            'attribs'    => array(
                'class'  	=> array('annotation-invoker')
            )
		));
	}
}