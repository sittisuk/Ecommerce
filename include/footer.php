</div>
<footer class="text-center" id="footer">&copy; Copyright 2016 SHOPPER CENTRAL</footer>


<script>
jQuery(window).scroll(function(){
  var vscroll = jQuery(this).scrollTop();
  jQuery('#logotext').css({
    "transform" : "translate(0px, "+vscroll/2+"px)"
  });

  jQuery('#back-flower').css({
    "transform" : "translate("+vscroll/5+"px, -"+vscroll/12+"px)"
  });

  jQuery('#fore-flower').css({
    "transform" : "translate(0px, -"+vscroll/20+"px)"
  });
});

function detailsmodal(id){
  var data = {"id" : id};
  $.ajax({
    url: 'include/detailsmodal.php',
    method: "post",
    data: data,
    success: function(data){
      jQuery('body').append(data);
      jQuery('#details-modal').modal('toggle');
    },
    error: function(){
      alert("Something went wrong! detailsmodal");
    }
  });
}

function update_cart(mode,edit_id,edit_size){
  var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
  $.ajax({
    url: "admin/parsers/update_cart.php",
    method: "post",
    data: data,
    success: function(){
      location.reload();
    },
    error: function(){alert("Something went wrong!")},
  })
}

function add_to_cart(){
  $('#modal_errors').html("");
  var size = $('#size').val();
  var quantity = $('#quantity').val();
  var available = $('#available').val();
  var error = '';
  var data = $('#add_product_form').serialize();
  if(size == '' || quantity == '' || quantity == 0){
    error += '<p class="text-danger text-center">ท่านต้องเลือกขนาดของสินค้าก่อน.</p>';
    $('#modal_errors').html(error);
    return;
  }else if(quantity > available){
    error += '<p class="text-danger text-center">สามารถเลือกได้ไม่เกิน '+available+' ชิ้น.</p>';
    $('#modal_errors').html(error);
    return;
  }else{
    $.ajax({
      url: 'admin/parsers/add_cart.php',
      method: 'post',
      data: data,
      success: function(){
        location.reload();
      },
      error: function(){alert("Something went wrong")}
    })
  }

}
</script>
</body>
</html>
