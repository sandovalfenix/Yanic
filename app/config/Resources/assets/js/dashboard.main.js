$(window).on('load', function () {
  // initialization of HSMegaMenu component
  $('.js-mega-menu').HSMegaMenu({
    event: 'hover',
    pageContainer: $('.container'),
    breakpoint: 767.98,
    hideTimeOut: 0
  });

  // initialization of HSMegaMenu component
  $('.js-breadcrumb-menu').HSMegaMenu({
    event: 'hover',
    pageContainer: $('.container'),
    breakpoint: 991.98,
    hideTimeOut: 0
  });

  // initialization of svg injector module
  $.HSCore.components.HSSVGIngector.init('.js-svg-injector');
});

$(document).on('ready', function () {
  // initialization of header
  $.HSCore.components.HSHeader.init($('#header'));

  // initialization of unfold component
  $.HSCore.components.HSUnfold.init($('[data-unfold-target]'), {
    afterOpen: function () {
      $(this).find('input[type="search"]').focus();
    }
  });

  // initialization of malihu scrollbar
  $.HSCore.components.HSMalihuScrollBar.init($('.js-scrollbar'));

  // initialization of forms
  $.HSCore.components.HSFocusState.init();

  // initialization of form validation
  $.HSCore.components.HSValidation.init('.js-validate');

  // initialization of autonomous popups
  $.HSCore.components.HSModalWindow.init('[data-modal-target]', '.js-modal-window', {
    autonomous: true
  });

  // initialization of step form
  $.HSCore.components.HSStepForm.init('.js-step-form');

  // initialization of show animations
  $.HSCore.components.HSShowAnimation.init('.js-animation-link');

  // initialization of range datepicker
  $.HSCore.components.HSRangeDatepicker.init('.js-range-datepicker');

  // initialization of chart pies
  var items = $.HSCore.components.HSChartPie.init('.js-pie');

  // initialization of horizontal progress bars
  var horizontalProgressBars = $.HSCore.components.HSProgressBar.init('.js-hr-progress', {
    direction: 'horizontal',
    indicatorSelector: '.js-hr-progress-bar'
  });

  var verticalProgressBars = $.HSCore.components.HSProgressBar.init('.js-vr-progress', {
    direction: 'vertical',
    indicatorSelector: '.js-vr-progress-bar'
  });

  // initialization of go to
  $.HSCore.components.HSGoTo.init('.js-go-to');
});
