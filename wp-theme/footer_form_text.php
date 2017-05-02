<?php

?>
 <footer>
    <div class="container">
      <form action="/" class="form footer__form" method="post">
        <div class="incontainer">
          <h3>Оставьте заявку на расчет стоимости</h3>
        </div>

        <div class="form__box">
          <div class="incontainer">
            <input type="text" name="request_name" placeholder="Имя">
            <textarea name="request_description" placeholder="Опишите ваш проект"></textarea>
          </div>
        </div>

        <div class="form__box">
          <div class="incontainer">
            <input type="text" name="request_phone" placeholder="Телефон" required>
            <input type="text" name="request_email" placeholder="Электронная почта" required>
            <label for="file_1" class="label__file"><span class="icon__addfile"></span>Прикрепить файл</label>
            <input id="file_1" class="hidden" type="file" name="request_file">
            <input type="hidden" name="form_type" value="request"/>
            <input type="hidden" name="request_from" value="<?=$_SERVER['REQUEST_URI'];?>"/>
            <input class="btn__submit" type="submit" value="Рассчитать">
          </div>
        </div>

      </form>

      <div class="footer__contacts">
        <div class="incontainer">
          <?php dynamic_sidebar( 'footer_text' ); ?>          
        </div>
      </div>
    </div><!--container-->
  </footer>