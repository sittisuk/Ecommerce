<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <a href="index.php" class="navbar-brand">SHOPPER CENTRAL<!--span style="font-size: 16px">แผงควบคุม</span--></a>
        <ul class="nav navbar-nav">
    				<!-- MEN items -->
            <li><a href="brands.php">แบรนด์</a></li>
            <li><a href="categories.php">หมวดหมู่</a></li>
            <li><a href="products.php">ผลิตภัณฑ์</a></li>
            <li><a href="archived.php">เก็บถาวร</a></li>
            <?php if(has_permission('admin')):?>
              <li><a href="users.php">ผู้ใช้</a></li>
            <?php endif; ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">สวัสดี <?= $user_data['first'] ?>
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="change_password.php">เปลี่ยนรหัส</a></li>
                <li><a href="logout.php">ออกจากระบบ</a></li>
              </ul>
            </li>
    				<!--li class="dropdown">
    					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?><span class="caret"></span></a>
    					<ul class="dropdown-menu" role="menu">
    							<li><a href="#"></a></li>
    					</ul>
    				</li>-->
        </ul>
    </div>
</nav>
