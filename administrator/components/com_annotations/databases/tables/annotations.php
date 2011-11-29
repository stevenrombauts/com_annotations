<?php

class ComAnnotationsDatabaseTableAnnotations extends KDatabaseTableDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	}
	
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'identity_column' => 'annotation_id',
            'name' => 'annotations',
        	'behaviors' => array('orderable'),
            'filters' => array(
                'text'   => array('html', 'tidy')
		    )
        ));
        
        parent::_initialize($config);
    }
}