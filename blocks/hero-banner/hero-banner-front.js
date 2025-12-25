jQuery(function ($) {
  function bannerHoverAnimation(block) {
    $('#move-area').on('mousemove', function (e) {
      var windowWidth = window.innerWidth;
      var windowHeight = window.innerHeight;
      var screenHeight = e.screenY;
      var screenWidth = e.screenX;
      var sxPos = (screenWidth / windowWidth) * 25;
      var syPos = (screenHeight / windowHeight) * 25;
      var $img = $('#skew-image');
      var $img2 = $('#skew-image2');
      var $img3 = $('#skew-image3');

      gsap.to($img, {
        rotationY: 0.1 * syPos,
        rotationX: 0.1 * sxPos,
        rotationZ: '-0.1',
        transformPerspective: 500,
        transformOrigin: 'center center',
      });

      gsap.to($img2, {
        rotationY: 0.15 * syPos,
        rotationX: 0.15 * sxPos,
        rotationZ: '0',
        transformPerspective: 500,
        transformOrigin: 'center center',
      });

      gsap.to($img3, {
        rotationY: 0.2 * syPos,
        rotationX: 0.2 * sxPos,
        rotationZ: '0.1',
        transformPerspective: 500,
        transformOrigin: 'center center',
      });
    });
  }

  if (window.acf) {
    window.acf.addAction('render_block_preview/type=seap/hero', bannerHoverAnimation);
  }

  bannerHoverAnimation();
});
