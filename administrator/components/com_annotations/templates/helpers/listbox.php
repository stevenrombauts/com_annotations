<?php
class ComAnnotationsTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
	public function positions(array $config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
				'name'     => 'position'
		));
	
		$list = array('bottom', 'left', 'top', 'right');
	
		foreach($list as $item) {
			$options[] = $this->option(array('text' => JText::_(ucfirst($item)), 'value' => $item));
		}
	
		$config->options = $options;
	
		return $this->optionlist($config);
	}
}