<?php
    require_once 'core/init.php';
    if(!is_logged_in()){
      login_error_redirect();
    }
    include 'include/head.php';
    include 'include/navigation.php';

    //restore product
    if(isset($_GET['restore'])){
      $id = sanitize($_GET['restore']);
      $db->query("UPDATE products SET deleted = 0 WHERE id = '$id'");
      header('Location: products.php');
    }

    $presults = $db->query("SELECT * FROM products WHERE deleted = 1 ORDER BY title");
?>
<h2 class="text-center">Products</h2>
<!--a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div-->
<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead><th>#</th><th>Products</th><th>Price</th><th>Category</th><!--th>Featured</th--><th>Sold</th></thead>
    <tbody>
        <?PHP while($product = mysqli_fetch_assoc($presults)):
              $childID = $product['categories'];
              $child = mysqli_fetch_assoc($db->query("SELECT * FROM categories WHERE id = '$childID'"));
              $parentID = $child['parent'];
              $parent = mysqli_fetch_assoc($db->query("SELECT * FROM categories WHERE id = '$parentID'"));
              $category = $parent['category'].'-'.$child['category'];
          ?>
            <tr>
                <td>
                    <a href="archived.php?restore=<?= $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
                </td>
                <td><?= $product['title'];?></td>
                <td><?= money($product['price']);?></td>
                <td><?= $category; ?></td>
                <!--td>
                  <a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0'); ?>&id=<?= $product['id'];?>" class="btn btn-xs btn-default">
                    <span class="glyphicon  glyphicon-<?= (($product['featured'] == 1)?'minus':'plus');?>"></span>
                  </a>&nbsp <?= (($product['featured']==1)?'Featured Product':'');?>
                </td-->
                <td>0</td>
            </tr>
        <?PHP endwhile; ?>
    </tbody>
</table>
 <?PHP  include 'include/footer.php'; ?>
