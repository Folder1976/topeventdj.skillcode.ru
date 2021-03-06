
<div class="row last_v">
  <div class="col-xs-12">
    <h3 class="last_v_caption">ПЕРЕГЛЯНУТІ ТОВАРИ</h3>
  </div>
<div class="lsv col-xs-12 row">
       <?php foreach ($products as $product) { ?>

        <div class="product-layout col-12 col-xl-6">
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $product['href']; ?>">
                <img
                    <?php if($product['quantity'] < 1){ echo 'class="no_products"'; }?>
                     src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="" /></a></div>
            <div style="clear: both;"></div>
            <div>
              <div class="caption">
                <h4><a class="product-name" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <!--<p><?php echo $product['description']; ?></p>-->
                <?php if ($product['price']) { ?>
                <p class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                  <?php if ($product['tax']) { ?>
                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                  <?php } ?>
                </p>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <!--<div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>-->
            </div>
          </div>
        </div>

        <?php } ?>

</div>
</div>

<script>
  var lsv = true;
  $(".last_v_caption").click(function () {
      if(lsv){
          $(".lsv").css("maxHeight","10000px");
          lsv = false;
      } else{
          $(".lsv").css("maxHeight","");
          lsv = true;
      }
  })
</script>