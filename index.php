<?php
	include_once 'core/init.php';
	include 'include/head.php';
	include 'include/navigation.php';
	include 'include/headerfull.php';
	include 'include/leftbar.php';

	$sql = "SELECT * FROM products WHERE featured = 1";
	$featured = $db->query($sql);
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
<div class="col-md-8">
	<div class="row">
		<h2 class="text-center Feature">Product</h2>
		<?php while($product = mysqli_fetch_assoc($featured)) : ?>
			<div class="col-md-3 point">
				<h4><?= strtoupper($product['title']); ?></h4>
				<a onclick="detailsmodal(<?= $product['id']; ?>)"> <img src="<?= $product['image']; ?>" title="<?= $product['title'];?>" class="img-thumb" /> </a>
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
