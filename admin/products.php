<?php
    require_once 'core/init.php';
    if(!is_logged_in()){
      login_error_redirect();
    }
    include 'include/head.php';
    include 'include/navigation.php';

    //delete Product
    if(isset($_GET['delete'])){
      $id = sanitize($_GET['delete']);
      $db->query("UPDATE products SET deleted = 1, featured = 0 WHERE id = '$id'");
      header('Location: products.php');
    }

    $dbpath = '';
    if(isset($_GET['add']) || isset($_GET['edit'])){
		$brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
		$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
		$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
		$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
		$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
		$category = ((isset($_POST['child'])) && !empty($_POST['child'])?sanitize($_POST['child']):'');
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
    $sizes = rtrim($sizes,',');
    $saved_image = '';

		// edit data for database
		// check
		if(isset($_GET['edit'])){
			$edit_id = (int)$_GET['edit'];
			$productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
			$product = mysqli_fetch_assoc($productResults);
      if(isset($_GET['delete_image'])){
        $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
        unlink($image_url);
        $db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
        header('Location: products.php?edit='.$edit_id);
      }
			$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
			$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
			$brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$product['brand']);
			$parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
			$parentResult = mysqli_fetch_assoc($parentQ);
			$parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
      $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
      $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$product['list_price']);
      $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$product['description']);
      $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):$product['sizes']);
      $sizes = rtrim($sizes,',');
      $saved_image = (($product['image'] != '')?$product['image']: '');
      $dbpath = $saved_image;
		}
    if(!empty($sizes)) {
      $sizeString = sanitize($sizes);
      $sizeString = rtrim($sizeString,',');
      $sizesArray = explode(',',$sizeString);
      $sArray = array();
      $qArray = array();
      foreach ($sizesArray as $ss) {
        $s = explode(':', $ss);
        $sArray[] = $s[0];
        $qArray[] = $s[1];
      }
    }else{$sizesArray = array();}

		if($_POST){
		  $errors = array();
			$required = array('title', 'brand', 'price', 'parent', 'child', 'sizes');
			foreach ($required as $field) {
				if($_POST[$field] == ''){
					$errors[] = 'All Fields With and Astrisk are required.';
					break;
				}
			}


			if(!empty($_FILES)){
				//var_dump($_FILES);
				$photo = $_FILES['photo'];
				$name = $photo['name'];
				$nameArray = explode('.',$name);
				$fileName = $nameArray[0];
				$fileExt = $nameArray[1];
				$mime = explode('/',$photo['type']);
				$mimeType = $mime[0];
				$mimeExt = $mime[1];
				$tmpLoc = $photo['tmp_name'];
				$fileSize = $photo['size'];
				$allowed = array('png','jpg','jpeg','gif');
				$uploadName = md5(microtime()).'.'.$fileExt;
				$uploadPath = '../image/products/'.$uploadName;
				$dbpath = 'image/products/'.$uploadName;
				if($mimeType != 'image'){
				   $errors[] = 'The file must be an image.';
				}
				if (!in_array($fileExt, $allowed)) {
					$errors[] = 'The file extension must be a png, jpg, jpeg, or gif';
				}
				if ($fileSize > 15000000) {
					$errors[] = 'The files size must be under 15MB.';
				}
				if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
					$errors[] = 'File Extension does not match the file.';
				}
			}

			if(!empty($errors)){
				echo display_errors($errors);
			}else{
				//upload file and insert into database
        if(!empty($_FILES)){
          move_uploaded_file($tmpLoc,$uploadPath);
        }

        // number_product code item number
        $sst = "SELECT Max(substr(number_product,-4))+1 AS MaxID FROM  products";
        $resultt = $db->query($sst);
        $new_id = mysqli_fetch_assoc($resultt);

        $date = date('y')+43;
        if($new_id['MaxID'] == ''){
            $number_product = "PRO-".$date."-0001";
        }else{
            $number_product = "PRO-".$date."-".sprintf("%04d",$new_id['MaxID']);
        }

				$Sql = "INSERT INTO products (number_product, title, price, list_price, brand,categories, sizes, image, description)
                                    VALUES ('$number_product','$title','$price','$list_price','$brand','$category','$sizes','$dbpath', '$description')";
        if(isset($_GET['edit'])){
          $Sql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand',
                                      categories = '$category', sizes = '$sizes', image = '$dbpath', description = '$description'
                                      WHERE id = '$edit_id'";
        }
        $db->query($Sql);
				header('Location: products.php');
			}
		}
?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'แก้ไข':'เพิ่ม');?> ผลิตภัณฑ์</h2><hr>
    <form action="products.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id : 'add=1'); ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="title">ชื่อสินค้า*:</label>
            <input type="text" name="title" class="form-control" id="title" value="<?= $title; ?>">
        </div>
        <div class="form-group col-md-3">
            <label for="brand">แบรนด์*:</label>
            <select class="form-control" id="brand" name="brand">
                <option value=""<?=(($brand == '')?' selected':'');?>></option>
                <?PHP while($b = mysqli_fetch_assoc($brandQuery)):?>
                    <option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>
                <?PHP endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="parent">หมวดหมู่หลัก*:</label>
            <select class="form-control" id="parent" name="parent">
                <option value=""<?=(($parent == '')?' selected':'')?>></option>
                <?PHP while($p = mysqli_fetch_assoc($parentQuery)):?>
                    <option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?' selected':'');?>><?=$p['category'];?></option>
                <?PHP endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="child">หมวดหมู่ลอง*:</label>
            <select id="child" name="child" class="form-control"></select>
        </div>
        <div class="form-group col-md-3">
            <label for="price">ราคาขาย*:</label>
            <input type="text" id="price" name="price" class="form-control" value="<?=$price?>">
        </div>
        <div class="form-group col-md-3">
            <label for="list_price">ลดจากราคา*:</label>
            <input type="text" id="list_price" name="list_price" class="form-control" value="<?=$list_price?>">
        </div>
        <div class="form-group col-md-3">
            <label>ขนาด & ปริมาณ*:</label>
            <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">ขนาด & ปริมาณ</button>
        </div>
        <div class="form-group col-md-3">
            <label for="sizes">แสดงขนาด & ปริมาณที่กรอก</label>
            <input type="text" name="sizes" class="form-control" id="sizes" value="<?=$sizes?>" readonly>
        </div>
        <div class="form-group col-md-6">
            <?php if($saved_image != ''): ?>
                <div class="saved-image">
                  <img src="../<?=$saved_image?>" alt="saved image"/></br>
                  <a href="products.php?delete_image=1&edit=<?=$edit_id?>" class="text-danger">Delete Image</a>
                </div>
            <?php else: ?>
            <label for="photo">ภาพผลิตภัณฑ์*:</label>
            <input type="file" name="photo" id="photo" class="form-control">
          <?php endif; ?>
        </div>
        <div class="form-group col-md-6">
            <label for="description">คำอธิบาย*:</label>
            <textarea name="description" id="description" class="form-control" rows="6"><?=$description?></textarea>
        </div>
        <div class="form-group pull-right">
            <a href="products.php" class="btn btn-default">ยกเลิก</a>
            <input type="submit" value="<?= ((isset($_GET['edit']))?'แก้ไข':'เพิ่ม'); ?> ผลิตภัณฑ์" class="btn btn-success">
        </div><div class="clearfix"></div>
    </form>

        <!-- Modal -->
        <div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="sizesModalLabel">ขนาด & ปริมาณ</h4>
              </div>
              <div class="modal-body">
                  <div class="container-fluid">
                  <?PHP for( $i=1 ; $i<=12 ; $i++ ): ?>
                      <div class="form-group col-md-4">
                          <label for="size<?=$i;?>">ขนาด: </label>
                          <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?= ((!empty($sArray[$i-1]))?$sArray[$i-1]:''); ?>" class="form-control">
                      </div>
                      <div class="form-group col-md-2">
                          <label for="qty<?=$i;?>">ปริมาณ: </label>
                          <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?= ((!empty($qArray[$i-1]))?$qArray[$i-1]:''); ?>" min="0" class="form-control">
                      </div>
                  <?PHP endfor;?>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return fales;">บันทึก</button>
              </div>
            </div>
          </div>
        </div>


<?PHP
    // show data for database
    }else{
    $sql = "SELECT * FROM products WHERE deleted = 0";
    $presults = $db->query($sql);
    if(isset($_GET['featured'])){
        $id = (int)$_GET['id'];
        $featured = (int)$_GET['featured'];
        $featuredsql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
        $db->query($featuredsql);
        header('Location: products.php');
    }
 ?>
<h2 class="text-center">ผลิตภัณฑ์</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">เพิ่มผลิตภัณฑ์ใหม่</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped categories_table">
    <thead><th>#</th><th>รหัสผลิตภัณฑ์</th><th>ผลิตภัณฑ์</th><th>ราคา</th><th>หมวดหมู่</th><th>ปรับสินค้า</th><th>ขาย</th></thead>
    <tbody>
        <?php $i = 1 ?>
        <?PHP while($product = mysqli_fetch_assoc($presults)):
              $childID = $product['categories'];
              $child = mysqli_fetch_assoc($db->query("SELECT * FROM categories WHERE id = '$childID'"));
              $parentID = $child['parent'];
              $parent = mysqli_fetch_assoc($db->query("SELECT * FROM categories WHERE id = '$parentID'"));
              $category = $parent['category'].'-'.$child['category'];

          ?>
            <tr>
                <td width="5%">
                    <a href="products.php?edit=<?= $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="products.php?delete=<?= $product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
                </td>
                <td width="10%"><?= $product['number_product'];?></td>
                <td width="40%"><?= $product['title'];?></td>
                <td width="10%"><?= money($product['price']);?></td>
                <td width="10%"><?= $category; ?></td>
                <td>
                  <a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0'); ?>&id=<?= $product['id'];?>" class="btn btn-xs btn-default">
                    <span class="glyphicon  glyphicon-<?= (($product['featured'] == 1)?'plus':'minus');?>"></span>
                  </a>&nbsp <?= (($product['featured']==1)?'<font color="green">แสดงสินค้า</font>':'');?>
                </td>
                <td>0</td>
            </tr>
        <?PHP endwhile; ?>
    </tbody>
</table>
 <?PHP } include 'include/footer.php'; ?>
 <script>
  $(document).ready(function(){
    $('.categories_table').dataTable();
    get_child_options('<?= $category ?>');
  });
 </script>
