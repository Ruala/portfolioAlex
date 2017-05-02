<div id="call-back" class="pop-up pop-up__call-back">
    <form action="/" class="form pop-up__form" method="post">
      <input class="reverse" type="text" name="callback_name" placeholder="Ваше имя">
      <input class="reverse" type="text" name="callback_phone" placeholder="Ваш номер телефона"
             data-name="номер телефона" required>
      <input class="reverse" type="text" name="callback_email" placeholder="Ваша электронная почта"
             data-name="электронная почта" required>
      <input type="hidden" name="form_type" value="callback"/>
      <input type="hidden" name="callback_from" value="<?=$_SERVER['REQUEST_URI'];?>"/>
      <input class="btn__submit" type="submit" value="Перезвоните мне">
    </form>
  </div>
</div>

<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/jquery-2.1.3.min.js" defer></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/plugins/mmenu/jquery.mmenu.all.min.js" defer></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/plugins/maskedinput/jquery.maskedinput.min.js" defer></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/plugins/fancybox/jquery.fancybox.min.js" defer></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/app.min.js?v=26" defer></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/map.js?v=10" defer></script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter21627733 = new Ya.Metrika({id:21627733,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/21627733" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?php wp_footer(); ?>

</body>
</html>