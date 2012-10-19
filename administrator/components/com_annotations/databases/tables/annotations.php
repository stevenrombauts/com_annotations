<?php
class ComAnnotationsDatabaseTableAnnotations extends KDatabaseTableDefault
{	
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