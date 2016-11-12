<?php
  require_once 'core/init.php';
  if(!is_logged_in()){
    header('Location: login.php');
  }
  include 'include/head.php';
  include 'include/navigation.php';

?>
Administrator HomePage
<?php include 'include/footer.php' ?>
