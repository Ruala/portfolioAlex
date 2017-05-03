/*BlockToggler*/

//TODO trottle заменить на debounce
//TODO добавить возможность програмного добавления групп
//TODO добавить возможность делегирования
(function ($) {
    function BlockToggler(options) {
        this._$block = $(options.block);
        this._targetSelector = this._$block.attr('data-bt-target') || this._$block.attr('href');
        this._getTarget = options.getTarget || null; //func, arg: this._$block, return: target
        this._groupName = this._$block.attr('data-bt-group');
        this._closeBtnSelector = options.closeBtnSelector || '.js__bt-close';
        this._isActive = false;
        this._animate = options.animate || 'simple';  // 'none', 'simple', 'slide', 'fade'
        this._onOpen = options.onOpen || null;
        this._onClose = options.onClose || null;
        this._onAfterOpen = options.onAfterOpen || null;
        this._onAfterClose = options.onAfterClose || null;
        this._outerClickClose = options.outerClick || false;
        this.className = {
            initializedToggler: 'js__bt-toggler-initialized',
            initializedTarget: 'js__bt-target-initialized',
            active: 'active'
        };
    }

    BlockToggler.prototype.init = function () {
        var throttledToggler = this.throttle(this.toggler, 405);
        var clickEvent = this._clickEvent = this.isIOS() ? 'touchstart' : 'click';
        var $body = $('body');
        var $target;

        this._openBlockListener = this.openBlockListener.bind(this);
        this._closeGroupListener = this.closeGroupListener.bind(this);
        this._closeBtnListener = this.closeBtnListener.bind(this);
        this._outerClickListener = this.outerClickListener.bind(this);


        if (typeof this._getTarget === 'function') {
            $target = $(this._getTarget(this._$block, this));
        } else {
            $target = $(this._targetSelector);
        }
        this._$target = $target;

        if ((!$target || !$target.length) && this._animate !== 'none') return; //if still no target stop init func


        if ($target) {
            $target
                .addClass(this.className.initializedTarget)
                .find(this._closeBtnSelector)
                .on('click', this._closeBtnListener);

            if (!this.isHidden($target)) {
                this._isActive = true;
                this._$block.addClass(this.className.active);
            }
        }

        if (this._outerClickClose) {
            $body.on(this._clickEvent, this._outerClickListener);
        }

        $body.on({
            'blockOpening': this._openBlockListener,
            'closeGroup': this._closeGroupListener
        });

        this._$block
            .on(clickEvent, throttledToggler.bind(this))
            .addClass(this.className.initializedToggler);
    };
    BlockToggler.prototype.toggler = function (e) {
        var $el = $(e.target);
        var isTarget = !!$el.closest(this._$target).length;

        if (!this.isHidden(this._$target)) {
            this._isActive = true;
        }

        if (this._isActive && isTarget) return;

        e.preventDefault();

        if (this._isActive) {
            this.hideBlock();
        } else {
            this.showBlock();
        }
    };
    BlockToggler.prototype.openBlockListener = function (e, $block, groupName) {
        if (!this._isActive ||
            $block.is(this._$block) ||
            groupName !== this._groupName ||
            groupName === undefined) {
            return;
        }

        this.hideBlock();
    };
    BlockToggler.prototype.closeGroupListener = function (e, groupName) {
        if (!this._isActive ||
            groupName !== this._groupName ||
            groupName === undefined) {
            return;
        }

        this.hideBlock();
    };
    BlockToggler.prototype.outerClickListener = function (e) {
        //console.dir(this);
        if (!this._isActive) return;

        var $el = $(e.target);
        var isOuter = !$el.closest(this._$target.add(this._$block)).length;

        if (!isOuter) return;

        this.hideBlock();
    };
    BlockToggler.prototype.closeBtnListener = function (e) {
        var $el = $(e.target);
        var $currTarget = $el.closest('.' + this.className.initializedTarget);

        if (!$currTarget.is(this._$target)) {
            $el.off('click', this._closeBtnListener);
            return;
        }

        this.hideBlock();
    };
    BlockToggler.prototype.showBlock = function () {
        var $target = this._$target;
        var callback = this.showCallback.bind(this);

        this._$block.addClass('active');
        this._isActive = true;

        if (typeof this._onOpen === 'function') {
            this._onOpen(this);
        }

        this._$block.trigger('blockOpening', [this._$block, this._groupName]);

        switch (this._animate) {
            case 'none':
                callback();
                break;
            case 'simple':
                $target.show();
                callback();
                break;
            case 'slide':
                if (!$target.length) {
                    callback();
                } else {
                    $target.slideDown('normal', 'linear', callback);
                }
                break;
            case 'fade':
                if (!$target.length) {
                    callback();
                } else {
                    $target.fadeIn('normal', 'linear', callback);
                }
                break;
        }
    };
    BlockToggler.prototype.showCallback = function () {
        if (typeof this._onAfterOpen === 'function') {
            this._onAfterOpen(this);
        }

        this._$block.trigger('blockOpened', [this._$block, this._groupName]);

        if (this._outerClickClose) {
            $('body').on(this._clickEvent, this.outerClickListener);
        }
    };
    BlockToggler.prototype.hideBlock = function () {
        var $target = this._$target;
        var callback = this.hideCallback.bind(this);

        this._$block.removeClass('active');
        this._isActive = false;

        if (typeof this._onClose === 'function') {
            this._onClose(this);
        }

        this._$block.trigger('blockClosing', [this._$block, this._groupName]);

        switch (this._animate) {
            case 'none':
                callback();
                break;
            case 'simple':
                $target.hide();
                callback();
                break;
            case 'slide':
                $target.slideUp('normal', 'linear', callback);
                break;
            case 'fade':
                $target.fadeOut('normal', 'linear', callback);
                break;
        }
    };
    BlockToggler.prototype.hideCallback = function () {

        if (typeof this._onAfterClose === 'function') {
            this._onAfterClose(this);
        }

        this._$block.trigger('blockClosed', [this._$block, this._groupName]);

        if (this._outerClickClose) {
            $('body').off(this._clickEvent, this.outerClickListener);
        }
    };
    BlockToggler.prototype.throttle = function (func, ms) {

        var isThrottled = false,
            savedArgs,
            savedThis;

        function wrapper() {

            if (isThrottled) { // (2)
                savedArgs = arguments;
                savedThis = this;
                return;
            }

            func.apply(this, arguments); // (1)

            isThrottled = true;

            setTimeout(function () {
                isThrottled = false; // (3)
                if (savedArgs) {
                    wrapper.apply(savedThis, savedArgs);
                    savedArgs = savedThis = null;
                }
            }, ms);
        }

        return wrapper;
    };
    BlockToggler.prototype.isIOS = function () {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    };
    BlockToggler.prototype.isHidden = function (el) {
        var $el = $(el);

        return $el.is(':hidden') ||
            $el.css('visibility') === 'hidden' ||
            +$el.css('opacity') === 0;
    };

    $.fn.blockToggler = function () {
        var options = typeof arguments[0] === 'object' ? arguments[0] : {};

        $(this).each(function () {
            options.block = this;

            var currBlockToggler = new BlockToggler(options);
            currBlockToggler.init();
        });
    }
})(jQuery);