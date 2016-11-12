<?php
	include_once 'core/init.php';
	include 'include/head.php';
	include 'include/navigation.php';
	include 'include/headerfull.php';
	include 'include/leftbar.php';

	$sql = "SELECT * FROM products WHERE featured = 1";
	$featured = $db->query($sql);
?>

<div class="col-md-8">
	<div class="row">
		<h2 class="text-center Feature">Featured Products</h2>
		<?php while($product = mysqli_fetch_assoc($featured)) : ?>
			<div class="col-md-3">
				<h4><?= $product['title']; ?></h4>
				<img src="<?= $product['image']; ?>" alt="<?= $product['title'];?>" class="img-thumb" />
				<p class="list-price text-danger">List price : <s>฿<?= $product['list_price'];?></s></p>
				<p class="price">Our price: ฿<?= $product['price'];?></p>
				<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id'];?>)">Details</button>
			</div>
		<?php endwhile; ?>
	</div>
</div>
<?php
	include 'include/rightbar.php';
	include 'include/footer.php';
?>
