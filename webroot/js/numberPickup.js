var template1 =
    '<div class="calculator">' +
    '<i class="fa fa-times close" data-class="close"></i>' +
    '<span>1</span>' +
    '<span>2</span>' +
    '<span>3</span>' +
    '<span>4</span>' +
    '<span>5</span>' +
    '<span>6</span>' +
    '<span>7</span>' +
    '<span>8</span>' +
    '<span>9</span>' +
    '<span class="clear operation">削除</span>' +
    '<span>0</span>' +
    '<span class="equal operation">確定</span>' +
    '</div>';


(function($) {
    $.fn.calculator = function(theme) {
        function Controller(el) {
            var self = this;
            el.wrap("<div class='calculator_wrap'></div>");
            el.after(template1);

            this.display = el;
            this.element = el.next();

            if (theme) {
                this.element.addClass(theme);
            }

            this.value = this.load();

            this.stack = null;
            this.stackOp = null;
            this.clearStack = true;

            $("span", this.element).on('click', function() {

                var code = $(this).text().trim();
                if (isNaN(code)) {
                    if (code == "削除") {
                        self.digit;
                    } else if (code.charCodeAt(0) == 247) {
                        self.op = "/";
                    } else if (code == "確定") {
                        $(".calculator").hide();
                    } else {
                        self.op = code;
                    }
                } else {                    
                    self.digit = code;
                }
            });
        }

        Controller.prototype = {
            load: function() {
                return this.display.val() || this.display.text();
            },
            save: function() {
                if (this.display.is("input")) this.display.val(this.value);
                else this.display.text(this.value);
            },
            get v() {
                return this.value;
            },
            set v(val) {
                this.clearStack = false;
                this.value = val;
                if (val.toString().length === 1) {
                    if (val === 0) {
                        this.value = '';
                    }
                }
                this.save();
            },
            get op() {
                return this.stackOp;
            },
            set digit(d) {
                d = parseInt(d);
                if (this.clearStack) return this.v = d;
                return this.v = this.v * 10 + d;
            },
            get digit() {
                if (this.clearStack) return this.v = 0;
                return this.v = Math.floor(this.v / 10);
            }
        };

        var controller;
        this.each(function() {
            controller = new Controller($(this));
            $(this).on('focus', function() {
                $(".calculator").hide();
                $("." + theme).show();
            });
            $("body, .close").click(function() {
                $(".calculator").hide();
            });
            $(".calculator_wrap").click(function(event) {
                event.stopPropagation();
            });
            $(document).keyup(function(e) {
                if (e.keyCode == 27) {
                    $(".calculator").hide();
                }
            });

        });

        return controller;
    };
})(jQuery);
