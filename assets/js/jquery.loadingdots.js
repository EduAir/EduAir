(function ($) {

    var count = 0,
        triggers = [];

    $.fn.loadingDots = function (options) {

        var settings = {
            dotColor: '#00B4FF',
            dotSize: '4',
            dotQuantity: 6,
            duration: 1300,
            destination: ''
        };

        if (options) {
            $.extend(settings, options);
        }

        return this.each(function (i, e) {
            var loader = $('<div />', { 'class': 'loader' });
            loader.css('position', 'relative');
            if (settings.destination) {
                $(settings.destination).append(loader);
            } else {
                loader.insertAfter($(e));
            }

            var dotList = $('<ul />', {});
            dotList.css('list-style', 'none');
            var dot = $('<li />').html('&nbsp;').css({
                'display': 'inline',
                'position': 'absolute',
                'height': settings.dotSize + 'px',
                'width': settings.dotSize + 'px',
                'margin-right': settings.dotSize + 'px',
                'display': 'none'
            });
            dotList.css('clear', 'both');
            for (var i = 0; i < settings.dotQuantity; i++) {
                dotList.append(dot.clone());
            }
            loader.append(dotList);
            loader.find('li').css('background-color', settings.dotColor);

            $(e).data('loadingDotsIndex', count);
            triggers[count++] = { loader: loader, shouldLoad: false, duration: settings.duration, quantity: settings.dotQuantity, dotSize: settings.dotSize };
        });
    };

    $.fn.showLoadingDots = function () {
        return this.each(function (i, e) {
            var loader = triggers[$(e).data('loadingDotsIndex')];
            startAnimating(loader);
        });
    };

    $.fn.hideLoadingDots = function () {
        return this.each(function (i, e) {
            var loader = triggers[$(e).data('loadingDotsIndex')];
            stopAnimating(loader);
        });
    };

    function startAnimating(loader) {
        loader.shouldLoad = true;
        animateDots(loader);
        loader.loader.find('ul').fadeIn(500);
    }

    function stopAnimating(loader) {
        loader.shouldLoad = false;
        loader.loader.find('ul').hide();
        loader.loader.find('ul li').stop().css('left', 0);
    }

    $.fn.toggleLoadingDots = function () {
        return this.each(function (i, e) {
            var loader = triggers[$(e).data('loadingDotsIndex')];
            if (loader.shouldLoad) {
                stopAnimating(loader);
            } else {
                startAnimating(loader);
            }
        });
    };

    function animateDots(loader) {
        if (!loader.shouldLoad) {
            loader.loader.find('ul li').css('left', 0);
            return;
        }
        var pageMiddle = 0,
            pageWidth = 0;
        pageWidth = loader.loader.width();
        pageMiddle = pageWidth / 2;

        loader.loader.find('ul li').hide().each(function (i, e) {
            var dot = $(e).css('left', 0);
            setTimeout(function () {
                if (!loader.shouldLoad) {
                    return;
                }
                dot.show()
                    .animate({ left: pageMiddle + (loader.quantity * (loader.dotSize)) - (i * (loader.dotSize * 2)) }, loader.duration, 'swing')
                    .animate({ left: pageWidth }, loader.duration, 'easeInQuad', function () { dot.hide().css('left', 0); });
            }, i * 100);
        });

        setTimeout(function () { animateDots(loader); }, loader.duration * 3);
    }
})(jQuery);