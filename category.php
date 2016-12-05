<?php
	require_once 'core/init.php';
	include 'include/head.php';
	include 'include/navigation.php';
	include 'include/headerpartial.php';
	include 'include/leftbar.php';

  if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
  }else{
    $cat_id = '';
  }

	$sql = "SELECT * FROM products WHERE categories = '$cat_id' AND featured = 1";
	$productsQ = $db->query($sql);
  $category = get_cetegory($cat_id);
?>
<style>
	.point{
		width: 19%;
		height: 359px;
		padding-bottom: 1rem;
		transition: ease .5s ;
	}
	.point:hover{
		transition: ease .5s ;
		box-shadow: 0px 1px 4px #888888;
		cursor: pointer;
	}
</style>
<!-- main content -->
<div class="col-md-8">
	<div class="row">
		<h2 class="text-center Feature"><?= $category['parent'].' '.$category['child']?></h2>
		<?php while($product = mysqli_fetch_assoc($productsQ)) : ?>
			<div class="col-md-3 point">
				<h4><?= strtoupper($product['title']); ?></h4>
				<a onclick="detailsmodal(<?= $product['id']; ?>)"> <img src="<?= $product['image']; ?>" alt="<?= $product['title'];?>" class="img-thumb" /> </a>
				<p class="list-price text-danger">ลดจากราคา: <s>฿<?= $product['list_price'];?></s></p>
				<p class="price">ขายราคา: ฿<?= $product['price'];?></p>
				<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id'];?>)">รายละเอียด</button>
			</div>
		<?php endwhile; ?>
	</div>
</div>
<?php
	include 'include/rightbar.php';
	include 'include/footer.php';
?>
