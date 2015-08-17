﻿/* ==========================================================
 * bootstrap-pivot.js v1.0.0 alpha1
 * http://aozora.github.com/bootmetro/
 * ==========================================================
 * Copyright 2013 Marcello Palmitessa
 * ========================================================== */


!function ($) {

   "use strict";


   /* PIVOT CLASS DEFINITION
    * ========================= */

   var Pivot = function (element, options) {
      this.$element = $(element)
      this.options = options
      this.options.slide && this.slide(this.options.slide)
   }




   Pivot.prototype = {

      to: function (pos) {
         var $active = this.$element.find('> .pivot-items > .pivot-item.active').eq(0)
            , children = $active.parent().children()
            , activePos = children.index($active)
            , that = this

         if (pos > (children.length - 1) || pos < 0)
            return

         if (this.sliding) {
            return this.$element.one('slid', function () {
               that.to(pos)
            })
            return
         }

         if (pos === activePos)
            return

         return this.slide(pos > activePos ? 'next' : 'prev', $(children[pos]))
      }

      , next: function () {
         if (this.sliding) return
         return this.slide('next')
      }

      , prev: function () {
         if (this.sliding) return
         return this.slide('prev')
      }

      , slide: function (type, next) {
         var $active = this.$element.find('> .pivot-items > .pivot-item.active').eq(0)
            , $next = next || $active[type]()
            , direction = type == 'next' ? 'left' : 'right'
            , fallback  = type == 'next' ? 'first' : 'last'
            , that = this
            , e

         this.sliding = true

         $next = $next.length ? $next : this.$element.find('> .pivot-items > .pivot-item')[fallback]()

         e = $.Event('slide', {
            relatedTarget: $next[0]
         })

         if ($next.hasClass('active')) {
            that.sliding = false // this should prevent issue #45
            return
         }

         if ($.support.transition && this.$element.hasClass('slide')) {
            this.$element.trigger(e)
            if (e.isDefaultPrevented())
               return

            $next.addClass(type)
            $next[0].offsetWidth // force reflow
            $active.addClass(direction)
            $next.addClass(direction)
            this.$element.one($.support.transition.end, function () {
               $next.removeClass([type, direction].join(' ')).addClass('active')
               $active.removeClass(['active', direction].join(' '))
               that.sliding = false
               setTimeout(function () { that.$element.trigger('slid') }, 0)
            })
         } else {
            this.$element.trigger(e)
            if (e.isDefaultPrevented())
               return

            $active.removeClass('active')
            $next.addClass('active')
            this.sliding = false
            this.$element.trigger('slid')
         }

         return this
      }

   }


   /* PIVOT PLUGIN DEFINITION
    * ========================== */

   $.fn.pivot = function (option) {
      return this.each(function () {
         var $this = $(this)
            , data = $this.data('pivot')
            , options = $.extend({}, $.fn.pivot.defaults, typeof option == 'object' && option)
            , action = typeof option == 'string' ? option : options.slide
         if (!data)
            $this.data('pivot', (data = new Pivot(this, options)))
         if (typeof option == 'number')
            data.to(option)
         else if (action) data[action]()
      })
   }

   $.fn.pivot.defaults = { }

   $.fn.pivot.Constructor = Pivot



   /* PIVOT DATA-API
    * ================= */

   $(document).on('click.pivot.data-api', '[data-pivot-index]', function (e) {
      var $this = $(this), href
         , $target = $($this.attr('data-target') || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')) //strip for ie7
         , options = $.extend({}, $target.data(), $this.data())
         , $index = parseInt($this.attr('data-pivot-index'), 10);

      $('[data-pivot-index].active').removeClass('active')
      $this.addClass('active')

      $target.pivot($index)
      e.preventDefault()
   })


}(window.jQuery);
