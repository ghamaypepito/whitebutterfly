;(function ($) {

	PPDevice = function (settings) {
		this.id 				= settings.id;
		this.node 				= $('.fl-node-' + this.id);
		this.scrollElement 		= this.node.find(".pp-device-media-screen"),
		this.imageScroll 		= this.node.find('.pp-device-media-screen-inner img'),

		this.trigger 			= settings.imgTriggerOn,
		this.scrollable 		= settings.scrollable,
		this.scrollSpeed 		= settings.scrollSpeed,
		this.direction 			= settings.scrollDir,
		this.reverse 			= settings.reverseDir,
		this.isBuilderActive 	= settings.isBuilderActive,

		this.transformOffset 	= null;
		this._init();
	};

	PPDevice.prototype = {
		_init: function () {
			this._initScroll();
		},

		_initScroll() {
			var self = this;

			if ( 'yes' === self.scrollable && "hover" === self.trigger ) {
				if ( 'yes' === self.reverse ) {
					self.scrollElement.imagesLoaded(function () {
						self._setTransform();
						self._startTransform();
					});
				}
				self.scrollElement.mouseenter(function () {
					self._setTransform();
					self.reverse === 'yes' ? self._endTransform() : self._startTransform();
				});
				self.scrollElement.mouseleave(function () {
					self.reverse === 'yes' ? self._startTransform() : self._endTransform();
				});
			}
		},

		_setTransform() {
			if ( "vertical" == this.direction ) {
				this.transformOffset = this.imageScroll.height() - this.scrollElement.height();
			}
		},

		_startTransform() {
			if ( "vertical" == this.direction ) {
				this.imageScroll.css( "transform", "translateY" + "( -" + this.transformOffset + "px)" );
			} else {
				this.imageScroll.css( "object-position", "right" );
			}
		},

		_endTransform() {
			if ( "vertical" == this.direction ) {
				this.imageScroll.css( "transform", "translateY" + "(0px)" );
			} else {
				this.imageScroll.css( "object-position", "left" );
			}
		},
	}
})(jQuery);