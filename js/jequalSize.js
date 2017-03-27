'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

(function (factory) {
  if (typeof define === 'function' && define.amd) {
    // AMD (Register as an anonymous module)
    define(['jquery'], factory);
  } else if ((typeof exports === 'undefined' ? 'undefined' : _typeof(exports)) === 'object') {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(jQuery);
  }
})(function ($) {
  var EqualSizeController = function () {
    function EqualSizeController(options) {
      _classCallCheck(this, EqualSizeController);

      this.$container = $(options.container);
      this.childrenSelectors = options.children || null;
      this.childrenArr = [];
      this.isActive = false;
    }

    _createClass(EqualSizeController, [{
      key: 'init',
      value: function init() {
        var _this = this;

        if (!this.$container.length) return;

        if ($.isArray(this.childrenSelectors)) {
          this.childrenSelectors.forEach(function (currChildrenSelector) {
            var currChildrenObj = {
              selector: null,
              included: [],
              excluded: []
            };
            currChildrenObj.selector = currChildrenSelector;
            _this.childrenArr.push(currChildrenObj);
          });
        } else if (typeof this.childrenSelectors === 'string') {
          var childrenObj = {
            selector: null,
            included: [],
            excluded: []
          };

          childrenObj.selector = this.childrenSelectors;
          this.childrenArr.push(childrenObj);
        }

        this.processingContainer();

        this._onResize = this._onResize ? this._onResize : this.processingContainer.bind(this);

        $(window).on('resize', this._onResize);
        this.isActive = true;
      }
    }, {
      key: 'stop',
      value: function stop() {
        var _this2 = this;

        if (this.childrenArr.length) {
          this.childrenArr.forEach(function (currChildren) {
            _this2.getChildren(_this2.$container, currChildren).each(function (index, el) {
              el.style.height = '';
            });
          });
        } else {
          this.getChildren(this.$container).each(function (index, el) {
            el.style.height = '';
          });
        }

        $(window).off('resize', this._onResize);
        this.isActive = false;
      }
    }, {
      key: 'run',
      value: function run() {
        if (this.isActive || !this.$container.length) return false;

        if (!this._onResize) {
          this._onResize = this.processingContainer.bind(this);
        }

        this._onResize();
        $(window).on('resize', this._onResize);
        return true;
      }
    }, {
      key: 'oneRun',
      value: function oneRun() {
        this.processingContainer();
      }
    }, {
      key: 'processingContainer',
      value: function processingContainer() {
        var $container = this.$container;
        var children = this.childrenArr;

        if (children.length) {
          var i = children.length - 1;
          var reverse = true;

          while (i < children.length) {
            this.setEqualSize($container, children[i]);

            if (i <= 0) {
              reverse = false;
            }

            if (reverse) {
              i--;
            } else {
              i++;
            }
          }
        } else {
          this.setEqualSize($container);
        }
      }
    }, {
      key: 'setEqualSize',
      value: function setEqualSize(container, children) {
        var height = this.getMaxSize(container, children);
        var $children = this.getChildren(container, children);

        $children.each(function (index, el) {
          el.style.height = height + 'px';
        });
      }
    }, {
      key: 'getMaxSize',
      value: function getMaxSize(container, children) {
        var $container = $(container);
        var containerWidth = $container.width();
        var $containerClone = $container.clone();
        var $innerBlocksClone = this.getChildren($containerClone, children);
        var maxHeight = 0;

        if (containerWidth) {
          $containerClone.css('width', containerWidth + 'px');
        }

        $containerClone.css({
          height: 'auto',
          position: 'absolute',
          top: '-10000px',
          lef: '-10000px',
          visibility: 'hidden'
        }).appendTo($('body'));

        $innerBlocksClone.each(function () {
          var height = $(this).css('height', '').outerHeight();

          if (height < maxHeight) return;

          maxHeight = height;
        });

        $containerClone.remove();

        return maxHeight;
      }
    }, {
      key: 'getChildren',
      value: function getChildren(container, children) {
        var $container = $(container);

        if (!children) {
          return $container.children();
        }

        if (typeof children === 'string' || (typeof children === 'undefined' ? 'undefined' : _typeof(children)) === $) {
          //probably could come just jq object
          return $container.find(children);
        }

        if ((typeof children === 'undefined' ? 'undefined' : _typeof(children)) === 'object' && children.selector) {
          var selector = children.selector;
          var $resultedChildren = $container.find(selector);

          if (children.included && children.included.length) {
            var $includedChildren = $container.find(children.included.join(', '));
            $resultedChildren = $resultedChildren.add($includedChildren);

            /*children.included.forEach((currIncluded) => {
             $resultedChildren = $resultedChildren.add($container.find(currIncluded));
             });*/
          }

          if (children.excluded && children.excluded.length) {
            var excludedChildren = children.excluded.join(', ');
            $resultedChildren = $resultedChildren.not(excludedChildren);
          }

          return $resultedChildren;
        }

        return false;
      }
    }, {
      key: 'addChildren',
      value: function addChildren(children, index) {
        if (typeof children !== 'string' || !this.childrenArr.length) return false;

        index = index || 0;
        var included = this.childrenArr[index].included;
        var excluded = this.childrenArr[index].excluded;
        var includedIndex = included.indexOf(children);
        var excludedIndex = excluded.indexOf(children);

        if (~includedIndex) return true;

        if (~excludedIndex) {
          excluded.splice(excludedIndex, 1);
        }

        included.push(children);
        this.oneRun();
        return true;
      }
    }, {
      key: 'removeChildren',
      value: function removeChildren(children, index) {
        if (typeof children !== 'string' || !this.childrenArr) return false;

        index = index || 0;
        var included = this.childrenArr[index].included;
        var excluded = this.childrenArr[index].excluded;
        var includedIndex = included.indexOf(children);
        var excludedIndex = excluded.indexOf(children);

        if (~excludedIndex) return true;

        this.stop();

        if (~includedIndex) {
          included.splice(includedIndex, 1);
        }

        excluded.push(children);
        this.run();
        return true;
      }
    }, {
      key: 'getSelf',
      value: function getSelf() {
        return this;
      }
    }]);

    return EqualSizeController;
  }();

  $.fn.jequalSize = function () {
    var _ = this;
    var options = arguments[0];
    var args = Array.prototype.slice.call(arguments, 1);

    for (var i = 0; i < _.length; i++) {
      if ((typeof options === 'undefined' ? 'undefined' : _typeof(options)) === 'object') {
        options.container = _;
        _[i].jequalSize = new EqualSizeController(options);
        _[i].jequalSize.init();
      } else if (typeof options === 'undefined') {
        options = {
          container: _
        };
        _[i].jequalSize = new EqualSizeController(options);
        _[i].jequalSize.init();
      } else {
        var result = _[i].jequalSize[options].call(_[i].jequalSize, args);

        if (typeof result !== 'undefined') return result;
      }

      return _;
    }
  };
});

/*equal init*/
jQuery(document).ready(function ($) {
  /*equal size simple*/
  (function () {
    var $equalContainer = $('.js__equal');

    $equalContainer.jequalSize();
  })();

  /*equal size selective*/
  (function () {
    var $equalContainer = $('.js__equal-select');
    var options = {
      children: '.js__equal-child'
    };

    $equalContainer.jequalSize(options);
  })();

  /*equal size selective multiple children*/
  (function () {
    var $equalContainer = $('.js__equal-select-mult');
    var options = {
      children: ['.js__equal-child-1', '.js__equal-child-2', '.js__equal-child-3']
    };

    $equalContainer.jequalSize(options);
  })();

  /*$('body').on('load', function () {
    /!*equal size simple*!/
    (function () {
      var $equalContainer = $('.js__equal');

      $equalContainer.jequalSize();
    })();

    /!*equal size selective*!/
    (function () {
      var $equalContainer = $('.js__equal-select');
      var options = {
        children: '.js__equal-child'
      };

      $equalContainer.jequalSize(options);
    })();

    /!*equal size selective multiple children*!/
    (function () {
      var $equalContainer = $('.js__equal-select-mult');
      var options = {
        children: ['.js__equal-child-1', '.js__equal-child-2', '.js__equal-child-3']
      };

      $equalContainer.jequalSize(options);
    })();
  })*/
});

//# sourceMappingURL=jequalSize.js.map