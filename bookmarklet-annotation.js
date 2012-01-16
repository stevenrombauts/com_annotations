(function() {
	var loadAnnotationsClasses=function()
	{
		var scriptNode=document.createElement('script');
		scriptNode.setAttribute('language','javascript');
		scriptNode.setAttribute('src','http://localhost/various/workspace/element-annotation/bookmarklet-core.js');
		scriptNode.onload=startAnnotations;
		
		headID.appendChild(scriptNode);
	};
	
	var startAnnotations=function()
	{
		Annotations.assistent = new Annotations.Assistent();
	};
	
	var headID = document.getElementsByTagName("head")[0];
	
	var cssNode = document.createElement('link');
	cssNode.type = 'text/css';
	cssNode.rel = 'stylesheet';
	cssNode.href = 'http://localhost/various/workspace/element-annotation/media/mod_annotate/css/annotate.css';
	cssNode.media = 'screen';
	headID.appendChild(cssNode);
	
	if(typeof MooTools == 'undefined')
	{
		var scriptNode=document.createElement('script');
		scriptNode.setAttribute('language','javascript');
		scriptNode.setAttribute('src','https://ajax.googleapis.com/ajax/libs/mootools/1.2.5/mootools-yui-compressed.js');
		scriptNode.onload=loadAnnotationsClasses;
		
		headID.appendChild(scriptNode);
	} 
	else
	{
		var v = MooTools.version.substr(0, 3);
		
		if(MooTools.version.substr(0, 3).toFloat() < 1.2)
		{
			alert('I am sorry - but this page has already loaded MooTools v'+MooTools.version+'\nwhich is incompatible with the current version of the annotations bookmarklet!\n\nThis bookmarklet can only work if no MooTools has been loaded, or MooTools 1.2 is used on the page!');
			return;
		}
		
		loadAnnotationsClasses();
	}	
})();
