<?php
// Mail handler
if (isset($_POST) && count($_POST)) {
  if (isset($_POST['form_type']) && $_POST['form_type'] == 'request') {

    $message = "Имя: " . addslashes($_POST['request_name']) . "\n\nТелефон: " . addslashes($_POST['request_phone']) . "\n\nEmail: " . addslashes($_POST['request_email']) . "\n\nОписание проекта: " . addslashes($_POST['request_description']) . "\n\Название услуги: " . addslashes($_POST['request_service_name']) . "\n\nСо страницы: " . get_option('siteurl') .  addslashes($_POST['request_from']);

    wp_mail( get_option('admin_email'), "Новая заявка с сайта Talanta.ru!", $message );

    echo '<p style="text-align: center; width: 100%; padding: 40px 0; font-size: 18px; color: green;">Ваша заявка успешно отправлена!</p>';
    exit;
  }
  elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'callback') {

    $message = "Имя: " . addslashes($_POST['callback_name']) . "\n\nТелефон: " . addslashes($_POST['callback_phone']) . "\n\nEmail: " . addslashes($_POST['callback_email']) . "\n\nСо страницы: " . get_option('siteurl') .  addslashes($_POST['callback_from']);

    wp_mail( get_option('admin_email'), "Новая заявка на звонок с сайта Talanta.ru!", $message );

    echo '<p style="text-align: center; width: 100%; padding: 40px 0; font-size: 18px; color: green;">Ваша заявка успешно отправлена!</p>';
    exit;
  }
  else {
    exit;
  }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!--Favicon-->
  <link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/images/favicon.ico" type="image/x-icon">
   <link rel="apple-touch-icon-precomposed" href="<?php echo esc_url( get_template_directory_uri() ); ?>/images/favicon.png"/>

  <!--For improved cross-browser rendering-->
  <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/normalize.min.css">
  <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/plugins/mmenu/jquery.mmenu.all.css">
  <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/plugins/fancybox/jquery.fancybox.min.css">

  <!-- Custom styles CSS -->
  <link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/styles.min.css?v=42">

  <?php wp_head(); ?>

  <meta name='yandex-verification' content='7388115e8f52b0cf' />

</head>
<body <?php body_class(); ?>>