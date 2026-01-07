jQuery(($) => {
  $("#login").wrap("<div class='helperbox-login'></div>");
  if ('undefined' !== typeof helperboxJS) {
    // check body class
    if (!$('body').hasClass(helperboxJS.bodyClass)) {
      $('body').addClass(helperboxJS.bodyClass)
    }
    // check logiin header url
    const login_headerurl = $('.helperbox-login #login h1 a');
    if (login_headerurl.attr('href') != helperboxJS.login_headerurl) {
      login_headerurl.attr('href', helperboxJS.login_headerurl);
    }
  }
});
