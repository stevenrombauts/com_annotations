com_annotations
===============

Introduction
------------

This Nooku Server component allows you to annotate DOM elements to clarify their goal and meaning. This is a proof of concept. 

Installation
------------

* Install Nooku Server 12.1
* Place all the files into your root directory
* Execute the SQL file in /administrator/components/com_annotations/install/ to install the component

Usage
-----

To display your annotations in your component, extend your toolbar class to inherit from ComAnnotationsControllerToolbarHelper. You can then add the help button to the toolbar as follows;

```php
class ComArticlesControllerToolbarArticle extends ComAnnotationsControllerToolbarHelper
{
    public function onAfterControllerBrowse(KEvent $event)
    {    
        parent::onAfterControllerBrowse($event);
        
        $this->addSeparator();
        $this->addEnable(array('label' => 'publish'));
        $this->addDisable(array('label' => 'unpublish'));
        
        $this->addSeparator();
        $this->addHelp();
    }
}
```

Then include the annotations behavior in your template.

```php
<?= @helper('com://admin/annotations.template.helper.behavior.annotations') ?>
```

Any Super Administrator can then start creating annotations in that view. Managers and Administrators will be able to toggle annotations on and off using the Help toolbar button.
To edit annotations using the element picker, use the following methods:

* When the page is loaded, press the _'h'_ key on your keyboard to start highlighting elements.
* Click on an element to annotate it.
* When editing the text, you can use HTML. To stop editing, click outside of the annotation or press the _ESC_ key.
* To deselect the current element (when not editing), press the _ESC_ key. 
* In order to relocate an annotation, select it and then highlight another element in the page.
* Press the _DEL_ key to remove an annotation.
* When selected, press the _LEFT_ and _RIGHT_ arrow keys on your keyboard to position the annotation around the element.
* Press the _'s'_ key to save all your annotations.
* You can verify if an annotation was saved by checking the Annotations component. Further editing through this component is currently not possible yet!
* Press 'h' key again to stop annotating.

To finalize and/or review your created annotations after saving, browse to com_annotations.