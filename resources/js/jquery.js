$(function () {
  // ============================
  // SPバーガーメニュー
  // ============================
  $('body').removeClass('u-scroll-prevent');
  
  let spnav = $('.js-toggle-sp-nav');
  
  // 背景がスクロールしないように固定する
  $('.js-toggle-sp-menu').on('click', function () {
    $(this).toggleClass('active');
    spnav.toggleClass('active');
    if (spnav.hasClass('active')) {
      scrollPosition = $(window).scrollTop();
      $('body').toggleClass('u-scroll-prevent').css({ top: -scrollPosition });
    } else {
      scrollPosition = $(window).scrollTop();
      $('body').toggleClass('u-scroll-prevent').css({ top: 0 });
      window.scrollTo(0, scrollPosition);
    }
  });
});
