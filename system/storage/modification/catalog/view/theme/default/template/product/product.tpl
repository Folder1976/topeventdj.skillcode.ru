<?php echo $header; ?>
<?php
if(!isset($_COOKIE['IdProduto'])){
$id_como_string = (string)$product_id;
$id_como_string .= ',';
setcookie('IdProduto',$id_como_string,time() + 34560000, "/");
}
else {
$id_como_string = (string)$product_id;
$array_produtos = $_COOKIE['IdProduto'];
if(strpos($array_produtos,','.$id_como_string.',')==strlen($array_produtos)-strlen($id_como_string)-2){   
}		
else if(strpos($array_produtos,$id_como_string.',') === 0){ 
$array_produtos = str_replace($id_como_string.',','',$array_produtos);
$array_produtos .= $id_como_string . ',';
setcookie('IdProduto',$array_produtos,time() + 34560000, "/");
}
else if(strpos($array_produtos,','.$id_como_string.',') !== false){ 
$array_produtos = str_replace($id_como_string.',','',$array_produtos);
$array_produtos .= $id_como_string . ',';
setcookie('IdProduto',$array_produtos,time() + 34560000, "/");
}
else {  
$array_produtos .= $id_como_string . ',';
setcookie('IdProduto',$array_produtos,time() + 34560000, "/");
}
}
?>
<div class="dj-banner blur-up">
  <style type="text/css">
    .switch-content{
      display: flex !important;
    }
  </style>
	<div id="banner-carousel" class="carousel slide carousel-fade" data-ride="carousel">
		<ol class="carousel-indicators">
			<?php foreach ($images as $key => $image) { ?>
				<li data-target="#banner-carousel" data-slide-to="<?php echo $key ?>"></li>
			<?php } ?>
  		</ol>
  		<div class="carousel-inner">
  			<?php foreach ($images as $image) { ?>
  				 <div class="carousel-item">
  		    		<img class="d-block w-100" src="<?php echo $image['thumb']; ?>">
  		  		</div>
  			<?php } ?>
  		</div>
  		<a class="carousel-control-prev arrow" href="#banner-carousel" role="button" data-slide="prev">
    		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
    		<span class="sr-only">Previous</span>
  		</a>
  		<a class="carousel-control-next arrow" href="#banner-carousel" role="button" data-slide="next">
  		  <span class="carousel-control-next-icon" aria-hidden="true"></span>
  		  <span class="sr-only">Next</span>
  		</a>
	</div>

	<div id="banner-carousel2" class="carousel slide carousel-fade" data-ride="carousel" style="display: none;">
		<ol class="carousel-indicators">
			<?php foreach ($images2 as $key => $image) { ?>
				<li data-target="#banner-carousel" data-slide-to="<?php echo $key ?>"></li>
			<?php } ?>
  		</ol>
  		<div class="carousel-inner">
  			<?php foreach ($images2 as $image) { ?>
  				 <div class="carousel-item">
  		    		<img class="d-block w-100" src="<?php echo $image['thumb']; ?>">
  		  		</div>
  			<?php } ?>
  		</div>
  		<a class="carousel-control-prev arrow" href="#banner-carousel" role="button" data-slide="prev">
    		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
    		<span class="sr-only">Previous</span>
  		</a>
  		<a class="carousel-control-next arrow" href="#banner-carousel" role="button" data-slide="next">
  		  <span class="carousel-control-next-icon" aria-hidden="true"></span>
  		  <span class="sr-only">Next</span>
  		</a>
	</div>

  <div class="container h-100">
    <div class="row banner-box h-100 align-items-end justify-content-end">
      <div class="col-12 col-md-6 text-center text-sm-right">

			<!-- Product edit link on front * * * Start -->
			<?php if(isset($token) AND $token){ ?>
			<div style="position: absolute;border: 1px solid red;padding: 2px;z-index: 999;background-color: #ffe0e0;margin-top: -30px;">
				<a style="margin: 2px;" href="/admin/index.php?route=catalog/product/edit&product_id=<?php echo $product_id; ?>&token=<?php echo $token; ?>" target="_blank">edit</a>
			</div>
			<?php } ?>
			<!-- Product edit link on front * * * End -->
					  
        <h1 class="text-white dj-header"><?php echo $heading_title; ?></h1>
        <h3 class="text-white dj-nick"><?php echo $nicname; ?></h3>
        <h4 class="text-white dj-slogan mt-5 d-none d-sm-block"><?php echo $slogan; ?></h4>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <!--<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul> -->
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <!--<ul class="nav nav-tabs">
            <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
            <?php if ($attribute_groups) { ?>
            <li><a href="#tab-specification" data-toggle="tab"><?php echo $tab_attribute; ?></a></li>
            <?php } ?>
            <?php if ($review_status) { ?>
            <li><a href="#tab-review" data-toggle="tab"><?php echo $tab_review; ?></a></li>
            <?php } ?>
          </ul> -->
          <div class="tab-content">
						<div class="tab-pane active" id="tab-description">
							<?php if ($attribute_groups) { ?>
              <div class="top-description">
								<?php foreach ($attribute_groups as $attribute_group) { ?>
								<?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                <p><span style="font-weight: normal;"><?php echo $attribute['name']; ?>:</span> <?php echo $attribute['text']; ?></p>
								<?php } ?>
								<?php } ?>
               </div>
							<?php } ?>
              <div class="text-center">
                <button type="button" class="btn btn-secondary dropdown-content">подробнее</button>
              </div>
              <div class="about-dj">
                <?php echo $description; ?>
              </div>
            </div>
            <?php if ($attribute_groups) { ?>
            <div class="tab-pane" id="tab-specification">
              <table class="table table-bordered">
                <?php foreach ($attribute_groups as $attribute_group) { ?>
                <thead>
                  <tr>
                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                  <tr>
                    <td><?php echo $attribute['name']; ?></td>
                    <td><?php echo $attribute['text']; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
                <?php } ?>
              </table>
            </div>
            <?php } ?>
						
						

          </div>
          <hr class="stroke-line">
          <?php if ($thumb || $images) { ?>
          <div class="thumbnails row">
            <?php if (false and $thumb) { ?>
            <div class="img-thumb col-4 col-md-6 col-lg-4">
              <a class="thumbnail" href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>">
                <img class="img-fluid blur-up" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
              </a>
            </div>
            <?php } ?>
            <?php if ($images and false) { ?>
            <?php foreach ($images as $image) { ?>
            <div class="img-thumb col-4 col-md-6 col-lg-4">
              <a class="thumbnail" href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>">
                <img class="img-fluid blur-up" src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
              </a>
            </div>
            <?php } ?>
            <?php } ?>
    
		        <?php if (isset($media) and count($media)) { ?>
						<?php $limit=15; ?>
            <?php foreach(array_slice($media, 0, $limit) as $image) { ?>
					  <div class="img-thumb col-4 col-md-6 col-lg-4">
              <a target="_blank" class="thumbnail position-relative" href="<?php echo $image->images->standard_resolution->url; ?>" title="<?php echo $image->caption->text; ?>">
							<div class="likes">
                <img src="image/like.svg">
                <span><?php echo $image->likes->count; ?></span>
              </div>
                <img class="img-fluid blur-up" src="<?php echo $image->images->low_resolution->url; ?>" title="<?php echo $image->caption->text; ?>" alt="Картинка <?php echo $image->caption->text; ?>" />
              </a>
            </div>
            <?php } ?>
            <?php } ?>
    
		
		      </div>
          <?php } ?>
          <div class="text-center">
            <a href="<?php echo $href_inctagram; ?>" target="_blank">
              <img width="45" src="image/instagram-logo.svg">          
            </a>
          </div>
          
					
					<hr class="stroke-line">
										
					<?php if ($review_status) { ?>
            <div class="text-center">
              <form class="form-horizontal" id="form-review">
                <div id="review" class="mb-5"></div>
                <div class="text-left">
                  <h2><?php echo $text_write; ?></h2>
                  <?php if ($review_guest) { ?>
                  <div class="form-group required mt-4">
                    <div class="col-sm-12 d-flex rating-dj-box">
                      <label class="control-label"><?php echo $entry_rating; ?></label>
                      &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                      <div class="rating-box">
                        <input id="rtdj1" type="radio" name="rating" value="1" />
                        <label for="rtdj1"><i class="fa fa-star" aria-hidden="true"></i></label>
                      </div>
                      &nbsp;
                      <div class="rating-box">
                        <input id="rtdj2" type="radio" name="rating" value="2" />
                        <label for="rtdj2"><i class="fa fa-star" aria-hidden="true"></i></label>
                      </div>
                      &nbsp;
                      <div class="rating-box">
                        <input id="rtdj3" type="radio" name="rating" value="3" />
                        <label for="rtdj3"><i class="fa fa-star" aria-hidden="true"></i></label>
                      </div>
                      &nbsp;
                      <div class="rating-box">
                        <input id="rtdj4" type="radio" name="rating" value="4" />
                        <label for="rtdj4"><i class="fa fa-star" aria-hidden="true"></i></label>
                      </div>
                      &nbsp;
                      <div class="rating-box">
                        <input id="rtdj5" type="radio" name="rating" value="5" />
                        <label for="rtdj5"><i class="fa fa-star" aria-hidden="true"></i></label>
                      </div>
                      &nbsp;<?php echo $entry_good; ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <div class="col-sm-12">
                      <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control dj-field" placeholder="Ваше имя" />
                    </div>
                  </div>
                  <div class="form-group required">
                    <div class="col-sm-12">
                      <textarea name="text" rows="5" id="input-review" class="form-control dj-field" placeholder="Ваш отзыв"></textarea>
                      <div class="help-block mt-3"><?php echo $text_note; ?></div>
                    </div>
                  </div>
                  <?php echo $captcha; ?>
                  <div class="buttons clearfix">
                    <div class="pull-right">
                      <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-secondary">Оставить Отзыв</button>
                    </div>
                  </div>
                  <?php } else { ?>
                  <?php echo $text_login; ?>
                  <?php } ?>
                </div>
              </form>
            </div>
            <?php } ?>
					
					<hr class="stroke-line">
					
        </div>

        <div class="col-12 col-lg-6 text-center text-lg-left">
          <div class="dj-logo-img mb-3 d-block d-lg-none">
            <img class="img-fluid" src="<?php echo $popup; ?>">
          </div>
          <h3 class="dj-header-secondary"><?php echo $heading_title; ?></h3>
          <h4 class="dj-nick-secondary mb-5"><?php echo $nicname; ?></h4>
          <h4 class="dj-nick-secondary mb-4">
            <a class="phone-call" href="tel:<?php echo $user_phone; ?>"><?php echo $user_phone; ?></a>
          </h4>
          <h4 class="dj-nick-secondary mb-4">
            <a class="mailto" href="mailto:<?php echo $user_email; ?>"><?php echo $user_email; ?></a>
          </h4>
          <ul class="footer-menu m-0 justify-content-center justify-content-lg-start">
            <li class="m-0">
              <a href="<?php echo $href_facebook; ?>" target="_blank">
                <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve"><desc>Facebook</desc><path style="fill:#ffffff;" d="M47.761,24c0,13.121-10.638,23.76-23.758,23.76C10.877,47.76,0.239,37.121,0.239,24c0-13.124,10.638-23.76,23.764-23.76C37.123,0.24,47.761,10.876,47.761,24 M20.033,38.85H26.2V24.01h4.163l0.539-5.242H26.2v-3.083c0-1.156,0.769-1.427,1.308-1.427h3.318V9.168L26.258,9.15c-5.072,0-6.225,3.796-6.225,6.224v3.394H17.1v5.242h2.933V38.85z"></path></svg>
              </a>
            </li>
            <li>
              <a href="<?php echo $href_vk; ?>" target="_blank">
                <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve"><desc>VK</desc><path style="fill:#ffffff;" d="M47.761,24c0,13.121-10.639,23.76-23.76,23.76C10.878,47.76,0.239,37.121,0.239,24c0-13.123,10.639-23.76,23.762-23.76C37.122,0.24,47.761,10.877,47.761,24 M35.259,28.999c-2.621-2.433-2.271-2.041,0.89-6.25c1.923-2.562,2.696-4.126,2.45-4.796c-0.227-0.639-1.64-0.469-1.64-0.469l-4.71,0.029c0,0-0.351-0.048-0.609,0.106c-0.249,0.151-0.414,0.505-0.414,0.505s-0.742,1.982-1.734,3.669c-2.094,3.559-2.935,3.747-3.277,3.524c-0.796-0.516-0.597-2.068-0.597-3.171c0-3.449,0.522-4.887-1.02-5.259c-0.511-0.124-0.887-0.205-2.195-0.219c-1.678-0.016-3.101,0.007-3.904,0.398c-0.536,0.263-0.949,0.847-0.697,0.88c0.31,0.041,1.016,0.192,1.388,0.699c0.484,0.656,0.464,2.131,0.464,2.131s0.282,4.056-0.646,4.561c-0.632,0.347-1.503-0.36-3.37-3.588c-0.958-1.652-1.68-3.481-1.68-3.481s-0.14-0.344-0.392-0.527c-0.299-0.222-0.722-0.298-0.722-0.298l-4.469,0.018c0,0-0.674-0.003-0.919,0.289c-0.219,0.259-0.018,0.752-0.018,0.752s3.499,8.104,7.573,12.23c3.638,3.784,7.764,3.36,7.764,3.36h1.867c0,0,0.566,0.113,0.854-0.189c0.265-0.288,0.256-0.646,0.256-0.646s-0.034-2.512,1.129-2.883c1.15-0.36,2.624,2.429,4.188,3.497c1.182,0.812,2.079,0.633,2.079,0.633l4.181-0.056c0,0,2.186-0.136,1.149-1.858C38.281,32.451,37.763,31.321,35.259,28.999"></path></svg>            </a>
            </li>
            <li>
              <a href="<?php echo $href_inctagram; ?>" target="_blank">
                <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 30 30" xml:space="preserve"><desc>Instagram</desc><path style="fill:#ffffff;" d="M15,11.014 C12.801,11.014 11.015,12.797 11.015,15 C11.015,17.202 12.802,18.987 15,18.987 C17.199,18.987 18.987,17.202 18.987,15 C18.987,12.797 17.199,11.014 15,11.014 L15,11.014 Z M15,17.606 C13.556,17.606 12.393,16.439 12.393,15 C12.393,13.561 13.556,12.394 15,12.394 C16.429,12.394 17.607,13.561 17.607,15 C17.607,16.439 16.444,17.606 15,17.606 L15,17.606 Z"></path><path style="fill:#ffffff;" d="M19.385,9.556 C18.872,9.556 18.465,9.964 18.465,10.477 C18.465,10.989 18.872,11.396 19.385,11.396 C19.898,11.396 20.306,10.989 20.306,10.477 C20.306,9.964 19.897,9.556 19.385,9.556 L19.385,9.556 Z"></path><path style="fill:#ffffff;" d="M15.002,0.15 C6.798,0.15 0.149,6.797 0.149,15 C0.149,23.201 6.798,29.85 15.002,29.85 C23.201,29.85 29.852,23.202 29.852,15 C29.852,6.797 23.201,0.15 15.002,0.15 L15.002,0.15 Z M22.666,18.265 C22.666,20.688 20.687,22.666 18.25,22.666 L11.75,22.666 C9.312,22.666 7.333,20.687 7.333,18.28 L7.333,11.734 C7.333,9.312 9.311,7.334 11.75,7.334 L18.25,7.334 C20.688,7.334 22.666,9.312 22.666,11.734 L22.666,18.265 L22.666,18.265 Z"></path></svg>            </a>
            </li>
          </ul>
        </div>
        <div class="col-12 col-lg-6 d-none d-lg-block position-relative">
          <div class="send-anim animated fadeIn" style="display: none;">
              <img width="180" src="image/send-img.gif">
            </div>
          <form id="dj_form">
        
            <input type="hidden" name="dj-name" value="<?php echo $heading_title; ?>">
            <input type="hidden" name="dj-email" value="<?php echo $user_email;?>">
            <div class="form-group" id="form">
              <input type="date" name="date" class="form-control dj-field"  placeholder="Дата мероприятия" value="<?php if(isset($_GET['date']) AND $_GET['date']) echo $_GET['date']; ?>">
            </div>
            <div class="form-group">
              <input type="email" name="email" class="form-control dj-field" aria-describedby="emailHelp" placeholder="Ваш e-mail">
            </div>
            <div class="form-group">
              <input type="text" name="name" class="form-control dj-field" placeholder="Ваше имя">
            </div>
            <div class="form-group">
              <textarea name="comment" class="form-control dj-field" rows="3" placeholder="Ваше сообщение"></textarea>
            </div>
            <button type="submit" id="msg_send" class="btn btn-secondary mt-4"
                        data-loading-text="Отправляю . . ."
                        data-error-text="Ошибка"
                        data-success-text="Отправлено"
                          >отправить сообщение</button>
          </form>
         
        </div>
        <div class="col-12">
          <hr class="stroke-line">
        </div>
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-4'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?> d-none">
          <div class="btn-group">
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart"></i></button>
            <button type="button" data-toggle="tooltip" class="btn btn-default" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
          </div>

			<!-- Product edit link on front * * * Start -->
			<?php if(isset($token) AND $token){ ?>
			<div style="position: absolute;border: 1px solid red;padding: 2px;z-index: 999;background-color: #ffe0e0;margin-top: -30px;">
				<a style="margin: 2px;" href="/admin/index.php?route=catalog/product/edit&product_id=<?php echo $product_id; ?>&token=<?php echo $token; ?>" target="_blank">edit</a>
			</div>
			<?php } ?>
			<!-- Product edit link on front * * * End -->
					  
          <h1><?php echo $heading_title; ?></h1>
          <ul class="list-unstyled">
            <li><?php echo $instagram_id; ?></li>
            <li><?php echo $instagram_code; ?></li>
            <li><?php echo $nicname; ?></li>
            <li><?php echo $href_facebook; ?></li>
            <li><?php echo $href_vk; ?></li>
            <li><?php echo $href_inctagram; ?></li>
            <li><?php echo $slogan; ?></li>
            <li><?php echo $user_phone; ?></li>
            <li><?php echo $user_email; ?></li>
          </ul>


          <ul class="list-unstyled">
            <?php if ($manufacturer) { ?>
            <li><?php echo $text_manufacturer; ?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
            <?php } ?>
            <li><?php echo $text_model; ?> <?php echo $model; ?></li>
            <?php if ($reward) { ?>
            <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>
            <?php } ?>
            <li><?php echo $text_stock; ?> <?php echo $stock; ?></li>
          </ul>
          <?php if ($price) { ?>
          <ul class="list-unstyled">
            <?php if (!$special) { ?>
            <li>
              <h2><?php echo $price; ?></h2>
            </li>
            <?php } else { ?>
            <li><span style="text-decoration: line-through;"><?php echo $price; ?></span></li>
            <li>
              <h2><?php echo $special; ?></h2>
            </li>
            <?php } ?>
            <?php if ($tax) { ?>
            <li><?php echo $text_tax; ?> <?php echo $tax; ?></li>
            <?php } ?>
            <?php if ($points) { ?>
            <li><?php echo $text_points; ?> <?php echo $points; ?></li>
            <?php } ?>
            <?php if ($discounts) { ?>
            <li>
              <hr>
            </li>
            <?php foreach ($discounts as $discount) { ?>
            <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
            <?php } ?>
            <?php } ?>
          </ul>
          <?php } ?>
          <div id="product">
            <?php if ($options) { ?>
            <hr>
            <h3><?php echo $text_option; ?></h3>
            <?php foreach ($options as $option) { ?>
            <?php if ($option['type'] == 'select') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'radio') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="radio">
                  <label>
                    <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <?php if ($option_value['image']) { ?>
                    <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
                    <?php } ?>
                    <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'checkbox') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <div id="input-option<?php echo $option['product_option_id']; ?>">
                <?php foreach ($option['product_option_value'] as $option_value) { ?>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" />
                    <?php if ($option_value['image']) { ?>
                    <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
                    <?php } ?>
                    <?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'text') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'textarea') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'file') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label"><?php echo $option['name']; ?></label>
              <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
              <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'date') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group date">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'datetime') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group datetime">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php if ($option['type'] == 'time') { ?>
            <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
              <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
              <div class="input-group time">
                <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if ($recurrings) { ?>
            <hr>
            <h3><?php echo $text_payment_recurring; ?></h3>
            <div class="form-group required">
              <select name="recurring_id" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($recurrings as $recurring) { ?>
                <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                <?php } ?>
              </select>
              <div class="help-block" id="recurring-description"></div>
            </div>
            <?php } ?>
            <div class="form-group">
              <label class="control-label" for="input-quantity"><?php echo $entry_qty; ?></label>
              <input type="text" name="quantity" value="<?php echo $minimum; ?>" size="2" id="input-quantity" class="form-control" />
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
              <br />
              <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block"><?php echo $button_cart; ?></button>
            </div>
            <?php if ($minimum > 1) { ?>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
            <?php } ?>
          </div>
          <?php if ($review_status) { ?>
          <div class="rating">
            <p>
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($rating < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } ?>
              <?php } ?>
              <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a> / <a href="" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a></p>
            <hr>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style" data-url="<?php echo $share; ?>"><a class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a class="addthis_button_tweet"></a> <a class="addthis_button_pinterest_pinit"></a> <a class="addthis_counter addthis_pill_style"></a></div>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
            <!-- AddThis Button END -->
          </div>
          <?php } ?>
        </div>
      </div>

      <h2 class="recomended-header">другие диджеи</h2>

      <?php if ($products) { ?>
      <h3><?php echo $text_related; ?></h3>
      <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($products as $product) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-8 col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-6 col-md-4'; ?>
        <?php } else { ?>
        <?php $class = 'col-6 col-sm-3'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <div class="product-thumb transition">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
              <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
              <p><?php echo $product['description']; ?></p>
              <?php if ($product['rating']) { ?>
              <div class="rating">
                <?php for ($j = 1; $j <= 5; $j++) { ?>
                <?php if ($product['rating'] < $j) { ?>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } else { ?>
                <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                <?php } ?>
                <?php } ?>
              </div>
              <?php } ?>
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
            </div>
            <div class="button-group">
              <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span> <i class="fa fa-shopping-cart"></i></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
              <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
            </div>
          </div>
        </div>
        <?php if (($column_left && $column_right) && (($i+1) % 2 == 0)) { ?>
        <div class="clearfix visible-md visible-sm"></div>
        <?php } elseif (($column_left || $column_right) && (($i+1) % 3 == 0)) { ?>
        <div class="clearfix visible-md"></div>
        <?php } elseif (($i+1) % 4 == 0) { ?>
        <div class="clearfix visible-md"></div>
        <?php } ?>
        <?php $i++; ?>
        <?php } ?>
      </div>
      <?php } ?>
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function() {
			$('#recurring-description').html('');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#button-cart').on('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {
				$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('button[id^=\'button-upload\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').val(json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>
<script type="text/javascript"><!--
$('#review').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#review').fadeOut('slow');

    $('#review').load(this.href);

    $('#review').fadeIn('slow');
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').on('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: $("#form-review").serialize(),
		beforeSend: function() {
			$('#button-review').button('loading');
		},
		complete: function() {
			$('#button-review').button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']) {
				$('#form-review .buttons').append('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').prop('checked', false);
			}
		}
	});
});

$(document).ready(function() {
	$('.thumbnails').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});

   $("input.switch-case").attr("checked", "checked");
    $('#eventCalendar').hide();
    $('#eventCalendar_user').show();
    $(".switch input").change(function(){
        if( $(this).is(':checked') ){
          $(".calendar_name2").removeClass("text-primary");
          $(".calendar_name").addClass("text-primary");
        } else {
          $(".calendar_name").removeClass("text-primary");
          $(".calendar_name2").addClass("text-primary");
        }
    });
});
//--></script>
<?php echo $footer; ?>
