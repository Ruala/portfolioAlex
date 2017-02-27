/*Form*/
(function(){
    function FormController(options) {
        this._submitSelector = options.submitSelector || 'input[type="submit"]';
        this._listenedBlock = options.listenedBlock || 'body';
        this._resetForm = options.resetForm || true;
        this._beforeSend = options.beforeSend || null;
        this._resolve = options.resolve || null;
        this._reject = options.reject || null;
        this._maxFileSize = options.maxFileSize || 2; //MB
    }
    FormController.prototype.init = function () {
        if(!document.querySelector(this._submitSelector)) return;

        $(this._listenedBlock).on('click', this.formListeners.bind(this));

        if($(this._listenedBlock).find('input[type="file"]').length) {
            $(this._listenedBlock).change(this.uploadListener.bind(this));
        }
    };
    FormController.prototype.validateForm = function (form) {
        var vResult = true;
        var passCurr = false;
        var self = this;

        $('input[name!="submit"], textarea', $(form)).each(function () {
            var vVal = $(this).val();
            var requiredField = $(this).attr('required');
            var pattern = '';
            var placeholderMess = '';

            $(this).removeClass('form-fail'); //чистим классы, если остались после прошлого раза
            $(this).removeClass('form-success');


            if (vVal.length === 0 && requiredField) {
                placeholderMess = 'Поле ' + ($(this).attr('data-name') ? '"' + $(this).attr('data-name') + '" ' : '') + 'обязательное!';
                vResult = false;
            } else if ($(this).attr('name') == 'email' && vVal.length) {
                pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

                if (pattern.test($(this).val())) {
                    $(this).addClass('form-success');
                } else {
                    placeholderMess = 'Введите корректный E-mail!';
                    vResult = false;
                }
            } else if ($(this).attr('name') == 'phone' && vVal.length) {
                pattern = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/i;

                if (pattern.test($(this).val())) {
                    $(this).addClass('form-success');
                } else {
                    placeholderMess = 'Введите корректный телефон!';
                    vResult = false;
                }
            } else if ($(this).attr('name') === 'passCurr' && vVal.length) {
                passCurr = this;
            } else if ($(this).attr('name') === 'passNew' && vVal.length) {
                if (vVal === $(passCurr).val()) {
                    $(passCurr).val('').addClass('form-fail').attr('placeholder', 'Новый пароль, не должен совпадать с текущим!');
                    placeholderMess = 'Новый пароль, не должен совпадать с текущим!';
                } else {
                    $(this).addClass('form-success');
                    $(passCurr).addClass('form-success');
                }
            }else if($(this).is('textarea') && vVal.length < 10 && vVal.length > 0  && requiredField) {
                placeholderMess = 'Сообщение слишком короткое!';
                vResult = false;
            } else if (requiredField && vVal.length) {
                $(this).addClass('form-success');
            }

            if (placeholderMess) {
                $(this).attr('data-old-placeholder', $(this).attr('placeholder'));
                $(this).val('').attr('placeholder', placeholderMess).addClass('form-fail');
                placeholderMess = '<span class="form-fail">' + placeholderMess + '</span>';
                self.changeLabel(this, placeholderMess, 'span.placeholder');
            }
        });

        return vResult;
    };
    FormController.prototype.uploadListener = function (e) {
        var elem = e.target;

        if(!elem.matches('input[type="file"]'))  return;

        var size = this.getFileSize(elem);

        if (size < this._maxFileSize * 1024 * 1024) return;

        alert("Файл слишком большой. Размер вашего файла " + (size / 1024 / 1024).toFixed(2) +
            " MB. Загружайте файлы меньше " + this._maxFileSize + "MB.");
        $(elem).val('');
    };
    FormController.prototype.getFileSize = function (input) {
        var file;

        if (typeof ActiveXObject == "function") { // IE
            file = (new ActiveXObject("Scripting.FileSystemObject")).getFile(input.value);
        } else {
            file = input.files[0];
        }

        return file.size;
    };
    FormController.prototype.changeLabel = function (elem, val, insideLabelSelector) {
        var selector = 'label[for="' + $(elem).attr('id') + '"] ' + insideLabelSelector || '';
        var $label = $(selector);

        if ($label.length) {
            $label.each(function () {
                this.innerHTML = val;
            });
        }
    };
    FormController.prototype.resetForms = function (formContainer) {
        var $form;
        var self = this;

        if (formContainer.tagName === 'FORM') {
            $form = $(formContainer);
        } else {
            $form = $('form', $(formContainer));
        }

        $form.each(function () {
            self.resetPlaceholders(this);
            if (self._resetForm) {
                this.reset();
                self.triggerChange(this);
            }
        });
    };
    FormController.prototype.resetPlaceholders = function (inputContainer) {
        var self = this;
        var $input;

        if (inputContainer.tagName === 'INPUT') {
            $input = $(inputContainer);
        } else {
            $input = $('input[name != submit]', $(inputContainer));
        }

        $input.each(function () {
            var name = $(this).attr('name');
            var placeholderMess =  $(this).attr('data-old-placeholder');

            $(this).removeClass('form-success');
            $(this).removeClass('form-fail');

            if (!placeholderMess) return;

            $(this).attr('placeholder', placeholderMess);
            self.changeLabel(this, placeholderMess, 'span.placeholder');
        });
    };
    FormController.prototype.triggerChange = function (inputContainer) {
        var $input = null;

        if (inputContainer.tagName === 'INPUT') {
            $input = $(inputContainer);
        } else {
            $input = $('input[name != submit]', $(inputContainer));
        }

        $input.each(function () {
            $(this).trigger('change');
        });
    };
    FormController.prototype.formListeners = function (e) {
        var elem = e.target;

        if (!elem.matches(this._submitSelector)) return;

        e.preventDefault();

        var form = elem.closest('form');

        if (this.validateForm(form)) {
            this.sendRequest(form, this._resolve, this._reject, this._beforeSend);
        }
    };
    FormController.prototype.sendRequest = function (form, resolve, reject, beforeSend) {
        var formData = $(form).serializeArray(); //собираем все данные из формы
        var self = this;


        if (beforeSend) {
            beforeSend.call(this, formData, form);
        }
        //console.dir(formData);

        this.showPending(form);

        $.ajax({
            type: form.method,
            url: form.action,
            data: $.param(formData),
            success: function (response) {
                //console.log(response);

                if (response) {
                    self.hidePending(form, self.showSuccess.bind(self, form));

                    if (resolve) {
                        resolve.call(self, form, response);
                    }
                } else {
                    self.hidePending(form, self.showError.bind(self, form));

                    if (reject) {
                        reject.call(self, form, response);
                    }
                }

                self.resetForms(form);
            },
            error: function (response) {

                //console.log(response);
                //throw new Error(response.statusText);
                self.hidePending(form, self.showError.bind(self, form));
                self.resetForms(form);

            }
        });
    };
    FormController.prototype.showError = function (form) {
        var $errBlock = $('.err-block', $(form));

        $('.form-success', $(form)).removeClass('form-success');
        $errBlock.fadeIn('normal');

        setTimeout(function () {
            $errBlock.fadeOut('normal');
        }, 10000);
    };
    FormController.prototype.showSuccess = function (form) {
        var $succBlock = $('.succ-block', $(form));

        $('.form-success', $(form)).removeClass('form-success');
        $succBlock.fadeIn('normal');

        setTimeout(function () {
            $succBlock.fadeOut('normal');
        }, 10000);
    };
    FormController.prototype.showPending = function (form) {
        var $pendingBlock = $('.pend-block', $(form));

        $pendingBlock.fadeIn('normal');
    };
    FormController.prototype.hidePending = function (form, callback) {
        var $pendingBlock = $('.pend-block', $(form));

        if (!$pendingBlock[0]) {
            callback();
            return;
        }

        $pendingBlock.fadeOut('normal', 'linear', callback);
    };

    $.fn.formController = function () {
        var options = typeof arguments[0] === 'object' ? arguments[0] : {};
        var controllersArr = [];

        $(this).each(function () {
            if (isElement(this)) {
                options.listenedBlock = this;
            }

            var controller = new FormController(options);
            controller.init();
            controllersArr.push(controller);
        });

        return controllersArr;
    };

    //Returns true if it is a DOM element
    function isElement(o) {
        return (
            typeof HTMLElement === "object" ? o instanceof HTMLElement : //DOM2
            o && typeof o === "object" && o !== null && o.nodeType === 1 && typeof o.nodeName === "string"
        );
    }
})();


$(document).ready(function () {
    /*ScrollToAnchor && mobile menu*/
    (function(){
        /*ScrollToAnchor class*/
        function ScrollToAnchor(options) {
            this._listenedBlock = options.listenedBlock || document.body;
            this._translationElementSelector = options.translation || false;
        }

        ScrollToAnchor.prototype.init = function () {
            $(this._listenedBlock).on('click', this.anchorClickListener.bind(this));
        };
        ScrollToAnchor.prototype.anchorClickListener = function (e) {
            var elem = e.target;
            var anchor = elem.closest('a[href*="#"]:not([data-scroll="disable"])');

            if (!anchor) return;

            var anchorWithHash = anchor.closest('a[href^="#"]');
            var windowPath = window.location.origin + window.location.pathname;
            var anchorPath = anchor.href.slice(0, anchor.href.indexOf('#'));

            if (windowPath === anchorPath) {
                anchorWithHash = anchor;
            }

            if (!anchorWithHash || anchorWithHash.hash.length < 2) return;

            e.preventDefault();

            var target = anchorWithHash.hash;
            var translation = this.getTranslation(anchorWithHash);

            if (!document.querySelector(target)) return;

            this.smoothScroll(target, translation);
        };
        ScrollToAnchor.prototype.getTranslation = function (anchor) {
            var translation = 0;

            if (anchor.hasAttribute('data-translation')) {
                translation = anchor.getAttribute('data-translation');
            } else if (this._translationElementSelector) {
                $(this._translationElementSelector).each(function () {
                    translation += this.offsetHeight;
                });
                //translation = document.querySelector(this._translationElementSelector).offsetHeight;
            }

            return translation;
        };
        ScrollToAnchor.prototype.smoothScroll = function (selector, translation) {
            $("html, body").animate({
                    scrollTop: $(selector).offset().top - (translation || 0)
                },
                500
            );
        };

        /*content scroll*/
        (function(){
            var pageScroll = new ScrollToAnchor({
                listenedBlock: '.page-wrap'
            });
            pageScroll.init();
        })();

        /*mmenu*/
        (function(){
            /*mmenu scroll*/
            var mmenuScroll = new ScrollToAnchor({
                listenedBlock: document.getElementById('#m-menu')
            });


            setupMenu();

            function setupMenu() {
                var $menu = $('nav#m-menu');
                var $openMenuBtn = $('#hamburger');
                var $openMenuBtnWrapper = $('#m-menu-btn-wrapper');
                var isMenuOpen = false;
                var scrollBarWidth = getScrollBarWidth();
                var html = document.documentElement || document.body;

                $menu.mmenu({
                    "extensions": ["fullscreen"],
                    offCanvas: {
                        moveBackground: false,
                        position: "top",
                        zposition: "front"
                    },
                    navbar: false,
                    /*{
                        title: 'Меню' //'Меню'
                    },*/
                    "navbars": [
                        {
                            'content': [
                                '<div class="logo"><a href="/"><img src="images/logo.png" alt="logo"></a></div>'
                            ],
                            'height': 2,
                            "position": "top"
                        },
                        /*{
                            "position": "top"
                        },*/
                        {
                            'content': [
                                '<a href="#call-back" class="btn__callback__mobile" data-role="lightbox"><div class="icon__mobile_big"></div><span>Перезвоните мне!</span></a>'
                             ],
                            'height': 4,
                            "position": "bottom"
                        }
                    ]
                });

                var selector = false;
                $menu.find( 'li > a' ).on(
                    'click',
                    function( e )
                    {
                        selector = this.hash;
                    }
                );

                var api = $menu.data( 'mmenu' );
                api.bind( 'closed',
                    function() {
                        if (selector) {
                            mmenuScroll.smoothScroll(selector);
                            selector = false;
                        }

                        html.style.paddingRight = '';
                        $openMenuBtnWrapper[0].style.right = 0;
                        isMenuOpen = false;
                    }

                );
                $openMenuBtn.on('click',
                    function () {
                        if (isMenuOpen) {
                            api.close();
                            isMenuOpen = false;
                        } else {
                            api.open();
                            html.style.paddingRight = scrollBarWidth + 'px';
                            $openMenuBtnWrapper[0].style.right = scrollBarWidth + 'px';
                            isMenuOpen = true;
                        }
                    });

            }

            function getScrollBarWidth() {
                var div = document.createElement('div');
                var scrollBarWidth = 0;

                $(div).css({
                    'width': '100px',
                    'height': '100px',
                    'overflowY': 'scroll',
                    'visibility': 'hidden'
                });
                document.body.appendChild(div);

                scrollBarWidth = div.offsetWidth - div.clientWidth;

                document.body.removeChild(div);

                return scrollBarWidth;
            }
        })();
    })();

    /*forms*/
    (function () {
        var $form = $('form');
        var $phone = $('input[name="phone"]');

        /*phone mask*/
        $phone.mask("+7 (999) 999-99-99");

        /*form validation and sending request*/
        $form.formController();

    })();

    /*fancybox*/
    (function () {
        var $simpleLightbox = $('[data-role="lightbox"]');

        $simpleLightbox.fancybox({
            padding   : 0,
            margin    : 0,
            tpl: {
                closeBtn : '<span class="btn__close"></span>'
            }
        });
    })();

    /*block visual manipulation*/
    (function () {
        var $slideDown = $('[data-role="slideDown"]');

        $slideDown.on('click', function () {
            var targetSelector = $(this).attr('data-target');

            $(this).fadeOut();
            $(targetSelector).slideDown();
        });
    })();

    /*ScrollUp button*/
    (function(){
        function ScrollTop(tmpl) {
            this._tmpl = tmpl || '<div id="scrollUp"><i class="upButton"></i></div>';
            this._isActive = false;

            this.init();
        }
        ScrollTop.prototype.init = function () {
            this._$btn = $(this._tmpl);
            $('body').append(this._$btn);

            this.scrollBtnToggler();

            this._$btn.on('click', this.scrollTop.bind(this));
            $(window).on('scroll', this.scrollBtnToggler.bind(this));
        };
        ScrollTop.prototype.scrollBtnToggler = function () {
            if ( $(document).scrollTop() > $(window).height() && !this._isActive ) {
                this._$btn.fadeIn({queue : false, duration: 400})
                    .animate({'bottom' : '40px'}, 400);
                this._isActive = true;
            } else if ( $(document).scrollTop() < $(window).height() && this._isActive ) {
                this._$btn.fadeOut({queue : false, duration: 400})
                    .animate({'bottom' : '-20px'}, 400);
                this._isActive = false;
            }
        };
        ScrollTop.prototype.scrollTop = function(){
            $("html, body").animate({scrollTop: 0}, 500);
            return false;
        };

        var scrollTopBtn = new ScrollTop();
    })();
});