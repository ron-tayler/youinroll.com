/*! jquery-pusher 0.2.3 2013-07-21
https://github.com/salvan13/jquery-pusher
Original author: Antonio Salvati - @salvan13 - salvan13@gmail.com
Licensed under the MIT license
 */
(function(t,n,e){"use strict";function i(n,e){this.element=n,this.options=t.extend({},o,e),this._defaults=o,this._name=a,this.init()}var a="pusher",o={watch:"a",initialPath:n.location.pathname,before:function(t){t()},handler:function(){},after:function(){},fail:function(){n.alert("Failed to load "+this.state.path)},onStateCreation:function(){}};i.prototype={init:function(){var e=this;if(history.pushState){var i=r({path:e.options.initialPath},e.options.onStateCreation);history.replaceState(i,null,i.path),t(e.element).on("click",e.options.watch,function(n){n.preventDefault();var i=r({path:t(this).attr("href"),elem:t(this)},e.options.onStateCreation);s(e,i,!0)}),n.addEventListener("popstate",function(t){s(e,t.state)})}}};var r=function(t,n){var e={};return t=t||{},e.path=t.path,e.time=(new Date).getTime(),n&&n(e,t.elem),e},s=function(n,e,i){if(e){var a={state:e,get:function(t){return u(a.res,t)},updateText:function(n){var e=t(n);this.get(n).each(function(n){var i=t(this).text();e.eq(n).text(i)})},updateHtml:function(n){var e=t(n);this.get(n).each(function(n){var i=t(this).contents();e.eq(n).html(i)})}},o=function(){t.ajax({type:"GET",url:e.path}).done(function(t){a.res=t,i&&history.pushState(e,null,e.path),n.options.handler.apply(a)}).fail(function(){n.options.fail.apply(a)}).always(function(){n.options.after.apply(a)})};n.options.before.apply(a,[o])}},u=function(n,e){var i=t("<root>").html(n),a=i.find(e);return a};t.fn[a]=function(n){t.data(e,"plugin_"+a)||t.data(e,"plugin_"+a,new i(this,n))}})(jQuery,window,document);

jQuery(function($) {
  
  $(".related").pusher({
    handler: function() {
      this.updateText("title");
      this.updateHtml(".video-holder");
    },
	after: function() {
      //hide loading when the page is loaded
     
    $(document).trigger("ready");
	$(document).trigger("resize");




    }
  });
  
});