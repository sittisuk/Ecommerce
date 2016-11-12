<?PHP
    require_once 'core_parser/init.php';
    $parentID = (int)$_POST['parentID'];
    $selected = sanitize($_POST['selected']);
    $childQuery = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");

    ob_start();
?>
      <option value=""></option>
      <?php while($child = mysqli_fetch_assoc($childQuery)):?>
          <option value="<?=$child['id'];?>"<?= (($selected == $child['id'])?' selected': '')?>><?= $child['category'];?></option>
      <?php endwhile;?>
<?PHP
    echo ob_get_clean();
?>
