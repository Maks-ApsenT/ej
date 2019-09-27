/*!
 * jQuery blockUI plugin
 * Version 2.42 (11-MAY-2012)
 * @requires jQuery v1.2.3 or later
 *
 * Examples at: http://malsup.com/jquery/block/
 * Copyright (c) 2007-2010 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Thanks to Amir-Hossein Sobhi for some excellent contributions!
 */

;(function() {

	function setup($) {

		$.fn._fadeIn = $.fn.fadeIn;

		var noOp = function() {};

		// this bit is to ensure we don't call setExpression when we shouldn't (with extra muscle to handle
		// retarded userAgent strings on Vista)
		var mode = document.documentMode || 0;
		var setExpr = $.browser.msie && (($.browser.version < 8 && !mode) || mode < 8);
		var ie6 = $.browser.msie && /MSIE 6.0/.test(navigator.userAgent) && !mode;

		// global $ methods for blocking/unblocking the entire page
		$.blockUI   = function(opts) { install(window, opts); };
		$.unblockUI = function(opts) { remove(window, opts); };

		// convenience method for quick growl-like notifications  (http://www.google.com/search?q=growl)
		$.growlUI = function(title, message, timeout, onClose) {
			var $m = $('<div class="growlUI"></div>');
			if (title) $m.append('<h1>'+title+'</h1>');
			if (message) $m.append('<h2>'+message+'</h2>');
			if (timeout == undefined) timeout = 3000;
			$.blockUI({
				message: $m, fadeIn: 700, fadeOut: 1000, centerY: false,
				timeout: timeout, showOverlay: false,
				onUnblock: onClose,
				css: $.blockUI.defaults.growlCSS
			});
		};

		// plugin method for blocking element content
		$.fn.block = function(opts) {
			var fullOpts = $.extend({}, $.blockUI.defaults, opts || {});
			this.each(function() {
				var $el = $(this);
				if (fullOpts.ignoreIfBlocked && $el.data('blockUI.isBlocked'))
					return;
				$el.unblock({ fadeOut: 0 });
			});

			return this.each(function() {
				if ($.css(this,'position') == 'static')
					this.style.position = 'relative';
				if ($.browser.msie)
					this.style.zoom = 1; // force 'hasLayout'
				install(this, opts);
			});
		};

		// plugin method for unblocking element content
		$.fn.unblock = function(opts) {
			return this.each(function() {
				remove(this, opts);
			});
		};

		$.blockUI.version = 2.42; // 2nd generation blocking at no extra cost!

		// override these in your code to change the default behavior and style
		$.blockUI.defaults = {
			// message displayed when blocking (use null for no message)
			message:  ' ',

			title: null,	  // title string; only used when theme == true
			draggable: true,  // only used when theme == true (requires jquery-ui.js to be loaded)

			theme: false, // set to true to use with jQuery UI themes

			// styles for the message when blocking; if you wish to disable
			// these and use an external stylesheet then do this in your code:
			// $.blockUI.defaults.css = {};
			css: {
				padding:	0,
				margin:		0,
				textAlign:	'center',
				color:		'#000',
				cursor:		'wait',
				border: "0 none",
				backgroundImage: "url('data:image/gif;base64,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')",
				backgroundRepeat: "no-repeat",
				backgroundPosition: "50% 50%",
				backgroundColor: "transparent",
				width: "100%",
				height: "100%",
				top: "0",
				left: "0"
			},

			// minimal style set used when themes are used
			themedCSS: {
				width:	'100%',
				top:	'0',
				left:	'0'
			},

			// styles for the overlay
			overlayCSS:  {
				backgroundColor: "#fff",
				opacity: 0.7
			},

			// styles applied when using $.growlUI
			growlCSS: {
				width:  	'350px',
				top:		'10px',
				left:   	'',
				right:  	'10px',
				border: 	'none',
				padding:	'5px',
				opacity:	0.6,
				cursor: 	'default',
				color:		'#fff',
				backgroundColor: '#000',
				'-webkit-border-radius': '10px',
				'-moz-border-radius':	 '10px',
				'border-radius': 		 '10px'
			},

			// IE issues: 'about:blank' fails on HTTPS and javascript:false is s-l-o-w
			// (hat tip to Jorge H. N. de Vasconcelos)
			iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank',

			// force usage of iframe in non-IE browsers (handy for blocking applets)
			forceIframe: false,

			// z-index for the blocking overlay
			baseZ: 1000,

			// set these to true to have the message automatically centered
			centerX: true, // <-- only effects element blocking (page block controlled via css above)
			centerY: true,

			// allow body element to be stetched in ie6; this makes blocking look better
			// on "short" pages.  disable if you wish to prevent changes to the body height
			allowBodyStretch: true,

			// enable if you want key and mouse events to be disabled for content that is blocked
			bindEvents: true,

			// be default blockUI will supress tab navigation from leaving blocking content
			// (if bindEvents is true)
			constrainTabKey: true,

			// fadeIn time in millis; set to 0 to disable fadeIn on block
			fadeIn:  100,

			// fadeOut time in millis; set to 0 to disable fadeOut on unblock
			fadeOut:  100,

			// time in millis to wait before auto-unblocking; set to 0 to disable auto-unblock
			timeout: 0,

			// disable if you don't want to show the overlay
			showOverlay: true,

			// if true, focus will be placed in the first available input field when
			// page blocking
			focusInput: true,

			// suppresses the use of overlay styles on FF/Linux (due to performance issues with opacity)
			applyPlatformOpacityRules: true,

			// callback method invoked when fadeIn has completed and blocking message is visible
			onBlock: null,

			// callback method invoked when unblocking has completed; the callback is
			// passed the element that has been unblocked (which is the window object for page
			// blocks) and the options that were passed to the unblock call:
			//	 onUnblock(element, options)
			onUnblock: null,

			// don't ask; if you really must know: http://groups.google.com/group/jquery-en/browse_thread/thread/36640a8730503595/2f6a79a77a78e493#2f6a79a77a78e493
			quirksmodeOffsetHack: 4,

			// class name of the message block
			blockMsgClass: 'blockMsg',

			// if it is already blocked, then ignore it (don't unblock and reblock)
			ignoreIfBlocked: false
		};

		// private data and functions follow...

		var pageBlock = null;
		var pageBlockEls = [];

		function install(el, opts) {
			var css, themedCSS;
			var full = (el == window);
			var msg = (opts && opts.message !== undefined ? opts.message : undefined);
			opts = $.extend({}, $.blockUI.defaults, opts || {});

			if (opts.ignoreIfBlocked && $(el).data('blockUI.isBlocked'))
				return;

			opts.overlayCSS = $.extend({}, $.blockUI.defaults.overlayCSS, opts.overlayCSS || {});
			css = $.extend({}, $.blockUI.defaults.css, opts.css || {});
			themedCSS = $.extend({}, $.blockUI.defaults.themedCSS, opts.themedCSS || {});
			msg = msg === undefined ? opts.message : msg;

			// remove the current block (if there is one)
			if (full && pageBlock)
				remove(window, {fadeOut:0});

			// if an existing element is being used as the blocking content then we capture
			// its current place in the DOM (and current display style) so we can restore
			// it when we unblock
			if (msg && typeof msg != 'string' && (msg.parentNode || msg.jquery)) {
				var node = msg.jquery ? msg[0] : msg;
				var data = {};
				$(el).data('blockUI.history', data);
				data.el = node;
				data.parent = node.parentNode;
				data.display = node.style.display;
				data.position = node.style.position;
				if (data.parent)
					data.parent.removeChild(node);
			}

			$(el).data('blockUI.onUnblock', opts.onUnblock);
			var z = opts.baseZ;

			// blockUI uses 3 layers for blocking, for simplicity they are all used on every platform;
			// layer1 is the iframe layer which is used to supress bleed through of underlying content
			// layer2 is the overlay layer which has opacity and a wait cursor (by default)
			// layer3 is the message content that is displayed while blocking

			var lyr1 = ($.browser.msie || opts.forceIframe)
				? $('<iframe class="blockUI" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+opts.iframeSrc+'"></iframe>')
				: $('<div class="blockUI" style="display:none"></div>');

			var lyr2 = opts.theme
				? $('<div class="blockUI blockOverlay ui-widget-overlay" style="z-index:'+ (z++) +';display:none"></div>')
				: $('<div class="blockUI blockOverlay" style="z-index:'+ (z++) +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>');

			var lyr3, s;
			if (opts.theme && full) {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:fixed">' +
						'<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>' +
						'<div class="ui-widget-content ui-dialog-content"></div>' +
					'</div>';
			}
			else if (opts.theme) {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement ui-dialog ui-widget ui-corner-all" style="z-index:'+(z+10)+';display:none;position:absolute">' +
						'<div class="ui-widget-header ui-dialog-titlebar ui-corner-all blockTitle">'+(opts.title || '&nbsp;')+'</div>' +
						'<div class="ui-widget-content ui-dialog-content"></div>' +
					'</div>';
			}
			else if (full) {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockPage" style="z-index:'+(z+10)+';display:none;position:fixed"></div>';
			}
			else {
				s = '<div class="blockUI ' + opts.blockMsgClass + ' blockElement" style="z-index:'+(z+10)+';display:none;position:absolute"></div>';
			}
			lyr3 = $(s);

			// if we have a message, style it
			if (msg) {
				if (opts.theme) {
					lyr3.css(themedCSS);
					lyr3.addClass('ui-widget-content');
				}
				else
					lyr3.css(css);
			}

			// style the overlay
			if (!opts.theme && (!opts.applyPlatformOpacityRules || !($.browser.mozilla && /Linux/.test(navigator.platform))))
				lyr2.css(opts.overlayCSS);
			lyr2.css('position', full ? 'fixed' : 'absolute');

			// make iframe layer transparent in IE
			if ($.browser.msie || opts.forceIframe)
				lyr1.css('opacity',0.0);

			//$([lyr1[0],lyr2[0],lyr3[0]]).appendTo(full ? 'body' : el);
			var layers = [lyr1,lyr2,lyr3], $par = full ? $('body') : $(el);
			$.each(layers, function() {
				this.appendTo($par);
			});

			if (opts.theme && opts.draggable && $.fn.draggable) {
				lyr3.draggable({
					handle: '.ui-dialog-titlebar',
					cancel: 'li'
				});
			}

			// ie7 must use absolute positioning in quirks mode and to account for activex issues (when scrolling)
			var expr = setExpr && (!$.boxModel || $('object,embed', full ? null : el).length > 0);
			if (ie6 || expr) {
				// give body 100% height
				if (full && opts.allowBodyStretch && $.boxModel)
					$('html,body').css('height','100%');

				// fix ie6 issue when blocked element has a border width
				if ((ie6 || !$.boxModel) && !full) {
					var t = sz(el,'borderTopWidth'), l = sz(el,'borderLeftWidth');
					var fixT = t ? '(0 - '+t+')' : 0;
					var fixL = l ? '(0 - '+l+')' : 0;
				}

				// simulate fixed position
				$.each([lyr1,lyr2,lyr3], function(i,o) {
					var s = o[0].style;
					s.position = 'absolute';
					if (i < 2) {
						full ? s.setExpression('height','Math.max(document.body.scrollHeight, document.body.offsetHeight) - (jQuery.boxModel?0:'+opts.quirksmodeOffsetHack+') + "px"')
							 : s.setExpression('height','this.parentNode.offsetHeight + "px"');
						full ? s.setExpression('width','jQuery.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"')
							 : s.setExpression('width','this.parentNode.offsetWidth + "px"');
						if (fixL) s.setExpression('left', fixL);
						if (fixT) s.setExpression('top', fixT);
					}
					else if (opts.centerY) {
						if (full) s.setExpression('top','(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"');
						s.marginTop = 0;
					}
					else if (!opts.centerY && full) {
						var top = (opts.css && opts.css.top) ? parseInt(opts.css.top) : 0;
						var expression = '((document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + '+top+') + "px"';
						s.setExpression('top',expression);
					}
				});
			}

			// show the message
			if (msg) {
				if (opts.theme)
					lyr3.find('.ui-widget-content').append(msg);
				else
					lyr3.append(msg);
				if (msg.jquery || msg.nodeType)
					$(msg).show();
			}

			if (($.browser.msie || opts.forceIframe) && opts.showOverlay)
				lyr1.show(); // opacity is zero
			if (opts.fadeIn) {
				var cb = opts.onBlock ? opts.onBlock : noOp;
				var cb1 = (opts.showOverlay && !msg) ? cb : noOp;
				var cb2 = msg ? cb : noOp;
				if (opts.showOverlay)
					lyr2._fadeIn(opts.fadeIn, cb1);
				if (msg)
					lyr3._fadeIn(opts.fadeIn, cb2);
			}
			else {
				if (opts.showOverlay)
					lyr2.show();
				if (msg)
					lyr3.show();
				if (opts.onBlock)
					opts.onBlock();
			}

			// bind key and mouse events
			bind(1, el, opts);

			if (full) {
				pageBlock = lyr3[0];
				pageBlockEls = $(':input:enabled:visible',pageBlock);
				if (opts.focusInput)
					setTimeout(focus, 20);
			}
			else
				center(lyr3[0], opts.centerX, opts.centerY);

			if (opts.timeout) {
				// auto-unblock
				var to = setTimeout(function() {
					full ? $.unblockUI(opts) : $(el).unblock(opts);
				}, opts.timeout);
				$(el).data('blockUI.timeout', to);
			}
		};

		// remove the block
		function remove(el, opts) {
			var full = (el == window);
			var $el = $(el);
			var data = $el.data('blockUI.history');
			var to = $el.data('blockUI.timeout');
			if (to) {
				clearTimeout(to);
				$el.removeData('blockUI.timeout');
			}
			opts = $.extend({}, $.blockUI.defaults, opts || {});
			bind(0, el, opts); // unbind events

			if (opts.onUnblock === null) {
				opts.onUnblock = $el.data('blockUI.onUnblock');
				$el.removeData('blockUI.onUnblock');
			}

			var els;
			if (full) // crazy selector to handle odd field errors in ie6/7
				els = $('body').children().filter('.blockUI').add('body > .blockUI');
			else
				els = $('.blockUI', el);

			if (full)
				pageBlock = pageBlockEls = null;

			if (opts.fadeOut) {
				els.fadeOut(opts.fadeOut);
				setTimeout(function() { reset(els,data,opts,el); }, opts.fadeOut);
			}
			else
				reset(els, data, opts, el);
		};

		// move blocking element back into the DOM where it started
		function reset(els,data,opts,el) {
			els.each(function(i,o) {
				// remove via DOM calls so we don't lose event handlers
				if (this.parentNode)
					this.parentNode.removeChild(this);
			});

			if (data && data.el) {
				data.el.style.display = data.display;
				data.el.style.position = data.position;
				if (data.parent)
					data.parent.appendChild(data.el);
				$(el).removeData('blockUI.history');
			}

			if (typeof opts.onUnblock == 'function')
				opts.onUnblock(el,opts);
		};

		// bind/unbind the handler
		function bind(b, el, opts) {
			var full = el == window, $el = $(el);

			// don't bother unbinding if there is nothing to unbind
			if (!b && (full && !pageBlock || !full && !$el.data('blockUI.isBlocked')))
				return;

			$el.data('blockUI.isBlocked', b);

			// don't bind events when overlay is not in use or if bindEvents is false
			if (!opts.bindEvents || (b && !opts.showOverlay))
				return;

			// bind anchors and inputs for mouse and key events
			var events = 'mousedown mouseup keydown keypress';
			b ? $(document).bind(events, opts, handler) : $(document).unbind(events, handler);

		// former impl...
		//	   var $e = $('a,:input');
		//	   b ? $e.bind(events, opts, handler) : $e.unbind(events, handler);
		};

		// event handler to suppress keyboard/mouse events when blocking
		function handler(e) {
			// allow tab navigation (conditionally)
			if (e.keyCode && e.keyCode == 9) {
				if (pageBlock && e.data.constrainTabKey) {
					var els = pageBlockEls;
					var fwd = !e.shiftKey && e.target === els[els.length-1];
					var back = e.shiftKey && e.target === els[0];
					if (fwd || back) {
						setTimeout(function(){focus(back)},10);
						return false;
					}
				}
			}
			var opts = e.data;
			// allow events within the message content
			if ($(e.target).parents('div.' + opts.blockMsgClass).length > 0)
				return true;

			// allow events for content that is not being blocked
			return $(e.target).parents().children().filter('div.blockUI').length == 0;
		};

		function focus(back) {
			if (!pageBlockEls)
				return;
			var e = pageBlockEls[back===true ? pageBlockEls.length-1 : 0];
			if (e)
				e.focus();
		};

		function center(el, x, y) {
			var p = el.parentNode, s = el.style;
			var l = ((p.offsetWidth - el.offsetWidth)/2) - sz(p,'borderLeftWidth');
			var t = ((p.offsetHeight - el.offsetHeight)/2) - sz(p,'borderTopWidth');
			if (x) s.left = l > 0 ? (l+'px') : '0';
			if (y) s.top  = t > 0 ? (t+'px') : '0';
		};

		function sz(el, p) {
			return parseInt($.css(el,p))||0;
		};

	};


	if (typeof define === 'function' && define.amd && define.amd.jQuery) {
		define(['jquery'], setup);
	} else {
		setup(jQuery);
	}

})();
