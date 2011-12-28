window.addEvent('domready', function() {
	Annotations.assistent = new Annotations.Assistent();
	
	var dummy = $time() + $random(0, 200);
	this.request = new Request.JSON({
		url: Annotations._baseUrl+'annotations?format=json&referrer=true&cachekiller='+dummy,
		onSuccess: Annotations.assistent.load.bind(Annotations.assistent),
		method: 'get'
	}).send();

	Annotations.visibilitypoller = new Annotations.VisibilityPoller();
});

Annotations = {};

Annotations.Assistent = new Class({ 
	activated: false,
	currentTarget: null,
	currentAnnotation: null,
	
	initialize: function() 
	{ 
		window.addEvent('keydown', this.onKeyDown.bind(this));
		window.addEvent('resize', this.onResize.bind(this));
	},
	
	onKeyDown: function(e) 
	{
		//console.log(e.key);
		
		if(e.key == 'h')
		{
			if(this.currentAnnotation == null || !this.currentAnnotation.isEditing)
			{
				if(this.isActive()) {
					this.deactivate();
				} else {
					this.activate();
				}
			}
		}
		
		if(e.key == 's')
		{
			if(this.currentAnnotation == null || !this.currentAnnotation.isEditing) {
				this.save();
			}
		}
		
		if(this.currentAnnotation == null) {
			return;
		}
		
		if(this.currentAnnotation.isEditing) {
			return;
		}
		
		if(!this.isActive()) {
			return;
		}
		
		if(e.key == 'enter')
		{
			e.stop();
			e.stopPropagation();
			
			this.currentAnnotation.toElement().fireEvent('dblclick');
		}
		
		if(e.key == 'delete') {
			this.deleteCurrentAnnotation();
		}
		
		if(e.key == 'left' || e.key == 'right') {
			this.currentAnnotation.cyclePosition(e.key);
		}
		
		if(e.key == 'esc') { 
			this.currentAnnotation.deactivate();
			this.currentAnnotation = null;
		}
	},
	
	onResize: function(event)
	{
		$$('div.isAnnotation').each(function(div) {
			var annotation = div.retrieve('Annotations.Annotation');
			annotation.updatePosition(false);
		});
	},
	
	activate: function()
	{ 
		$(document.body).addEvent('mouseover', this.highlight.bind(this));
		
		// make sure we always select an annotation if activated
		if(this.currentAnnotation == null)
		{
			var annotations = $$('div.isAnnotation');
			if(annotations[0]) { 
				this.currentAnnotation = annotations[0].retrieve('Annotations.Annotation'); 
			}
		}
		
		if(this.currentAnnotation != null) { this.currentAnnotation.activate(); }
		
		this.activated = true;
	},
	
	deactivate: function() 
	{		
		$(document.body).removeEvents('mouseover'); // @TODO remove -only- the highlight event
		
		$$('div.isAnnotation').each(function(div) { div.retrieve('Annotations.Annotation').deactivate(); });
		
		this.activated = false;
		
		if(this.currentTarget != null) {
			this.currentTarget.fireEvent('mouseout');
		}
	},
	
	deleteCurrentAnnotation: function() 
	{  
		var current = this.currentAnnotation.getOrder(); // store the annotation's ordering position
		
		// delete the active annotation
		this.currentAnnotation.remove();
		this.currentAnnotation = null;
		
		// activate the previously created annotation, if any
		var annotations = $$('div.isAnnotation');
		for(var i=0;i<annotations.length;i++) 
		{
			var obj = annotations[i].retrieve('Annotations.Annotation');
			
			this.currentAnnotation = obj;			
			if(this.currentAnnotation.getOrder() == current-1)  {
				break;
			}
		}
		
		if(this.currentAnnotation != null) this.currentAnnotation.activate();
	},
	
	highlight: function(e)
	{
		var target = e.target;
		
		if(target.hasClass('isAnnotation')) {
			return;
		}
		
		if(target.getParent('div.isAnnotation')) {
			return;
		}

		if(target.get('tag') == 'option') {
			return;
		}
		
		this.currentTarget = target;
		
		// clone the element and its click events
		var clone = target.clone().cloneEvents(target, 'click');
		// remove all click events
		target.removeEvents('click');
		// if an inline onclick event is set, back it up & remove as well
		var inlineClone = null;
		if( target.get('onclick') != null )
		{
			inlineClone = target.get('onclick');
			target.set('onclick', '');
		}

		// highlight this element
		target.addClass('highlight');
		// catch click event
		target.addEvent('click', this.create.bind(this));
		// implement mouseout listener to reset to the element's original state
		target.addEvent('mouseout', function(e)
		{
			target.removeClass('highlight');
			target.removeEvent('click', this.create.bind(this));

			target.cloneEvents(clone, 'click');
			if(inlineClone != null) {
				target.set('onclick', inlineClone);
			}

			this.currentTarget = null;
		}.bind(this));
	},
	
	create: function(event)
	{
		if(this.currentTarget == null) { return; }
		
		// stop the event first
		event.stop();
		event.stopPropagation();

		// add an annotation, if none exists
		if(!this.currentTarget.annotated)
		{
			var order = $$('div.isAnnotation').length + 1;
			
			// if an annotation is currently selected, re-assign it to the current target
			if(this.currentAnnotation != null)
			{
				this.currentAnnotation.attach(this.currentTarget);
				this.currentAnnotation.updatePosition(false);
			}
			else // create a new annotation
			{
				this.currentAnnotation = new Annotations.Annotation(this.currentTarget, {ordering: order});
				
				this.currentAnnotation.activate();
				this.currentAnnotation.toElement().fireEvent('dblclick');
			}
		}
	},
	
	save: function()
	{
		// gather all annotations 
		var elements = $$('div.isAnnotation');
		var objects = [];
		for(var i=0;i<elements.length;i++)
		{
			var annotation = elements[i].retrieve('Annotations.Annotation');
			objects.push(annotation.getProperties());
		}
		
		// create the request object if it doesn't exist yet
		if(this.request == null)
		{
			var dummy = $time() + $random(0, 200);
			this.request = new Request.JSON({
				url: Annotations._baseUrl+'annotations?format=json&action=savebulk&cachekiller='+dummy,
				onSuccess: this.updateTableKeys.bind(this),
				method: 'put'
			});
		}
		
		// send the objects for saving
		this.request.cancel().send({
			data: {
				'annotations': JSON.encode(objects),
				'_token': Annotations._token
			}
		});
	},
	
	updateTableKeys: function(json)
	{
		var annotations = $$('div.isAnnotation');
		
		for(var i=0;i<json.count;i++)
		{
			var obj = json.data[i];
			
			// find the annotation and set the table key
			for(var j=0;j<annotations.length;j++)
			{
				var annotation = annotations[j].retrieve('Annotations.Annotation');
				if(annotation.getUid() == obj.uid)
				{
					annotation.setTableKey(obj.id);
					continue;
				}
			}
		}
	},
	
	load: function(payload)
	{
		var annotations = payload.items;
		annotations.each(function(annotation)
		{
			var selector = '';
			var index = -1;
			var source = null;
			
			// select the source element, based on the selector's type
			if(annotation.selector.substr(0, 1) == '#')
			{
				selector = annotation.selector.substr(1).trim();
				source = $(selector);
			}
			else
			{
				var pos = annotation.selector.indexOf('@');
				selector = annotation.selector.substr(0, pos).trim();
				index = annotation.selector.substr(pos+1).trim().toInt();
				
				var elements = $$(selector);
				source = elements[index];
			}

			if(source)
			{					
				var newAnnotation = new Annotations.Annotation(source, annotation);
				// this.setActiveAnnotation(newAnnotation); @TODO why won't this work??
				newAnnotation.toElement().fade('hide').fade(1);
				this.currentAnnotation = newAnnotation;
			}
			
		});
	},
	
	setActiveAnnotation: function(annotation) { this.currentAnnotation = annotation; },
	getActiveAnnotaton: function(annotation) { return this.currentAnnotation; },
	
	isActive: function() { return this.activated; }
});

Annotations.Annotation = new Class({
	Implements: [Options, Events],
	/* properties */
	source: null,
	selector: null,
	annotation: null,
	editField: null,
	positions: ['left', 'top', 'right', 'bottom'],
	/* flags */
	isEditing: false,
	/* options */
	options: {
		text: 'Lorem ipsum dolor sit amet',
		position: 'bottom',
		ordering: 1,
		id: null,
		
		className: 'annotation',
		zIndex: 13000
	},
	/* functions */
	initialize: function(element, options) 
	{
		this.attach(element);
		
		// set the options
		this.setOptions(options);
		
		if(!this.positions.contains(this.options.position)) {
			this.options.position = 'bottom';
		}
		
		// build & display
		this.build();
		this.updatePosition(false);
		
		this.annotation.store('Annotations.Annotation', this);
	},
	
	build: function()
	{
		// create the element
		this.annotation = new Element('div', {
				'class': this.options.className + " isAnnotation", 
				html: this.options.text,
				styles : {
					'z-index': this.options.zIndex
				}
		});

		// register the event listeners
		this.annotation
			.addEvent('dblclick', this.onDblClick.bind(this))
			.addEvent('click', this.onClick.bind(this))
			.addEvent('mouseover', this.onMouseOver.bind(this))
			.addEvent('mouseout', this.onMouseOut.bind(this));
		
		// inject into the DOM
		this.annotation.inject($(document.body));
	},

	setPosition: function(pos) 
	{		
		if(!this.positions.contains(pos)) {
			pos = 'bottom';
		}
		
		this.options.position = pos;
		this.updatePosition(true);
	},
	
	cyclePosition: function(direction)
	{
		var currentIndex = this.positions.indexOf(this.options.position);
		
		// calculate the next index
		currentIndex = currentIndex + (direction == 'left' ? 1 : -1);
		if(currentIndex >= this.positions.length) {
			currentIndex = 0;
		} else if(currentIndex < 0) {
			currentIndex = this.positions.length - 1;
		}
		
		// update
		this.setPosition(this.positions[currentIndex]);
	},
	
	updatePosition: function(morph)
	{
		var pos = null;

		switch(this.options.position) 
		{
			case 'left':
				pos = {	
					x: this.source.getPosition().x - this.annotation.getSize().x - 10, 
					y: this.source.getPosition().y + (this.source.getSize().y / 2 - this.annotation.getSize().y / 2)
				};
				break;
			case 'right':
				pos = {	
					x: this.source.getPosition().x + this.source.getSize().x + 10, 
					y: this.source.getPosition().y + (this.source.getSize().y / 2 - this.annotation.getSize().y / 2)
				};
				break;
			case 'top':
				pos = {	
					x: this.source.getPosition().x + (this.source.getSize().x / 2 - this.annotation.getSize().x / 2), 
					y: this.source.getPosition().y - this.annotation.getSize().y - 10
				};
				break;
			case 'bottom':
			default:
				pos = {	
					x: this.source.getPosition().x + (this.source.getSize().x / 2 - this.annotation.getSize().x / 2), 
					y: this.source.getPosition().y + this.source.getSize().y + 10
				};
				break;
		}

		// remove the arrow class
		for(var i=0;i<this.positions.length;i++) {
			this.annotation.removeClass('annotation-'+this.positions[i]);
		}
		
		// apply the position change
		this.annotation.addClass('annotation-'+this.options.position);
		
		if(!morph)
		{
			this.annotation.setPosition(pos);
		} 
		else
		{
			var move = new Fx.Morph(this.annotation,{ 
				 duration: 500
			}).start({
				'top': pos.y + 'px',
				'left': pos.x + 'px'
			});
		}
	},
	
	setOrdering: function(order)
	{
		this.options.ordering = order;
	},
	
	remove: function() 
	{
		this.deattach();
		this.annotation.destroy();
	},
	
	deattach: function()
	{
		this.source.annotated = false;
		this.source.removeClass('annotated_element');
		this.source.store('Annotations.Annotation', null);
	},
	
	attach: function(element)
	{
		if(this.source != null) {
			this.deattach();
		}
		
		this.source = $(element);
		this.source.annotated = true;
		this.source.addClass('annotated_element');
		
		this.source.store('Annotations.Annotation', this);
		
		this.selectorize();
	},
	
	onClick: function(event)
	{
		if(!Annotations.assistent.isActive()) return;
		
		Annotations.assistent.setActiveAnnotation(this);
		
		this.activate();
	},
	
	onDblClick: function(event)
	{
		if(!Annotations.assistent.isActive()) return;
		
		if(this.isEditing)
		{
			event.stop();
			event.stopPropagation();
			
			return;
		}
		
		// temporarily disable the assistent
		Annotations.assistent.deactivate();
		
	    var before = this.annotation.get('html').trim(); // store the current message
	  
	    // replace current text/content with textarea element
	    this.annotation.set('html', '');
	    
	    this.editField = new Element('textarea', {'class':'box', 'text': before});
	    this.editField.inject(this.annotation).select();
	    
	    // reposition to account for the new dimension
	    this.updatePosition(false);
	    
	    // register the listeners
	    this.editField.addEvent('blur', this.saveEdit.bind(this));
	    this.editField.addEvent('keydown', function(e) { if(e.key == 'esc') { e.stop(); e.stopPropagation(); this.saveEdit(); } }.bind(this) );
	    
	    // flag as being edited
	    this.isEditing = true;
	},
	
	saveEdit: function()
	{
    	// set the new value
    	this.options.text = this.editField.get('value').trim();
    	this.options.text = this.options.text.replace('\n', '<br />');
    	this.annotation.set('html', this.options.text);
    	
    	// reposition the annotation
    	this.updatePosition(false);
    	
    	this.editField.destroy();
    	this.editField = null;
    	
    	this.isEditing = false; // disable the editing flag
    	Annotations.assistent.activate(); // activate the assistant again
	},
	
	onMouseOver: function(event) 
	{ 
		if(Annotations.assistent.isActive()) this.annotation.highlight('#F7D4BE');
	},
	
	onMouseOut: function(event) {},
	
	selectorize: function()
	{
		// if available, return the element's ID
		if(this.source.get('id'))
		{
			this.selector = '#'+this.source.get('id');
			
			return this.selector;
		}
		
		// if no ID is given, we get a list of all the elements' parents, 
		// and make sure to add it's class and index
		var parent = this.source.getParent();
		var parts = [this.source.get('tag')];
		var tag = null, klass = null;
		
		while(parent != null)
		{
			tag = parent.get('tag');
			id = parent.get('id');
			klass = parent.get('class');
			
			// if classname contains 'annotation' or 'annotated', skip it
			if(klass.contains('annotat')) {
				klass = '';
			}
			
			// if the current element has multiple classes, only use the first
			if(klass.contains(' ')) { 
				klass = klass.substr(0, klass.indexOf(' '));
			}
			
			// add this parent's selector to the stack
			if(id) {
				parts.push( tag + '#' + id);
			} else { 
				parts.push( tag + (klass == '' ? '' : '.'+klass) );
			}
			
			parent = (tag == 'body' ? null : parent.getParent());
		}
		
		// create the element's selector by joining all the parts
		var selector = '';
		for(var i=parts.length-1;i>=0;i--) 
		{
			selector = selector + parts[i] + ' ';
		}
		
		// now verify the total of elements that fit this selector
		// and find the correct index to guarantee it's unique
		var hits = $$(selector);
		for(var i = 0;i<hits.length;i++)
		{
			if(hits[i] == this.source) 
			{
				index = i;
				break;
			}
		}
		
		this.selector = selector + '@' + index;
		return this.selector;
	},
	
	getProperties: function()
	{
		var properties = {
			selector: this.selector,
			uid: this.getUid(),
			text: this.options.text,
			position: this.options.position,
			ordering: this.options.ordering,
			id: this.options.id,
			
			className: this.options.className,
			zIndex: this.options.zIndex,
		};
		
		return properties;
	},
	
	activate: function()
	{
		$$('div.isAnnotation').each(function(div) { div.retrieve('Annotations.Annotation').deactivate(); });
		
		this.annotation.addClass('current_annotation');
	},
	
	deactivate: function()
	{
		this.annotation.removeClass('current_annotation');
	},
	
	toElement: function() {
		return this.annotation;
	},
	
	getSource: function() {
		return this.source;
	},
	
	setTableKey: function(id) 
	{
		this.options.id = id;
	},
	
	getUid: function() { return this.annotation.uid; },
	
	getOrder: function() { return this.options.ordering; },
	setOrder: function(order) { this.options.ordering = order; }
});


Annotations.VisibilityPoller = new Class({
	Implements: [Options, Events],
	/* options */
	options: {
		interval: 450
	},
	/* functions */
	initialize: function(element, options) 
	{
		this.setOptions(options);
		
		this.poll.bind(this).periodical(this.options.interval);
	},
	poll: function()
	{
		$$('div.isAnnotation').each(function(div)
		{
			var annotation = div.retrieve('Annotations.Annotation');
			var source = annotation.getSource();
			
			// check display property
			var display = source.getStyle('display');
			annotation.toElement().setStyle('visibility', (display == 'none' ? 'hidden': 'visible') );
			
			// copy the visibility property
			annotation.toElement().setStyle('visibility', source.getStyle('visibility'));
		});
	}
});