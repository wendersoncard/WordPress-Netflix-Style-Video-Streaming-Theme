/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);
/*!
 * Isotope PACKAGED v3.0.4
 *
 * Licensed GPLv3 for open source use
 * or Isotope Commercial License for commercial use
 *
 * http://isotope.metafizzy.co
 * Copyright 2017 Metafizzy
 */

!function(t,e){"function"==typeof define&&define.amd?define("jquery-bridget/jquery-bridget",["jquery"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("jquery")):t.jQueryBridget=e(t,t.jQuery)}(window,function(t,e){"use strict";function i(i,s,a){function u(t,e,o){var n,s="$()."+i+'("'+e+'")';return t.each(function(t,u){var h=a.data(u,i);if(!h)return void r(i+" not initialized. Cannot call methods, i.e. "+s);var d=h[e];if(!d||"_"==e.charAt(0))return void r(s+" is not a valid method");var l=d.apply(h,o);n=void 0===n?l:n}),void 0!==n?n:t}function h(t,e){t.each(function(t,o){var n=a.data(o,i);n?(n.option(e),n._init()):(n=new s(o,e),a.data(o,i,n))})}a=a||e||t.jQuery,a&&(s.prototype.option||(s.prototype.option=function(t){a.isPlainObject(t)&&(this.options=a.extend(!0,this.options,t))}),a.fn[i]=function(t){if("string"==typeof t){var e=n.call(arguments,1);return u(this,t,e)}return h(this,t),this},o(a))}function o(t){!t||t&&t.bridget||(t.bridget=i)}var n=Array.prototype.slice,s=t.console,r="undefined"==typeof s?function(){}:function(t){s.error(t)};return o(e||t.jQuery),i}),function(t,e){"function"==typeof define&&define.amd?define("ev-emitter/ev-emitter",e):"object"==typeof module&&module.exports?module.exports=e():t.EvEmitter=e()}("undefined"!=typeof window?window:this,function(){function t(){}var e=t.prototype;return e.on=function(t,e){if(t&&e){var i=this._events=this._events||{},o=i[t]=i[t]||[];return o.indexOf(e)==-1&&o.push(e),this}},e.once=function(t,e){if(t&&e){this.on(t,e);var i=this._onceEvents=this._onceEvents||{},o=i[t]=i[t]||{};return o[e]=!0,this}},e.off=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var o=i.indexOf(e);return o!=-1&&i.splice(o,1),this}},e.emitEvent=function(t,e){var i=this._events&&this._events[t];if(i&&i.length){var o=0,n=i[o];e=e||[];for(var s=this._onceEvents&&this._onceEvents[t];n;){var r=s&&s[n];r&&(this.off(t,n),delete s[n]),n.apply(this,e),o+=r?0:1,n=i[o]}return this}},t}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("get-size/get-size",[],function(){return e()}):"object"==typeof module&&module.exports?module.exports=e():t.getSize=e()}(window,function(){"use strict";function t(t){var e=parseFloat(t),i=t.indexOf("%")==-1&&!isNaN(e);return i&&e}function e(){}function i(){for(var t={width:0,height:0,innerWidth:0,innerHeight:0,outerWidth:0,outerHeight:0},e=0;e<h;e++){var i=u[e];t[i]=0}return t}function o(t){var e=getComputedStyle(t);return e||a("Style returned "+e+". Are you running this code in a hidden iframe on Firefox? See http://bit.ly/getsizebug1"),e}function n(){if(!d){d=!0;var e=document.createElement("div");e.style.width="200px",e.style.padding="1px 2px 3px 4px",e.style.borderStyle="solid",e.style.borderWidth="1px 2px 3px 4px",e.style.boxSizing="border-box";var i=document.body||document.documentElement;i.appendChild(e);var n=o(e);s.isBoxSizeOuter=r=200==t(n.width),i.removeChild(e)}}function s(e){if(n(),"string"==typeof e&&(e=document.querySelector(e)),e&&"object"==typeof e&&e.nodeType){var s=o(e);if("none"==s.display)return i();var a={};a.width=e.offsetWidth,a.height=e.offsetHeight;for(var d=a.isBorderBox="border-box"==s.boxSizing,l=0;l<h;l++){var f=u[l],c=s[f],m=parseFloat(c);a[f]=isNaN(m)?0:m}var p=a.paddingLeft+a.paddingRight,y=a.paddingTop+a.paddingBottom,g=a.marginLeft+a.marginRight,v=a.marginTop+a.marginBottom,_=a.borderLeftWidth+a.borderRightWidth,I=a.borderTopWidth+a.borderBottomWidth,z=d&&r,x=t(s.width);x!==!1&&(a.width=x+(z?0:p+_));var S=t(s.height);return S!==!1&&(a.height=S+(z?0:y+I)),a.innerWidth=a.width-(p+_),a.innerHeight=a.height-(y+I),a.outerWidth=a.width+g,a.outerHeight=a.height+v,a}}var r,a="undefined"==typeof console?e:function(t){console.error(t)},u=["paddingLeft","paddingRight","paddingTop","paddingBottom","marginLeft","marginRight","marginTop","marginBottom","borderLeftWidth","borderRightWidth","borderTopWidth","borderBottomWidth"],h=u.length,d=!1;return s}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("desandro-matches-selector/matches-selector",e):"object"==typeof module&&module.exports?module.exports=e():t.matchesSelector=e()}(window,function(){"use strict";var t=function(){var t=window.Element.prototype;if(t.matches)return"matches";if(t.matchesSelector)return"matchesSelector";for(var e=["webkit","moz","ms","o"],i=0;i<e.length;i++){var o=e[i],n=o+"MatchesSelector";if(t[n])return n}}();return function(e,i){return e[t](i)}}),function(t,e){"function"==typeof define&&define.amd?define("fizzy-ui-utils/utils",["desandro-matches-selector/matches-selector"],function(i){return e(t,i)}):"object"==typeof module&&module.exports?module.exports=e(t,require("desandro-matches-selector")):t.fizzyUIUtils=e(t,t.matchesSelector)}(window,function(t,e){var i={};i.extend=function(t,e){for(var i in e)t[i]=e[i];return t},i.modulo=function(t,e){return(t%e+e)%e},i.makeArray=function(t){var e=[];if(Array.isArray(t))e=t;else if(t&&"object"==typeof t&&"number"==typeof t.length)for(var i=0;i<t.length;i++)e.push(t[i]);else e.push(t);return e},i.removeFrom=function(t,e){var i=t.indexOf(e);i!=-1&&t.splice(i,1)},i.getParent=function(t,i){for(;t.parentNode&&t!=document.body;)if(t=t.parentNode,e(t,i))return t},i.getQueryElement=function(t){return"string"==typeof t?document.querySelector(t):t},i.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},i.filterFindElements=function(t,o){t=i.makeArray(t);var n=[];return t.forEach(function(t){if(t instanceof HTMLElement){if(!o)return void n.push(t);e(t,o)&&n.push(t);for(var i=t.querySelectorAll(o),s=0;s<i.length;s++)n.push(i[s])}}),n},i.debounceMethod=function(t,e,i){var o=t.prototype[e],n=e+"Timeout";t.prototype[e]=function(){var t=this[n];t&&clearTimeout(t);var e=arguments,s=this;this[n]=setTimeout(function(){o.apply(s,e),delete s[n]},i||100)}},i.docReady=function(t){var e=document.readyState;"complete"==e||"interactive"==e?setTimeout(t):document.addEventListener("DOMContentLoaded",t)},i.toDashed=function(t){return t.replace(/(.)([A-Z])/g,function(t,e,i){return e+"-"+i}).toLowerCase()};var o=t.console;return i.htmlInit=function(e,n){i.docReady(function(){var s=i.toDashed(n),r="data-"+s,a=document.querySelectorAll("["+r+"]"),u=document.querySelectorAll(".js-"+s),h=i.makeArray(a).concat(i.makeArray(u)),d=r+"-options",l=t.jQuery;h.forEach(function(t){var i,s=t.getAttribute(r)||t.getAttribute(d);try{i=s&&JSON.parse(s)}catch(a){return void(o&&o.error("Error parsing "+r+" on "+t.className+": "+a))}var u=new e(t,i);l&&l.data(t,n,u)})})},i}),function(t,e){"function"==typeof define&&define.amd?define("outlayer/item",["ev-emitter/ev-emitter","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("ev-emitter"),require("get-size")):(t.Outlayer={},t.Outlayer.Item=e(t.EvEmitter,t.getSize))}(window,function(t,e){"use strict";function i(t){for(var e in t)return!1;return e=null,!0}function o(t,e){t&&(this.element=t,this.layout=e,this.position={x:0,y:0},this._create())}function n(t){return t.replace(/([A-Z])/g,function(t){return"-"+t.toLowerCase()})}var s=document.documentElement.style,r="string"==typeof s.transition?"transition":"WebkitTransition",a="string"==typeof s.transform?"transform":"WebkitTransform",u={WebkitTransition:"webkitTransitionEnd",transition:"transitionend"}[r],h={transform:a,transition:r,transitionDuration:r+"Duration",transitionProperty:r+"Property",transitionDelay:r+"Delay"},d=o.prototype=Object.create(t.prototype);d.constructor=o,d._create=function(){this._transn={ingProperties:{},clean:{},onEnd:{}},this.css({position:"absolute"})},d.handleEvent=function(t){var e="on"+t.type;this[e]&&this[e](t)},d.getSize=function(){this.size=e(this.element)},d.css=function(t){var e=this.element.style;for(var i in t){var o=h[i]||i;e[o]=t[i]}},d.getPosition=function(){var t=getComputedStyle(this.element),e=this.layout._getOption("originLeft"),i=this.layout._getOption("originTop"),o=t[e?"left":"right"],n=t[i?"top":"bottom"],s=this.layout.size,r=o.indexOf("%")!=-1?parseFloat(o)/100*s.width:parseInt(o,10),a=n.indexOf("%")!=-1?parseFloat(n)/100*s.height:parseInt(n,10);r=isNaN(r)?0:r,a=isNaN(a)?0:a,r-=e?s.paddingLeft:s.paddingRight,a-=i?s.paddingTop:s.paddingBottom,this.position.x=r,this.position.y=a},d.layoutPosition=function(){var t=this.layout.size,e={},i=this.layout._getOption("originLeft"),o=this.layout._getOption("originTop"),n=i?"paddingLeft":"paddingRight",s=i?"left":"right",r=i?"right":"left",a=this.position.x+t[n];e[s]=this.getXValue(a),e[r]="";var u=o?"paddingTop":"paddingBottom",h=o?"top":"bottom",d=o?"bottom":"top",l=this.position.y+t[u];e[h]=this.getYValue(l),e[d]="",this.css(e),this.emitEvent("layout",[this])},d.getXValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&!e?t/this.layout.size.width*100+"%":t+"px"},d.getYValue=function(t){var e=this.layout._getOption("horizontal");return this.layout.options.percentPosition&&e?t/this.layout.size.height*100+"%":t+"px"},d._transitionTo=function(t,e){this.getPosition();var i=this.position.x,o=this.position.y,n=parseInt(t,10),s=parseInt(e,10),r=n===this.position.x&&s===this.position.y;if(this.setPosition(t,e),r&&!this.isTransitioning)return void this.layoutPosition();var a=t-i,u=e-o,h={};h.transform=this.getTranslate(a,u),this.transition({to:h,onTransitionEnd:{transform:this.layoutPosition},isCleaning:!0})},d.getTranslate=function(t,e){var i=this.layout._getOption("originLeft"),o=this.layout._getOption("originTop");return t=i?t:-t,e=o?e:-e,"translate3d("+t+"px, "+e+"px, 0)"},d.goTo=function(t,e){this.setPosition(t,e),this.layoutPosition()},d.moveTo=d._transitionTo,d.setPosition=function(t,e){this.position.x=parseInt(t,10),this.position.y=parseInt(e,10)},d._nonTransition=function(t){this.css(t.to),t.isCleaning&&this._removeStyles(t.to);for(var e in t.onTransitionEnd)t.onTransitionEnd[e].call(this)},d.transition=function(t){if(!parseFloat(this.layout.options.transitionDuration))return void this._nonTransition(t);var e=this._transn;for(var i in t.onTransitionEnd)e.onEnd[i]=t.onTransitionEnd[i];for(i in t.to)e.ingProperties[i]=!0,t.isCleaning&&(e.clean[i]=!0);if(t.from){this.css(t.from);var o=this.element.offsetHeight;o=null}this.enableTransition(t.to),this.css(t.to),this.isTransitioning=!0};var l="opacity,"+n(a);d.enableTransition=function(){if(!this.isTransitioning){var t=this.layout.options.transitionDuration;t="number"==typeof t?t+"ms":t,this.css({transitionProperty:l,transitionDuration:t,transitionDelay:this.staggerDelay||0}),this.element.addEventListener(u,this,!1)}},d.onwebkitTransitionEnd=function(t){this.ontransitionend(t)},d.onotransitionend=function(t){this.ontransitionend(t)};var f={"-webkit-transform":"transform"};d.ontransitionend=function(t){if(t.target===this.element){var e=this._transn,o=f[t.propertyName]||t.propertyName;if(delete e.ingProperties[o],i(e.ingProperties)&&this.disableTransition(),o in e.clean&&(this.element.style[t.propertyName]="",delete e.clean[o]),o in e.onEnd){var n=e.onEnd[o];n.call(this),delete e.onEnd[o]}this.emitEvent("transitionEnd",[this])}},d.disableTransition=function(){this.removeTransitionStyles(),this.element.removeEventListener(u,this,!1),this.isTransitioning=!1},d._removeStyles=function(t){var e={};for(var i in t)e[i]="";this.css(e)};var c={transitionProperty:"",transitionDuration:"",transitionDelay:""};return d.removeTransitionStyles=function(){this.css(c)},d.stagger=function(t){t=isNaN(t)?0:t,this.staggerDelay=t+"ms"},d.removeElem=function(){this.element.parentNode.removeChild(this.element),this.css({display:""}),this.emitEvent("remove",[this])},d.remove=function(){return r&&parseFloat(this.layout.options.transitionDuration)?(this.once("transitionEnd",function(){this.removeElem()}),void this.hide()):void this.removeElem()},d.reveal=function(){delete this.isHidden,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("visibleStyle");e[i]=this.onRevealTransitionEnd,this.transition({from:t.hiddenStyle,to:t.visibleStyle,isCleaning:!0,onTransitionEnd:e})},d.onRevealTransitionEnd=function(){this.isHidden||this.emitEvent("reveal")},d.getHideRevealTransitionEndProperty=function(t){var e=this.layout.options[t];if(e.opacity)return"opacity";for(var i in e)return i},d.hide=function(){this.isHidden=!0,this.css({display:""});var t=this.layout.options,e={},i=this.getHideRevealTransitionEndProperty("hiddenStyle");e[i]=this.onHideTransitionEnd,this.transition({from:t.visibleStyle,to:t.hiddenStyle,isCleaning:!0,onTransitionEnd:e})},d.onHideTransitionEnd=function(){this.isHidden&&(this.css({display:"none"}),this.emitEvent("hide"))},d.destroy=function(){this.css({position:"",left:"",right:"",top:"",bottom:"",transition:"",transform:""})},o}),function(t,e){"use strict";"function"==typeof define&&define.amd?define("outlayer/outlayer",["ev-emitter/ev-emitter","get-size/get-size","fizzy-ui-utils/utils","./item"],function(i,o,n,s){return e(t,i,o,n,s)}):"object"==typeof module&&module.exports?module.exports=e(t,require("ev-emitter"),require("get-size"),require("fizzy-ui-utils"),require("./item")):t.Outlayer=e(t,t.EvEmitter,t.getSize,t.fizzyUIUtils,t.Outlayer.Item)}(window,function(t,e,i,o,n){"use strict";function s(t,e){var i=o.getQueryElement(t);if(!i)return void(u&&u.error("Bad element for "+this.constructor.namespace+": "+(i||t)));this.element=i,h&&(this.$element=h(this.element)),this.options=o.extend({},this.constructor.defaults),this.option(e);var n=++l;this.element.outlayerGUID=n,f[n]=this,this._create();var s=this._getOption("initLayout");s&&this.layout()}function r(t){function e(){t.apply(this,arguments)}return e.prototype=Object.create(t.prototype),e.prototype.constructor=e,e}function a(t){if("number"==typeof t)return t;var e=t.match(/(^\d*\.?\d*)(\w*)/),i=e&&e[1],o=e&&e[2];if(!i.length)return 0;i=parseFloat(i);var n=m[o]||1;return i*n}var u=t.console,h=t.jQuery,d=function(){},l=0,f={};s.namespace="outlayer",s.Item=n,s.defaults={containerStyle:{position:"relative"},initLayout:!0,originLeft:!0,originTop:!0,resize:!0,resizeContainer:!0,transitionDuration:"0.4s",hiddenStyle:{opacity:0,transform:"scale(0.001)"},visibleStyle:{opacity:1,transform:"scale(1)"}};var c=s.prototype;o.extend(c,e.prototype),c.option=function(t){o.extend(this.options,t)},c._getOption=function(t){var e=this.constructor.compatOptions[t];return e&&void 0!==this.options[e]?this.options[e]:this.options[t]},s.compatOptions={initLayout:"isInitLayout",horizontal:"isHorizontal",layoutInstant:"isLayoutInstant",originLeft:"isOriginLeft",originTop:"isOriginTop",resize:"isResizeBound",resizeContainer:"isResizingContainer"},c._create=function(){this.reloadItems(),this.stamps=[],this.stamp(this.options.stamp),o.extend(this.element.style,this.options.containerStyle);var t=this._getOption("resize");t&&this.bindResize()},c.reloadItems=function(){this.items=this._itemize(this.element.children)},c._itemize=function(t){for(var e=this._filterFindItemElements(t),i=this.constructor.Item,o=[],n=0;n<e.length;n++){var s=e[n],r=new i(s,this);o.push(r)}return o},c._filterFindItemElements=function(t){return o.filterFindElements(t,this.options.itemSelector)},c.getItemElements=function(){return this.items.map(function(t){return t.element})},c.layout=function(){this._resetLayout(),this._manageStamps();var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;this.layoutItems(this.items,e),this._isLayoutInited=!0},c._init=c.layout,c._resetLayout=function(){this.getSize()},c.getSize=function(){this.size=i(this.element)},c._getMeasurement=function(t,e){var o,n=this.options[t];n?("string"==typeof n?o=this.element.querySelector(n):n instanceof HTMLElement&&(o=n),this[t]=o?i(o)[e]:n):this[t]=0},c.layoutItems=function(t,e){t=this._getItemsForLayout(t),this._layoutItems(t,e),this._postLayout()},c._getItemsForLayout=function(t){return t.filter(function(t){return!t.isIgnored})},c._layoutItems=function(t,e){if(this._emitCompleteOnItems("layout",t),t&&t.length){var i=[];t.forEach(function(t){var o=this._getItemLayoutPosition(t);o.item=t,o.isInstant=e||t.isLayoutInstant,i.push(o)},this),this._processLayoutQueue(i)}},c._getItemLayoutPosition=function(){return{x:0,y:0}},c._processLayoutQueue=function(t){this.updateStagger(),t.forEach(function(t,e){this._positionItem(t.item,t.x,t.y,t.isInstant,e)},this)},c.updateStagger=function(){var t=this.options.stagger;return null===t||void 0===t?void(this.stagger=0):(this.stagger=a(t),this.stagger)},c._positionItem=function(t,e,i,o,n){o?t.goTo(e,i):(t.stagger(n*this.stagger),t.moveTo(e,i))},c._postLayout=function(){this.resizeContainer()},c.resizeContainer=function(){var t=this._getOption("resizeContainer");if(t){var e=this._getContainerSize();e&&(this._setContainerMeasure(e.width,!0),this._setContainerMeasure(e.height,!1))}},c._getContainerSize=d,c._setContainerMeasure=function(t,e){if(void 0!==t){var i=this.size;i.isBorderBox&&(t+=e?i.paddingLeft+i.paddingRight+i.borderLeftWidth+i.borderRightWidth:i.paddingBottom+i.paddingTop+i.borderTopWidth+i.borderBottomWidth),t=Math.max(t,0),this.element.style[e?"width":"height"]=t+"px"}},c._emitCompleteOnItems=function(t,e){function i(){n.dispatchEvent(t+"Complete",null,[e])}function o(){r++,r==s&&i()}var n=this,s=e.length;if(!e||!s)return void i();var r=0;e.forEach(function(e){e.once(t,o)})},c.dispatchEvent=function(t,e,i){var o=e?[e].concat(i):i;if(this.emitEvent(t,o),h)if(this.$element=this.$element||h(this.element),e){var n=h.Event(e);n.type=t,this.$element.trigger(n,i)}else this.$element.trigger(t,i)},c.ignore=function(t){var e=this.getItem(t);e&&(e.isIgnored=!0)},c.unignore=function(t){var e=this.getItem(t);e&&delete e.isIgnored},c.stamp=function(t){t=this._find(t),t&&(this.stamps=this.stamps.concat(t),t.forEach(this.ignore,this))},c.unstamp=function(t){t=this._find(t),t&&t.forEach(function(t){o.removeFrom(this.stamps,t),this.unignore(t)},this)},c._find=function(t){if(t)return"string"==typeof t&&(t=this.element.querySelectorAll(t)),t=o.makeArray(t)},c._manageStamps=function(){this.stamps&&this.stamps.length&&(this._getBoundingRect(),this.stamps.forEach(this._manageStamp,this))},c._getBoundingRect=function(){var t=this.element.getBoundingClientRect(),e=this.size;this._boundingRect={left:t.left+e.paddingLeft+e.borderLeftWidth,top:t.top+e.paddingTop+e.borderTopWidth,right:t.right-(e.paddingRight+e.borderRightWidth),bottom:t.bottom-(e.paddingBottom+e.borderBottomWidth)}},c._manageStamp=d,c._getElementOffset=function(t){var e=t.getBoundingClientRect(),o=this._boundingRect,n=i(t),s={left:e.left-o.left-n.marginLeft,top:e.top-o.top-n.marginTop,right:o.right-e.right-n.marginRight,bottom:o.bottom-e.bottom-n.marginBottom};return s},c.handleEvent=o.handleEvent,c.bindResize=function(){t.addEventListener("resize",this),this.isResizeBound=!0},c.unbindResize=function(){t.removeEventListener("resize",this),this.isResizeBound=!1},c.onresize=function(){this.resize()},o.debounceMethod(s,"onresize",100),c.resize=function(){this.isResizeBound&&this.needsResizeLayout()&&this.layout()},c.needsResizeLayout=function(){var t=i(this.element),e=this.size&&t;return e&&t.innerWidth!==this.size.innerWidth},c.addItems=function(t){var e=this._itemize(t);return e.length&&(this.items=this.items.concat(e)),e},c.appended=function(t){var e=this.addItems(t);e.length&&(this.layoutItems(e,!0),this.reveal(e))},c.prepended=function(t){var e=this._itemize(t);if(e.length){var i=this.items.slice(0);this.items=e.concat(i),this._resetLayout(),this._manageStamps(),this.layoutItems(e,!0),this.reveal(e),this.layoutItems(i)}},c.reveal=function(t){if(this._emitCompleteOnItems("reveal",t),t&&t.length){var e=this.updateStagger();t.forEach(function(t,i){t.stagger(i*e),t.reveal()})}},c.hide=function(t){if(this._emitCompleteOnItems("hide",t),t&&t.length){var e=this.updateStagger();t.forEach(function(t,i){t.stagger(i*e),t.hide()})}},c.revealItemElements=function(t){var e=this.getItems(t);this.reveal(e)},c.hideItemElements=function(t){var e=this.getItems(t);this.hide(e)},c.getItem=function(t){for(var e=0;e<this.items.length;e++){var i=this.items[e];if(i.element==t)return i}},c.getItems=function(t){t=o.makeArray(t);var e=[];return t.forEach(function(t){var i=this.getItem(t);i&&e.push(i)},this),e},c.remove=function(t){var e=this.getItems(t);this._emitCompleteOnItems("remove",e),e&&e.length&&e.forEach(function(t){t.remove(),o.removeFrom(this.items,t)},this)},c.destroy=function(){var t=this.element.style;t.height="",t.position="",t.width="",this.items.forEach(function(t){t.destroy()}),this.unbindResize();var e=this.element.outlayerGUID;delete f[e],delete this.element.outlayerGUID,h&&h.removeData(this.element,this.constructor.namespace)},s.data=function(t){t=o.getQueryElement(t);var e=t&&t.outlayerGUID;return e&&f[e]},s.create=function(t,e){var i=r(s);return i.defaults=o.extend({},s.defaults),o.extend(i.defaults,e),i.compatOptions=o.extend({},s.compatOptions),i.namespace=t,i.data=s.data,i.Item=r(n),o.htmlInit(i,t),h&&h.bridget&&h.bridget(t,i),i};var m={ms:1,s:1e3};return s.Item=n,s}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/item",["outlayer/outlayer"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.Item=e(t.Outlayer))}(window,function(t){"use strict";function e(){t.Item.apply(this,arguments)}var i=e.prototype=Object.create(t.Item.prototype),o=i._create;i._create=function(){this.id=this.layout.itemGUID++,o.call(this),this.sortData={}},i.updateSortData=function(){if(!this.isIgnored){this.sortData.id=this.id,this.sortData["original-order"]=this.id,this.sortData.random=Math.random();var t=this.layout.options.getSortData,e=this.layout._sorters;for(var i in t){var o=e[i];this.sortData[i]=o(this.element,this)}}};var n=i.destroy;return i.destroy=function(){n.apply(this,arguments),this.css({display:""})},e}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-mode",["get-size/get-size","outlayer/outlayer"],e):"object"==typeof module&&module.exports?module.exports=e(require("get-size"),require("outlayer")):(t.Isotope=t.Isotope||{},t.Isotope.LayoutMode=e(t.getSize,t.Outlayer))}(window,function(t,e){"use strict";function i(t){this.isotope=t,t&&(this.options=t.options[this.namespace],this.element=t.element,this.items=t.filteredItems,this.size=t.size)}var o=i.prototype,n=["_resetLayout","_getItemLayoutPosition","_manageStamp","_getContainerSize","_getElementOffset","needsResizeLayout","_getOption"];return n.forEach(function(t){o[t]=function(){return e.prototype[t].apply(this.isotope,arguments)}}),o.needsVerticalResizeLayout=function(){var e=t(this.isotope.element),i=this.isotope.size&&e;return i&&e.innerHeight!=this.isotope.size.innerHeight},o._getMeasurement=function(){this.isotope._getMeasurement.apply(this,arguments)},o.getColumnWidth=function(){this.getSegmentSize("column","Width")},o.getRowHeight=function(){this.getSegmentSize("row","Height")},o.getSegmentSize=function(t,e){var i=t+e,o="outer"+e;if(this._getMeasurement(i,o),!this[i]){var n=this.getFirstItemSize();this[i]=n&&n[o]||this.isotope.size["inner"+e]}},o.getFirstItemSize=function(){var e=this.isotope.filteredItems[0];return e&&e.element&&t(e.element)},o.layout=function(){this.isotope.layout.apply(this.isotope,arguments)},o.getSize=function(){this.isotope.getSize(),this.size=this.isotope.size},i.modes={},i.create=function(t,e){function n(){i.apply(this,arguments)}return n.prototype=Object.create(o),n.prototype.constructor=n,e&&(n.options=e),n.prototype.namespace=t,i.modes[t]=n,n},i}),function(t,e){"function"==typeof define&&define.amd?define("masonry/masonry",["outlayer/outlayer","get-size/get-size"],e):"object"==typeof module&&module.exports?module.exports=e(require("outlayer"),require("get-size")):t.Masonry=e(t.Outlayer,t.getSize)}(window,function(t,e){var i=t.create("masonry");i.compatOptions.fitWidth="isFitWidth";var o=i.prototype;return o._resetLayout=function(){this.getSize(),this._getMeasurement("columnWidth","outerWidth"),this._getMeasurement("gutter","outerWidth"),this.measureColumns(),this.colYs=[];for(var t=0;t<this.cols;t++)this.colYs.push(0);this.maxY=0,this.horizontalColIndex=0},o.measureColumns=function(){if(this.getContainerWidth(),!this.columnWidth){var t=this.items[0],i=t&&t.element;this.columnWidth=i&&e(i).outerWidth||this.containerWidth}var o=this.columnWidth+=this.gutter,n=this.containerWidth+this.gutter,s=n/o,r=o-n%o,a=r&&r<1?"round":"floor";s=Math[a](s),this.cols=Math.max(s,1)},o.getContainerWidth=function(){var t=this._getOption("fitWidth"),i=t?this.element.parentNode:this.element,o=e(i);this.containerWidth=o&&o.innerWidth},o._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth%this.columnWidth,i=e&&e<1?"round":"ceil",o=Math[i](t.size.outerWidth/this.columnWidth);o=Math.min(o,this.cols);for(var n=this.options.horizontalOrder?"_getHorizontalColPosition":"_getTopColPosition",s=this[n](o,t),r={x:this.columnWidth*s.col,y:s.y},a=s.y+t.size.outerHeight,u=o+s.col,h=s.col;h<u;h++)this.colYs[h]=a;return r},o._getTopColPosition=function(t){var e=this._getTopColGroup(t),i=Math.min.apply(Math,e);return{col:e.indexOf(i),y:i}},o._getTopColGroup=function(t){if(t<2)return this.colYs;for(var e=[],i=this.cols+1-t,o=0;o<i;o++)e[o]=this._getColGroupY(o,t);return e},o._getColGroupY=function(t,e){if(e<2)return this.colYs[t];var i=this.colYs.slice(t,t+e);return Math.max.apply(Math,i)},o._getHorizontalColPosition=function(t,e){var i=this.horizontalColIndex%this.cols,o=t>1&&i+t>this.cols;i=o?0:i;var n=e.size.outerWidth&&e.size.outerHeight;return this.horizontalColIndex=n?i+t:this.horizontalColIndex,{col:i,y:this._getColGroupY(i,t)}},o._manageStamp=function(t){var i=e(t),o=this._getElementOffset(t),n=this._getOption("originLeft"),s=n?o.left:o.right,r=s+i.outerWidth,a=Math.floor(s/this.columnWidth);a=Math.max(0,a);var u=Math.floor(r/this.columnWidth);u-=r%this.columnWidth?0:1,u=Math.min(this.cols-1,u);for(var h=this._getOption("originTop"),d=(h?o.top:o.bottom)+i.outerHeight,l=a;l<=u;l++)this.colYs[l]=Math.max(d,this.colYs[l])},o._getContainerSize=function(){this.maxY=Math.max.apply(Math,this.colYs);var t={height:this.maxY};return this._getOption("fitWidth")&&(t.width=this._getContainerFitWidth()),t},o._getContainerFitWidth=function(){for(var t=0,e=this.cols;--e&&0===this.colYs[e];)t++;return(this.cols-t)*this.columnWidth-this.gutter},o.needsResizeLayout=function(){var t=this.containerWidth;return this.getContainerWidth(),t!=this.containerWidth},i}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-modes/masonry",["../layout-mode","masonry/masonry"],e):"object"==typeof module&&module.exports?module.exports=e(require("../layout-mode"),require("masonry-layout")):e(t.Isotope.LayoutMode,t.Masonry)}(window,function(t,e){"use strict";var i=t.create("masonry"),o=i.prototype,n={_getElementOffset:!0,layout:!0,_getMeasurement:!0};for(var s in e.prototype)n[s]||(o[s]=e.prototype[s]);var r=o.measureColumns;o.measureColumns=function(){this.items=this.isotope.filteredItems,r.call(this)};var a=o._getOption;return o._getOption=function(t){return"fitWidth"==t?void 0!==this.options.isFitWidth?this.options.isFitWidth:this.options.fitWidth:a.apply(this.isotope,arguments)},i}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-modes/fit-rows",["../layout-mode"],e):"object"==typeof exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window,function(t){"use strict";var e=t.create("fitRows"),i=e.prototype;return i._resetLayout=function(){this.x=0,this.y=0,this.maxY=0,this._getMeasurement("gutter","outerWidth")},i._getItemLayoutPosition=function(t){t.getSize();var e=t.size.outerWidth+this.gutter,i=this.isotope.size.innerWidth+this.gutter;0!==this.x&&e+this.x>i&&(this.x=0,this.y=this.maxY);var o={x:this.x,y:this.y};return this.maxY=Math.max(this.maxY,this.y+t.size.outerHeight),this.x+=e,o},i._getContainerSize=function(){return{height:this.maxY}},e}),function(t,e){"function"==typeof define&&define.amd?define("isotope/js/layout-modes/vertical",["../layout-mode"],e):"object"==typeof module&&module.exports?module.exports=e(require("../layout-mode")):e(t.Isotope.LayoutMode)}(window,function(t){"use strict";var e=t.create("vertical",{horizontalAlignment:0}),i=e.prototype;return i._resetLayout=function(){this.y=0},i._getItemLayoutPosition=function(t){t.getSize();var e=(this.isotope.size.innerWidth-t.size.outerWidth)*this.options.horizontalAlignment,i=this.y;return this.y+=t.size.outerHeight,{x:e,y:i}},i._getContainerSize=function(){return{height:this.y}},e}),function(t,e){"function"==typeof define&&define.amd?define(["outlayer/outlayer","get-size/get-size","desandro-matches-selector/matches-selector","fizzy-ui-utils/utils","isotope/js/item","isotope/js/layout-mode","isotope/js/layout-modes/masonry","isotope/js/layout-modes/fit-rows","isotope/js/layout-modes/vertical"],function(i,o,n,s,r,a){return e(t,i,o,n,s,r,a)}):"object"==typeof module&&module.exports?module.exports=e(t,require("outlayer"),require("get-size"),require("desandro-matches-selector"),require("fizzy-ui-utils"),require("isotope/js/item"),require("isotope/js/layout-mode"),require("isotope/js/layout-modes/masonry"),require("isotope/js/layout-modes/fit-rows"),require("isotope/js/layout-modes/vertical")):t.Isotope=e(t,t.Outlayer,t.getSize,t.matchesSelector,t.fizzyUIUtils,t.Isotope.Item,t.Isotope.LayoutMode)}(window,function(t,e,i,o,n,s,r){function a(t,e){return function(i,o){for(var n=0;n<t.length;n++){var s=t[n],r=i.sortData[s],a=o.sortData[s];if(r>a||r<a){var u=void 0!==e[s]?e[s]:e,h=u?1:-1;return(r>a?1:-1)*h}}return 0}}var u=t.jQuery,h=String.prototype.trim?function(t){return t.trim()}:function(t){return t.replace(/^\s+|\s+$/g,"")},d=e.create("isotope",{layoutMode:"masonry",isJQueryFiltering:!0,sortAscending:!0});d.Item=s,d.LayoutMode=r;var l=d.prototype;l._create=function(){this.itemGUID=0,this._sorters={},this._getSorters(),e.prototype._create.call(this),this.modes={},this.filteredItems=this.items,this.sortHistory=["original-order"];for(var t in r.modes)this._initLayoutMode(t)},l.reloadItems=function(){this.itemGUID=0,e.prototype.reloadItems.call(this)},l._itemize=function(){for(var t=e.prototype._itemize.apply(this,arguments),i=0;i<t.length;i++){var o=t[i];o.id=this.itemGUID++}return this._updateItemsSortData(t),t},l._initLayoutMode=function(t){var e=r.modes[t],i=this.options[t]||{};this.options[t]=e.options?n.extend(e.options,i):i,this.modes[t]=new e(this)},l.layout=function(){return!this._isLayoutInited&&this._getOption("initLayout")?void this.arrange():void this._layout()},l._layout=function(){var t=this._getIsInstant();this._resetLayout(),this._manageStamps(),this.layoutItems(this.filteredItems,t),this._isLayoutInited=!0},l.arrange=function(t){this.option(t),this._getIsInstant();var e=this._filter(this.items);this.filteredItems=e.matches,this._bindArrangeComplete(),this._isInstant?this._noTransition(this._hideReveal,[e]):this._hideReveal(e),this._sort(),this._layout()},l._init=l.arrange,l._hideReveal=function(t){this.reveal(t.needReveal),this.hide(t.needHide)},l._getIsInstant=function(){var t=this._getOption("layoutInstant"),e=void 0!==t?t:!this._isLayoutInited;return this._isInstant=e,e},l._bindArrangeComplete=function(){function t(){e&&i&&o&&n.dispatchEvent("arrangeComplete",null,[n.filteredItems])}var e,i,o,n=this;this.once("layoutComplete",function(){e=!0,t()}),this.once("hideComplete",function(){i=!0,t()}),this.once("revealComplete",function(){o=!0,t()})},l._filter=function(t){var e=this.options.filter;e=e||"*";for(var i=[],o=[],n=[],s=this._getFilterTest(e),r=0;r<t.length;r++){var a=t[r];if(!a.isIgnored){var u=s(a);u&&i.push(a),u&&a.isHidden?o.push(a):u||a.isHidden||n.push(a)}}return{matches:i,needReveal:o,needHide:n}},l._getFilterTest=function(t){return u&&this.options.isJQueryFiltering?function(e){return u(e.element).is(t)}:"function"==typeof t?function(e){return t(e.element)}:function(e){return o(e.element,t)}},l.updateSortData=function(t){
var e;t?(t=n.makeArray(t),e=this.getItems(t)):e=this.items,this._getSorters(),this._updateItemsSortData(e)},l._getSorters=function(){var t=this.options.getSortData;for(var e in t){var i=t[e];this._sorters[e]=f(i)}},l._updateItemsSortData=function(t){for(var e=t&&t.length,i=0;e&&i<e;i++){var o=t[i];o.updateSortData()}};var f=function(){function t(t){if("string"!=typeof t)return t;var i=h(t).split(" "),o=i[0],n=o.match(/^\[(.+)\]$/),s=n&&n[1],r=e(s,o),a=d.sortDataParsers[i[1]];return t=a?function(t){return t&&a(r(t))}:function(t){return t&&r(t)}}function e(t,e){return t?function(e){return e.getAttribute(t)}:function(t){var i=t.querySelector(e);return i&&i.textContent}}return t}();d.sortDataParsers={parseInt:function(t){return parseInt(t,10)},parseFloat:function(t){return parseFloat(t)}},l._sort=function(){if(this.options.sortBy){var t=n.makeArray(this.options.sortBy);this._getIsSameSortBy(t)||(this.sortHistory=t.concat(this.sortHistory));var e=a(this.sortHistory,this.options.sortAscending);this.filteredItems.sort(e)}},l._getIsSameSortBy=function(t){for(var e=0;e<t.length;e++)if(t[e]!=this.sortHistory[e])return!1;return!0},l._mode=function(){var t=this.options.layoutMode,e=this.modes[t];if(!e)throw new Error("No layout mode: "+t);return e.options=this.options[t],e},l._resetLayout=function(){e.prototype._resetLayout.call(this),this._mode()._resetLayout()},l._getItemLayoutPosition=function(t){return this._mode()._getItemLayoutPosition(t)},l._manageStamp=function(t){this._mode()._manageStamp(t)},l._getContainerSize=function(){return this._mode()._getContainerSize()},l.needsResizeLayout=function(){return this._mode().needsResizeLayout()},l.appended=function(t){var e=this.addItems(t);if(e.length){var i=this._filterRevealAdded(e);this.filteredItems=this.filteredItems.concat(i)}},l.prepended=function(t){var e=this._itemize(t);if(e.length){this._resetLayout(),this._manageStamps();var i=this._filterRevealAdded(e);this.layoutItems(this.filteredItems),this.filteredItems=i.concat(this.filteredItems),this.items=e.concat(this.items)}},l._filterRevealAdded=function(t){var e=this._filter(t);return this.hide(e.needHide),this.reveal(e.matches),this.layoutItems(e.matches,!0),e.matches},l.insert=function(t){var e=this.addItems(t);if(e.length){var i,o,n=e.length;for(i=0;i<n;i++)o=e[i],this.element.appendChild(o.element);var s=this._filter(e).matches;for(i=0;i<n;i++)e[i].isLayoutInstant=!0;for(this.arrange(),i=0;i<n;i++)delete e[i].isLayoutInstant;this.reveal(s)}};var c=l.remove;return l.remove=function(t){t=n.makeArray(t);var e=this.getItems(t);c.call(this,t);for(var i=e&&e.length,o=0;i&&o<i;o++){var s=e[o];n.removeFrom(this.filteredItems,s)}},l.shuffle=function(){for(var t=0;t<this.items.length;t++){var e=this.items[t];e.sortData.random=Math.random()}this.options.sortBy="random",this._sort(),this._layout()},l._noTransition=function(t,e){var i=this.options.transitionDuration;this.options.transitionDuration=0;var o=t.apply(this,e);return this.options.transitionDuration=i,o},l.getFilteredItemElements=function(){return this.filteredItems.map(function(t){return t.element})},d});
/**
 * Main function
 * @public
 */
function Streamium() {

    var $ = jQuery.noConflict();

    // Remove some elements on load
    $(".subscriptio_list_product a").contents().unwrap();
    $(".product-name a").contents().unwrap();
    $(".subscriptio_frontend_items_list_item a").contents().unwrap();
    $(".subscriptio_list_id a").contents().unwrap();
    $(".product-thumbnail").remove();

    var build = {
        setCount: 6,
        lazy: {
            throttle: 500,
            visibleOnly: true,
            effect: "fadeIn",
            effectTime: 500
        },
        slick: {
            adaptiveHeight: true,
            infinite: false, 
            responsive: [{
                breakpoint: 480,
                settings: {
                    appendArrows: false,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }]
        },
        slickSeries: {
            adaptiveHeight: true,
            responsive: [{
                breakpoint: 480,
                settings: {
                    appendArrows: false,
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }]
        },
        init: function(data) {


            // Desktop
            if(this.findBootstrapEnvironment() === 'lg' || this.findBootstrapEnvironment() === 'md'){
                this.setCount = parseInt(streamium_object.tile_count);
                $(window).scroll( $.throttle( 250, this.throttleScroll ) );
            }

            // Tablet
            if(this.findBootstrapEnvironment() === 'sm'){
                this.setCount = 4;
            }

            // Mobile
            if(this.findBootstrapEnvironment() === 'xs'){
                this.setCount = 2;
            }

            // Setup the class
            this.slick.slidesToShow = this.setCount;
            this.slick.slidesToScroll = this.setCount
            this.slickSeries.slidesToShow = (this.setCount+1);
            this.slickSeries.slidesToScroll = (this.setCount+1);

        },
        isOdd: function(num) {
            return num % 2;
        },
        throttleScroll: function(num) {

            if($('body').hasClass('home')){
                
                var scroll = $(window).scrollTop();
                if (scroll >= 50) {
                    $('body').addClass('nav-is-fixed');
                    $('.cd-main-content').css('top','70px');
                } else {
                    $('body').removeClass("nav-is-fixed");
                    $('.cd-main-content').css('top','0px');
                }

            }

        },
        limitWords: function(textToLimit, wordLimit, elip) {

            var finalText = "";
            var text2 = textToLimit.replace(/\s+/g, ' ');
            var text3 = text2.split(' ');
            var numberOfWords = text3.length;
            var i = 0;

            if (numberOfWords > wordLimit) {
                for (i = 0; i < wordLimit; i++)
                    finalText = finalText + " " + text3[i] + " ";

                return finalText + (elip ? elip : "...");
            } else return textToLimit;

        },
        findBootstrapEnvironment: function() {
            var envs = ['xs', 'sm', 'md', 'lg'];

            var $el = $('<div>');
            $el.appendTo($('body'));

            for (var i = envs.length - 1; i >= 0; i--) {
                var env = envs[i];

                $el.addClass('hidden-'+env);
                if ($el.is(':hidden')) {
                    $el.remove();
                    return env;
                }
            }
        },
        isMobile: {
            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
            },
            any: function() {
                return (this.Android() || this.BlackBerry() || this.iOS() || this.Opera() || this.Windows());
            }
        },
        getData: function(data, callback) {

            // add the query object
            data.query = streamium_object.query;
            data.search = streamium_object.search;

            $.ajax({
                type: "post",
                dataType: "json",
                url: streamium_object.ajax_url,
                data: data,
                success: function(response) {

                    callback(response);

                }
            });

        },
        caroTileTemplate: function(tiles, i, type) {

            // Paid
            var html = "";
            if (tiles[i].paid) {
                html = tiles[i].paid.html;
            }

            // Set left and right class fixes
            var classPush = "";
            if (i === 0) {
                classPush = "far-left";
            } else if (i === (streamium_object.tile_count - 1)) {
                classPush = "far-right";
            }
            return '<div class="tile ' + classPush + '" data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '">' +
                '<div class="tile_inner-spacer"><div class="tile_inner tile_inner-home lazy" data-src="' + tiles[i].tileUrl + '">' +
                html +
                    '<div id="tile-white-selected-' + type + '-' + tiles[i].id + '" class="tile-white-selected">' +
                        '<span>Loading...</span>' +
                    '</div>' +
                        '<div class="content">' +
                            '<div class="overlay" style="background-image: url(' + tiles[i].tileUrlExpanded + ');">' +
                            '<div class="overlay-gradient"></div>' +
                                '<a class="play-icon-wrap hidden-xs" href="' + tiles[i].link + '">' +
                                    '<div class="play-icon-wrap-rel">' +
                                        '<div class="play-icon-wrap-rel-ring"></div>' +
                                        '<span class="play-icon-wrap-rel-play">' +
                                            '<i class="fa fa-play fa-1x" aria-hidden="true"></i>' +
                                        '</span>' +
                                    '</div>' +
                                '</a>' +
                                '<div class="overlay-meta hidden-xs">' +
                                    '<span class="top-meta-watched">' + ((tiles[i].progressBar > 0) ? tiles[i].progressBar + "% watched" : "") + '</span>' +
                                    '<h4>' + tiles[i].title + '</h4>' +
                                    '<p>' + tiles[i].text + '</p>' +
                                    '<span class="top-meta-reviews">' + tiles[i].reviews + '</span>' +
                                    '<a data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '" class="tile_meta_more_info home-arrow hidden-xs ani"><i class="icon-streamium" aria-hidden="true"></i></a>' +
                                '</div>' +
                            '</div>' +
                        '<div class="streamium-extra-meta">' + tiles[i].extraMeta + '</div>' +
                    '</div>' +
                '</div>' +
                '</div>' +
                '</div>';

        },
        staticTileTemplate: function(tiles, i, type, changeInd) {

            // Paid
            var html = "";
            if (tiles[i].paid) {
                html = tiles[i].paid.html;
            }

            // Set left and right class fixes
            var classPush = "";
            if (changeInd === 1 || i % (streamium_object.tile_count) === 0) {
                classPush = "far-left";
            } else if (changeInd % (streamium_object.tile_count) === 0) {
                classPush = "far-right";
            }

            return '<div class="tile ' + classPush + '" data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '">' +
                    '<div class="tile_inner-spacer"><div class="tile_inner tile_inner-home lazy" data-src="' + tiles[i].tileUrl + '">' +
                        html +
                        '<div id="tile-white-selected-' + type + '-' + tiles[i].id + '" class="tile-white-selected">' +
	                        '<span>Loading...</span>' +
	                    '</div>' +
                        '<div class="content">' +
                            '<div class="overlay" style="background-image: url(' + tiles[i].tileUrlExpanded + ');">' +
                            '<div class="overlay-gradient"></div>' +
                                '<a class="play-icon-wrap hidden-xs" href="' + tiles[i].link + '">' +
                                    '<div class="play-icon-wrap-rel">' +
                                        '<div class="play-icon-wrap-rel-ring"></div>' +
                                        '<span class="play-icon-wrap-rel-play">' +
                                            '<i class="fa fa-play fa-1x" aria-hidden="true"></i>' +
                                        '</span>' +
                                    '</div>' +
                                '</a>' +
                            '<div class="overlay-meta hidden-xs">' +
                                '<span class="top-meta-watched">' + ((tiles[i].progressBar > 0) ? tiles[i].progressBar + "% watched" : "") + '</span>' +
                                '<h4>' + tiles[i].title + '</h4>' +
                                '<p>' + tiles[i].text + '</p>' +
                                '<span class="top-meta-reviews">' + tiles[i].reviews + '</span>' +
                                '<a data-id="' + tiles[i].id + '" data-nonce="' + tiles[i].nonce + '" data-cat="' + type + '" class="tile_meta_more_info hidden-xs home-arrow ani"><i class="icon-streamium" aria-hidden="true"></i></a>' +
                            '</div>' +
                        '</div>' +
                        '<div class="streamium-extra-meta">' + tiles[i].extraMeta + '</div>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>';

        },
        staticTemplate: function(response, callback) {

            var tiles = response.data;
            var count = response.count;

            if (this.isMobile.any()) {
                streamium_object.tile_count = 2;
            }

            if (tiles.length > 0) {

                var tile = '';
                var cat_count = 0;

                for (i = 0; i < tiles.length; i++) {

                    // Change index
                    var changeInd = (i + 1);
                    var type = 'tax-' + cat_count;
                    if (i % streamium_object.tile_count === 0) {
                        tile += '<div class="container-fluid"><div class="row static-row ' + ((i === 0) ? 'static-row-first' : '') + '">';
                    }

                    tile += this.staticTileTemplate(tiles, i, type, changeInd);

                    var check = false;
                    if (this.isMobile.any()) {
                        if (this.isOdd(i)) {
                            check = true;
                        }
                    } else {
                        if (changeInd % (streamium_object.tile_count) === 0) {
                            check = true;
                        }
                    }

                    if (check || i === (count - 1)) {
                        tile += '</div></div>' + this.expandedTemplate(type);
                        cat_count++;
                    }

                }

            }

            $("#" + response.id).html(tile).promise().done(function(){
                $('.static-row .tile').css('width',(100 / streamium_object.tile_count) + "%");
                callback(true);
            });

        },
        expandedTemplate: function(type) {

            return '<section class="s3bubble-details-full lazy ' + type + '">' +
                        '<div class="s3bubble-details-full-overlay"></div>' +
                            '<div class="container-fluid s3bubble-details-inner-content">' +
                                '<div class="row">' +
                                    '<div class="col-sm-5 col-xs-6 rel">' +
                                        '<div class="synopis-outer">' +
                                            '<div class="synopis-middle">' +
                                                '<div class="synopis-inner">' +
                                                    '<h2 class="synopis"></h2>' +
                                                    '<div class="synopis content"></div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>' +
                                        '<h4 class="series-watched-episode-title"></h4>' +
                                    '</div>' +
                '<div class="col-sm-7 col-xs-6 rel">' +
                    '<a class="play-icon-wrap synopis" href="#">' +
                        '<div class="play-icon-wrap-rel">' +
                            '<div class="play-icon-wrap-rel-ring"></div>' +
                                '<span class="play-icon-wrap-rel-play">' +
                                    '<i class="fa fa-play fa-3x" aria-hidden="true"></i>' +
                                '</span>' +
                            '</div>' +
                        '</a>' +
                        '<a href="#" class="synopis-video-trailer streamium-btns hidden-xs">Watch Trailer</a>' +
                        '<a href="#" class="s3bubble-details-inner-close" data-cat="' + type + '"><i class="fa fa-times" aria-hidden="true"></i></a>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '</section>' +
                '<section id="series-watched-' + type + '" class="series-watched"><div id="series-watched-caro-' + type + '" class="series-watched-caro"></div></div></section>';

        },
        getMovieData: function(data, callback) {

            // Clear up
            $(".series-watched-episode-title").empty();

            var that = this;

            $.ajax({
                url: streamium_object.ajax_url,
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(response) { 

                    if (response.error) {

                        swal({
                            title: streamium_object.swal_error,
                            text: response.message,
                            type: "info",
                            showCancelButton: true,
                            confirmButtonColor: "#d86c2d",
                            confirmButtonText: streamium_object.swal_ok_got_it,
                            closeOnConfirm: true
                        },
                        function() {

                        });

                        return;

                    }

                    // Run some edits for mobile
                    var content = that.limitWords(response.content, 30, '<a class="show-more-content" data-id="' + data.post_id + '">' + streamium_object.read_more + '</a>') + response.meta + response.reviews;
                    if (that.isMobile.any()) {
                        content = that.limitWords(response.content, 8, '<a class="show-more-content" data-id="' + data.post_id + '">' + streamium_object.read_more + '</a>');
                    }

                    // Set the current can to populate
                    var currentCat = "." + response.cat;
                    var currentCatId = "#series-watched-caro-" + response.cat;
                    var currentCatWrapId = "#series-watched-" + response.cat;
                    var tileSelected = '#tile-white-selected-' + response.cat + '-' + data.post_id;
                    var seriesTitle = response.title;

                    // Populate the expanded view 
                    var twidth = $(currentCat).width();
                    var theight = Math.floor(twidth / 21 * 9);
                    $(currentCat).find('h2.synopis').text(response.title);
                    $(currentCat).find('div.synopis').html(content); 
                    $(currentCat).find('a.synopis').attr("href", response.href);
                    $(currentCat).css("background-image", 'url(' + response.bgimage + ')');

                    if (response.trailer === "") {
                    
                        $(currentCat).find('a.synopis-video-trailer').hide();
                    
                    } else {
                        
                        // Populate the button
                        $(currentCat).find('a.synopis-video-trailer').fadeIn().attr("href","#").html(response.trailer_btn_text);
                        $(currentCat).find('a.synopis-video-trailer').data('code', response.preview);
                        
	                    $('.synopis-video-trailer').on('click',function(){

	                    	var preview = $(this).data('code'); // Get the video code
	                    	s3bubble("s3bubble-modal").modal({
								codes: [preview],
					            options : {
					                autoplay : true
					            },
					            meta : {
									backButton : false
								},
								brand: {
							 		controlbar: streamium_object.brand_control, // Controlbar background
							 		icons: streamium_object.brand_icons, // Icon color
							 		sliders: streamium_object.brand_sliders // Slider color
							 	},
					            modal:{
					            	title: '', 
					            	width:960,
					            	showCancelButton:false,
					            	showConfirmButton:false
					            }
					        }, function(player) {

					        });

					        return false;

	                    });

                    }

                    // Animate to the white overlay
                    var vmiddle = Math.round($('.cd-main-header').height());
                    var vtile = Math.round($(tileSelected).outerHeight()) + (4);
                    var voff = Math.round($(currentCat).offset().top);
                    $('html, body').animate({
                        scrollTop: (voff - (vtile + vmiddle))
                    }, 500);

                    // Animate out the details
                    $(currentCat).animate({
                        height: theight
                    }, 250, function() {

                        $(currentCat + ' .s3bubble-details-inner-content').animate({
                            opacity: 1,
                        }, 500, function() {

                        });
 
                        // Initailise the tooltips
                        $('[data-toggle="tooltip"]').tooltip();

                    });

                    var seriesContainer = $('#series-watched-' + response.cat);

                    // Check for series
                    that.getData({
                        action: "streamium_get_dynamic_series_content",
                        postId: data.post_id,
                        nonce: streamium_object.home_api_nonce
                    }, function(response) {

                        callback(true);

                        if (response.error) {
                            console.log("Error: ", response.message);
                            return;
                        }

                        var series = response.data;
                        var serie = '';
                        var id = 0;

                        if (Object.keys(series).length > 0) {

                            for (var a = 0; a < Object.keys(series).length; a++) {

                                var episodes = series[(a + 1)];

                                if (episodes.length > 0) {

                                    for (var i = 0; i < episodes.length; i++) {

                                        var p = id++;
                                        if (that.isMobile.any()) {

                                            serie += '<div class="tile-series"><a class="play-icon-wrap" href="' + episodes[i].link + '?v=' + p + '"><div class="tile_inner" style="background-image: url(' + episodes[i].thumbnails + ');">' +
                                                '<div class="overlay-gradient"></div>' +
                                                '<h4><b>S' + episodes[i].seasons + ':E' + episodes[i].positions + '</b> ' + episodes[i].titles + '</h4>' +
                                                '</div></a></div>';

                                        } else {

                                            serie += '<div class="tile-series"><div class="tile_inner lazy" data-src="' + episodes[i].thumbnails + '">' +
                                                '<div class="overlay-gradient"></div>' +
                                                '<a class="play-icon-wrap" href="' + episodes[i].link + '?v=' + p + '">' +
                                                '<div class="play-icon-wrap-rel">' +
                                                '<div class="play-icon-wrap-rel-ring"></div>' +
                                                '<span class="play-icon-wrap-rel-play">' +
                                                '<i class="fa fa-play fa-1x" aria-hidden="true"></i>' +
                                                '</span>' +
                                                '</div>' +
                                                '</a>' +
                                                '<h4><b>S' + episodes[i].seasons + ':E' + episodes[i].positions + '</b> ' + episodes[i].titles + '</h4>' +
                                                '</div></div>';

                                        }
                                    }

                                }

                            }

                        }

                        $(currentCat).find('h4.series-watched-episode-title').text(seriesTitle + ' Episodes');
                        seriesContainer.fadeIn();

                        if(seriesContainer.hasClass('slick-initialized')) {
                            seriesContainer.slick('unslick');
                            $('.tile-series').remove();
                            seriesContainer.html(serie);
                            seriesContainer.slick(that.slickSeries);
                        }else{
                            seriesContainer.html(serie);
                            seriesContainer.slick(that.slickSeries);
                        } 

                        seriesContainer.on('setPosition', function(event, slick, currentSlide) {
                            $('.lazy').Lazy(that.lazy);
                        });
                        $('.lazy').Lazy(that.lazy);

                    }); // end series

                }

            }); // end jquery

        },
        recentTemplate: function(response, callback) {

            var that = this;

            var tiles = response.data;
            var count = response.count;
            var type = "recent";

            if(tiles.length === 0){
                callback(true);
                return;
            }

            var tile = '';
            for (i = 0; i < tiles.length; i++) {

                // buildTiles located in global.js
                tile += this.caroTileTemplate(tiles, i, type);

            }

            if (count < streamium_object.tile_count) {
                for (c = 0; c < ((streamium_object.tile_count) - count); c++) {
                    tile += '<div class="tile filler"><div class="tile_inner-spacer"><div class="tile_inner"></div></div></div>';
                }
            }

            $("#recently-watched").append('<section class="videos">' + 
                    '<div class="container-fluid">' + 
                        '<div class="row">' + 
                            '<div class="col-sm-12">' + 
                                '<div class="video-header"><h3>' + (streamium_object.continue_watching) + '</h3></div>' + 
                            '</div>' + 
                        '</div>' + 
                        '<div class="carousels" id="' + type + '">' + tile + '</div>' + 
                    '</div>' + 
                '</section>' + 
            this.expandedTemplate(type) + '<div class="container-spacer"></div>');

            var sliderCaro = $("#" + type);
            sliderCaro.slick(this.slick);

            // hide all slides initally
            sliderCaro.find('.slick-prev').addClass('hidden');

            sliderCaro.on('setPosition', function(event, slick, currentSlide) {

                $(this).find(".slick-active:first").addClass("far-left");
                if (slick.slideCount > 6) { // Get the slide count
                    $(this).find(".slick-active:last").addClass("far-right");
                }
                $('.lazy').Lazy(that.lazy);

            });

            sliderCaro.on('afterChange', function(event, slick, currentSlide) {

                $(this).find(".tile").removeClass("far-left").removeClass("far-right");
                $(this).find(".slick-active:first").addClass("far-left");
                $(this).find(".slick-active:last").addClass("far-right");
                if (currentSlide === 0) {
                    $(this).find('.slick-prev').addClass('hidden');
                } else {
                    $(this).find('.slick-prev').removeClass('hidden');
                }
                if (slick.currentSlide >= slick.slideCount - slick.options.slidesToShow) {
                    $(this).find('.slick-next').addClass('hidden');
                } else {
                    $(this).find('.slick-next').removeClass('hidden');
                }

            });

            callback(true);

        },
        customTemplate: function(response, callback) {

            var that = this;
            var cats = response.data;
            if(cats.length === 0){ // Return if no data is present
                callback(true);
                return;
            }

            if (cats.length > 0) {

                for (a = 0; a < cats.length; a++) {

                    var catName = cats[a].meta.name;
                    var catSlug = cats[a].meta.catSlug;
                    var type = cats[a].meta.type;
                    var link = cats[a].meta.link;
                    var home = cats[a].meta.home;
                    var taxUrl = cats[a].meta.taxUrl;
                    var taxTitle = cats[a].meta.title;
                    var count = cats[a].meta.count;

                    var tiles = cats[a].data;
                    var tile = '';
                    for (i = 0; i < tiles.length; i++) {

                        // buildTiles located in global.js
                        tile += this.caroTileTemplate(tiles, i, type);

                    }

                    if (count < streamium_object.tile_count) {
                        for (c = 0; c < ((streamium_object.tile_count) - count); c++) {
                            tile += '<div class="tile filler"><div class="tile_inner-spacer"><div class="tile_inner"></div></div></div>';
                        }
                    }

                    $("#custom-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>' + taxTitle + '</h3><a class="see-all" href="' + link + '">' + (streamium_object.view_all) + ' ' + taxTitle + '</a></div></div></div><div class="carousels" id="' + type + '">' + tile + '</div></div></section>' + this.expandedTemplate(type) + '<div class="container-spacer"></div>');

                    var sliderCaro = $("#" + type);
                    sliderCaro.slick(this.slick);

                    // hide all slides initally
                    sliderCaro.find('.slick-prev').addClass('hidden');

                    sliderCaro.on('setPosition', function(event, slick, currentSlide) {

                        $(this).find(".slick-active:first").addClass("far-left");
                        if (slick.slideCount > 6) { // Get the slide count
                            $(this).find(".slick-active:last").addClass("far-right");
                        }
                        $('.lazy').Lazy(that.lazy);

                    });

                    sliderCaro.on('afterChange', function(event, slick, currentSlide) {

                        $(this).find(".tile").removeClass("far-left").removeClass("far-right");
                        $(this).find(".slick-active:first").addClass("far-left");
                        $(this).find(".slick-active:last").addClass("far-right");
                        if (currentSlide === 0) {
                            $(this).find('.slick-prev').addClass('hidden');
                        } else {
                            $(this).find('.slick-prev').removeClass('hidden');
                        }
                        if (slick.currentSlide >= slick.slideCount - slick.options.slidesToShow) {
                            $(this).find('.slick-next').addClass('hidden');
                        } else {
                            $(this).find('.slick-next').removeClass('hidden');
                        }

                    });

                }

            }

            callback(true);

        },
        homeTemplate: function(response, callback) {

            var that = this;
            var cats = response.data;
            if(cats.length === 0){ // Return if no data is present
                callback(true);
                return;
            }

            if (cats.length > 0) {

                for (a = 0; a < cats.length; a++) {

                    var catParent = cats[a].meta.title;
                    var catName = cats[a].meta.name;
                    var type = cats[a].meta.catSlug;
                    var link = cats[a].meta.link;
                    var home = cats[a].meta.home;
                    var count = cats[a].meta.count;

                    var tiles = cats[a].data;
                    if (tiles.length > 0) {

                        var tile = '';
                        for (var i = 0; i < tiles.length; i++) {

                            // buildTiles located in global.js
                            tile += this.caroTileTemplate(tiles, i, type);

                        }

                        if (count < streamium_object.tile_count) {
                            for (c = 0; c < ((streamium_object.tile_count) - count); c++) {
                                tile += '<div class="tile filler"><div class="tile_inner-spacer"><div class="tile_inner"></div></div></div>';
                            }
                        }

                        $("#home-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>' + catName + '</h3><a class="see-all" href="' + link + '">' + (streamium_object.view_all) + ' ' + catName + '</a></div></div></div><div class="carousels" id="' + type + '">' + tile + '</div></div></section>' + this.expandedTemplate(type) + '<div class="container-spacer"></div>');

                        var sliderCaro = $("#" + type);
                        sliderCaro.slick(this.slick);

                        // hide all slides initally
                        sliderCaro.find('.slick-prev').addClass('hidden');

                        sliderCaro.on('setPosition', function(event, slick, currentSlide) {

                            $(this).find(".slick-active:first").addClass("far-left");
                            if (slick.slideCount > streamium_object.tile_count) { // Get the slide count
                                $(this).find(".slick-active:last").addClass("far-right");
                            }
                            $('.lazy').Lazy(that.lazy);

                        });

                        sliderCaro.on('afterChange', function(event, slick, currentSlide) {

                            $(this).find(".tile").removeClass("far-left").removeClass("far-right");
                            $(this).find(".slick-active:first").addClass("far-left");
                            $(this).find(".slick-active:last").addClass("far-right");
                            if (currentSlide === 0) {
                                $(this).find('.slick-prev').addClass('hidden');
                            } else {
                                $(this).find('.slick-prev').removeClass('hidden');
                            }
                            if (slick.currentSlide >= slick.slideCount - slick.options.slidesToShow) {
                                $(this).find('.slick-next').addClass('hidden');
                            } else {
                                $(this).find('.slick-next').removeClass('hidden');
                            }

                        });

                    }

                }

            }

            callback(true);

        },
        catTemplate: function(response, callback) {

            var that = this;
            var tiles = response.data;
            var type = "type-" + response.meta.id;
            var catParent = response.meta.title;
            var catName = response.meta.name;
            var type = response.meta.catSlug;
            var link = response.meta.link;
            var home = response.meta.home;
            var count = response.meta.count; 

            if(tiles.length === 0){ // Return if no data is present
                callback(true);
                return;
            }

            if (tiles.length > 0) {

                var tile = '';
                for (var i = 0; i < tiles.length; i++) {

                    // buildTiles located in global.js
                    tile += this.caroTileTemplate(tiles, i, type);

                }

                if (count < streamium_object.tile_count) {
                    for (c = 0; c < ((streamium_object.tile_count) - count); c++) {
                        tile += '<div class="tile filler"><div class="tile_inner-spacer"><div class="tile_inner"></div></div></div>';
                    }
                }

                $("#home-watched").append('<section class="videos"><div class="container-fluid"><div class="row"><div class="col-sm-12"><div class="video-header"><h3>' + catParent + ' <i class="fa fa-chevron-right" aria-hidden="true"></i> ' + catName + '</h3><a class="see-all" href="' + link + '">' + (streamium_object.view_all) + ' ' + catName + '</a></div></div></div><div class="carousels" id="' + type + '">' + tile + '</div></div></section>' + this.expandedTemplate(type) + '<div class="container-spacer"></div>');

                var sliderCaro = $("#" + type);
                sliderCaro.slick(this.slick);

                // hide all slides initally
                sliderCaro.find('.slick-prev').addClass('hidden');

                sliderCaro.on('setPosition', function(event, slick, currentSlide) {

                    $(this).find(".slick-active:first").addClass("far-left");
                    if (slick.slideCount > streamium_object.tile_count) { // Get the slide count
                        $(this).find(".slick-active:last").addClass("far-right");
                    }
                    $('.lazy').Lazy(that.lazy);

                });

                sliderCaro.on('afterChange', function(event, slick, currentSlide) {

                    $(this).find(".tile").removeClass("far-left").removeClass("far-right");
                    $(this).find(".slick-active:first").addClass("far-left");
                    $(this).find(".slick-active:last").addClass("far-right");
                    if (currentSlide === 0) {
                        $(this).find('.slick-prev').addClass('hidden');
                    } else {
                        $(this).find('.slick-prev').removeClass('hidden');
                    }
                    if (slick.currentSlide >= slick.slideCount - slick.options.slidesToShow) {
                        $(this).find('.slick-next').addClass('hidden');
                    } else {
                        $(this).find('.slick-next').removeClass('hidden');
                    }

                });

            }

            callback(true);

        }

    }

    return build;
}

jQuery(document).ready(function($) {

    var streamium = Streamium();
    streamium.init();
 
    var tileCount = streamium_object.tile_count;
    var tileWidth = Math.round($('.container-fluid').width() / tileCount);
    var growFactor = 2;
    var moveDistance = (tileWidth / 2);
    var currentCat;
    var view_height = Math.round(($(window).innerWidth() / 21 * 9));

    $('.streamium-slider .slick-slide').height(view_height);

    function resizeVideoJS() {
        view_height = Math.round(($(window).innerWidth() / 21 * 9));
        $('.streamium-slider .slick-slide').height(view_height);
    }

    // Initialise Slider
    $('.streamium-slider').slick({
    	slide: 'div.streamium-slider-div',
        slidesToShow: 1, 
        slidesToScroll: 1,
        dots: false,
        autoplay: parseInt(streamium_object.autoplay_slider),
        autoplaySpeed: 5000,
        pauseOnHover: true,
        adaptiveHeight: true,
        speed: 500,
        fade: true,
        cssEase: 'linear'
    });

    $('.streamium-slider .slick-slide').height(view_height);
    resizeVideoJS();
    window.onresize = resizeVideoJS;

    $('head').append('<style type="text/css">' +

        '.shiftLeftFirst { transform: translate3d(' + (moveDistance * 2) + 'px, 0, 0);}' +
        '.shiftRightFirst { transform: translate3d(-' + (moveDistance * 2) + 'px, 0, 0);}' +
        '.shiftLeft { transform: translate3d(-' + moveDistance + 'px, 0, 0);}' +
        '.shiftRight { transform: translate3d(' + moveDistance + 'px, 0, 0);}' +

        '</style>');
 
    $('[data-toggle="tooltip"]').tooltip();

   	// MUTE OR UNMMUTE MAIN VIDEO::
    $(document).on('click', ".streamium-unmute", function() {

    	var player = $(this).data('pid');
    	var div = $('#' + player).find('div').attr("id");
    	var video = videojs(div);
    	if (video.muted()) {
    		$(this).html('<i class="fa fa-volume-up" aria-hidden="true"></i>');
		    video.muted(false);
		}else{
			$(this).html('<i class="fa fa-volume-off" aria-hidden="true"></i>');
			video.muted(true);
		}

    });

    var clickClass = "home-arrow";
    if (streamium.isMobile.any()) {
        clickClass = "tile";
    }

    $('body').on("click", '.' + clickClass, function(event) {

        event.preventDefault();

        var cat = $(this).data('cat');
        var post_id = $(this).data('id');
        var nonce = $(this).data('nonce');
    
        // Fadeout episodes
        $('.series-watched').fadeOut();
        $('#' + cat + ' .tile-white-selected').hide();
        $('#' + cat + ' .tile-white-selected span').show();
        $('#' + cat + ' .tile-white-selected').removeClass('tile-white-is-selected');

        $(this).closest('.content').hide();
        $(this).closest('.tile_inner-home').trigger('mouseleave');

        // Set the selected block
        $('#tile-white-selected-' + cat + '-' + post_id).show(); 
        $('#tile-white-selected-' + cat + '-' + post_id).addClass("tile-white-is-selected");
 
        streamium.getMovieData({
            action: 'streamium_get_dynamic_content',
            cat: cat,
            post_id: post_id,
            nonce: nonce
        }, function() {
            $('.tile-white-selected span').fadeOut();
        });

        return false; 

    });

    $('body').on("click", '.s3bubble-details-inner-close', function(event) {

        event.preventDefault();

        var div = $(this).parent().parent().parent();
        var cat = $(this).data('cat');

        $(".series-watched").fadeOut();
        $('#' + cat + ' .tile-white-selected').hide();

        div.animate({ 
            opacity: 0,
        }, 250, function() {
            div.parent().animate({
                height: 0
            }, 250, function() {

            });
        });

    }); 

    // Shows the extra meta
    $('body').on("click", '.show-more-content', function(event) {

        event.preventDefault();

        var pid = $(this).data("id");

        // Check for series
        streamium.getData({
            action: "streamium_get_more_content",
            postId: pid,
            nonce: streamium_object.extra_api_nonce
        }, function(response) {

            if (response.error) {
                console.log("ERROR:", response.message);
            }

            // open extra window
            $('.streamium-review-panel-content').html(response.content);
            $('.streamium-review-panel-header h1').html(response.title);
            $('.streamium-review-panel').addClass('is-visible');
            $('html, body').addClass('overflow-hidden');

        });

    });

    $('body').on("click", '.synopis-video-trailer-content', function(event) {
    	
    	var preview = $(this).data('code');
    	s3bubble("s3bubble-modal").modal({
			codes: [preview],
            options : {
                autoplay : true
            },
            meta : {
				backButton : false
			},
			brand: {
		 		controlbar: streamium_object.brand_control, // Controlbar background
		 		icons: streamium_object.brand_icons, // Icon color
		 		sliders: streamium_object.brand_sliders // Slider color
		 	},
            modal:{ 
            	width:960,
            	showCancelButton:false,
            	showConfirmButton:false
            }
        }, function(player) {

        });

        return false;

    });

    if (!streamium.isMobile.any()) {

        $('body').on('mouseenter', '.tile_inner-home', function() {

            // Setup the hover
            if (($(this).find('.tile-white-is-selected').length === 1)) {
                $(this).find('.content').hide();
                return;
            } else {
                $(this).find('.content').show();
            }

            if (!$(this).parent().hasClass('filler')) {

                $(this).addClass('remove-background');
                $(this).find('.streamium-extra-meta').hide();

                var mainTile = $(this).parent().parent();
                if (mainTile.hasClass("far-left")) {
                    mainTile.nextAll().addClass("shiftLeftFirst");
                } else if (mainTile.hasClass("far-right")) {
                    mainTile.prevAll().addClass("shiftRightFirst");
                } else {
                    mainTile.nextAll().addClass("shiftRight");
                    mainTile.prevAll().addClass("shiftLeft");
                }

                $(this).css('transform', 'scale(2)');

            }
        }).on('mouseleave', '.tile_inner-home', function() {

            $(this).removeClass('remove-background');
            $(this).find('.streamium-extra-meta').fadeIn();

            var mainTile = $(this).parent().parent();
            if (mainTile.hasClass("far-left")) {
                mainTile.nextAll().removeClass("shiftLeftFirst");
            } else if (mainTile.hasClass("far-right")) {
                mainTile.prevAll().removeClass("shiftRightFirst");
            } else {
                mainTile.nextAll().removeClass("shiftRight");
                mainTile.prevAll().removeClass("shiftLeft");
            }

            $(this).css('transform', 'scale(1)');

        });

    } 

    // Is homepage
    if (streamium_object.is_home || streamium_object.is_archive && !streamium_object.is_tax && !streamium_object.is_tag) {

        $.when( 
            $.ajax({
                type: "post",
                dataType: "json",
                url: streamium_object.ajax_url,
                data: {
                    action: "recently_watched_api_post",
                    nonce: streamium_object.recently_watched_api_nonce
            }}),
            $.ajax({
                type: "post",
                dataType: "json",
                url: streamium_object.ajax_url,
                data: {
                    action: "custom_api_post",
                    nonce: streamium_object.custom_api_nonce
            }})  
        ).done(function( recent, custom ) {

            if(recent[0].error || custom[0].error){
                console.log('ERROR: ', recent[0].message + ',' + custom[0].message);
                return;
            }

            streamium.recentTemplate(recent[0], function() {

                streamium.customTemplate(custom[0], function() {

                	// Set the margin for the homepage
                    var setMargin = Math.round(($('.carousels').height() / 2));
                    if(!streamium.isMobile.any()){
                        setMargin = (setMargin+30);
                    } 

                    $('.container-spacer').animate({
                        height: setMargin + 'px'
                    }, 50, function() {
                        
                        // Init ui this must be done after the dom and elements are fully set
                        $('.streamium-loading').fadeOut();
                        $('.lazy').Lazy(streamium.lazy);

                    });
 
                });

            });

        });

        updateItems(function(response) {
	 
		    if (response.error) {
		        console.log(response.messsage);
		        return; 
		    }
		 
		    // Success update ui
		 
		});

    }

    function updateItems(callback) {
 		
	    // Setup an interator
	    var iter = 0; 
	 
	    function run() {

	    	$.ajax({
	    		type: "post",
                dataType: "json",
                url: streamium_object.ajax_url,
                data: {
                    action: "home_api_post",
                    index: iter,
                    query: streamium_object.query, // This is needed for custom post types
                    nonce: streamium_object.home_api_nonce
                },
		        success: function(res){

		        	if(!res.error){

		        		streamium.homeTemplate(res, function() {

	                        // Set the margin for the homepage
	                        var setMargin = Math.round(($('.carousels').height() / 2));
	                        if(!streamium.isMobile.any()){
	                            setMargin = (setMargin+30);
	                        }

	                        $('.container-spacer').animate({
	                            height: setMargin + 'px'
	                        }, 50, function() {
	                            
	                            $('.lazy').Lazy(streamium.lazy);

	                            // Preloader
				                $('.loader').fadeOut();
				                $('.loader-mask').delay(250).fadeOut('slow');

	                        });

	                    });

		                iter++;
		                run();

		        	}  


			    },
			    error: function(XMLHttpRequest, textStatus, errorThrown) { 
			        
			        callback({
		                error: true,
		                message: request.responseText
		            });

			    }   
		    }); 
	 
	    }
	 
	    // Start the iterations
	    run();
	 
	}

    // Is taxonomy
    if (streamium_object.is_tax) {

        streamium.getData({
            action: "tax_api_post",
            search: "all",
            nonce: streamium_object.tax_api_nonce
        }, function(response) {

            if (response.error) {
                console.log("Error: ", response.message);
                return;
            }

            response.id = "tax-watched";
            streamium.staticTemplate(response, function() {

                // Init ui this must be done after the dom and elements are fully set
                var setMargin = Math.round($('.tile').height() / 2);
                $('.static-row').css('margin-top', setMargin + 'px');
                $('.lazy').Lazy(streamium.lazy);
                // Preloader
                $('.loader').fadeOut();
                $('.loader-mask').delay(250).fadeOut('slow');

            });

        });

        $(".tax-search-filter").on("click", function(event) {

            event.preventDefault();
            var query = $(this).data("type");
            streamium_object.search = query;
            streamium.getData({
                action: "tax_api_post",
                search: "all",
                nonce: streamium_object.tax_api_nonce
            }, function(response) {

                if (response.error) {
                    console.log("Error: ", response.message);
                    return;
                }
                response.id = "tax-watched";
                streamium.staticTemplate(response, function() {
                    
                    // Init ui this must be done after the dom and elements are fully set
                    var setMargin = Math.round($('.tile').height() / 2);
                    $('.static-row').css('margin-top', setMargin + 'px');
                    $('.lazy').Lazy(streamium.lazy);
                    // Preloader
	                $('.loader').fadeOut();
	                $('.loader-mask').delay(250).fadeOut('slow');

                });

            });

        });

    }

    // Is search
    if (streamium_object.is_search) {

        streamium.getData({
            action: "search_api_post",
            search: "all",
            nonce: streamium_object.search_api_nonce
        }, function(response) {

            if (response.error) {
                console.log("Error: ", response.message);
                $("#search-watched").html('<div class="container-fluid"><div class="row"><div class="col-sm-12"><p>Error: ' + response.message + '...</p></div></div></div>');
                return;
            }

            response.id = "search-watched";
            streamium.staticTemplate(response, function() {

                // Init ui this must be done after the dom and elements are fully set
                var setMargin = Math.round($('.tile').height() / 2);
                $('.static-row').css('margin-top', setMargin + 'px');
                $('.lazy').Lazy(streamium.lazy);
                // Preloader
                $('.loader').fadeOut();
                $('.loader-mask').delay(250).fadeOut('slow');

            });

        });

        $(".search-search-filter").on("click", function(event) {

            event.preventDefault();
            var date = $(this).data("type");
            streamium_object.search = {
                "s": "all",
                "date": date
            };
 
            streamium.getData({
                action: "search_api_post",
                nonce: streamium_object.search_api_nonce
            }, function(response) {

                if (response.error) {
                    console.log("Error: ", response.message);
                    $("#search-watched").html('<div class="container-fluid"><div class="row"><div class="col-sm-12"><p>Error: ' + response.message + '...</p></div></div></div>');
                    return;
                }

                response.id = "search-watched";
                streamium.staticTemplate(response, function() {

                    // Init ui this must be done after the dom and elements are fully set
                    var setMargin = Math.round($('.tile').height() / 2);
                    $('.static-row').css('margin-top', setMargin + 'px');
                    $('.lazy').Lazy(streamium.lazy);
                    // Preloader
	                $('.loader').fadeOut();
	                $('.loader-mask').delay(250).fadeOut('slow');

                });

            });

        });

    }

    // Is tag
    if (streamium_object.is_tag) {

        streamium.getData({
            action: "tag_api_post",
            nonce: streamium_object.tag_api_nonce
        }, function(response) {

            if (response.error) {
                console.log("Error: ", response.message);
                $("#search-watched").html('<div class="container-fluid"><div class="row"><div class="col-sm-12"><p>Error: ' + response.message + '...</p></div></div></div>');
                return;
            }

            response.id = "tag-watched";
            streamium.staticTemplate(response, function() {

                // Init ui this must be done after the dom and elements are fully set
                var setMargin = Math.round($('.tile').height() / 2);
                $('.static-row').css('margin-top', setMargin + 'px');
                $('.lazy').Lazy(streamium.lazy);
               	// Preloader
                $('.loader').fadeOut();
                $('.loader-mask').delay(250).fadeOut('slow');

            });

        });

    }

});