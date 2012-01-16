/* Replace whitespace w/ %20 */
javascript:
(function() {
var scriptNode=document.createElement('script');
scriptNode.setAttribute('language','javascript');
scriptNode.setAttribute('src','http://localhost/various/workspace/element-annotation/bookmarklet-annotation.js');

var headID = document.getElementsByTagName('head')[0];
headID.appendChild(scriptNode);
})();