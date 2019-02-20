<?php echo $header; ?>
<div class="contact-content blur-up">
  <div class="container h-100">
    <!-- <ul class="breadcrumb">
       <?php foreach ($breadcrumbs as $breadcrumb) { ?>
       <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
       <?php } ?>
     </ul> -->
    <div class="row justify-content-center align-items-center h-100"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
      <?php $class = 'col-12 col-md-6'; ?>
      <?php } ?>
      <div id="content" class="<?php echo $class; ?> text-center"><?php echo $content_top; ?>
        <!--<h1><?php echo $heading_title; ?></h1> -->
        <img class="img-fluid" width="280" src="image/logo-contact.png">
        <h3 class="text-white font-weight-light">мы рассмотрим любые ваши предложения</h4>
        <!-- <h3><?php echo $text_location; ?></h3> -->
        <!-- <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
              <?php if ($image) { ?>
              <div class="col-sm-3"><img src="<?php echo $image; ?>" alt="<?php echo $store; ?>" title="<?php echo $store; ?>" class="img-thumbnail" /></div>
              <?php } ?>
              <div class="col-sm-3"><strong><?php echo $store; ?></strong><br />
                <address>
                  <?php echo $address; ?>
                </address>
                <?php if ($geocode) { ?>
                <a href="https://maps.google.com/maps?q=<?php echo urlencode($geocode); ?>&hl=<?php echo $geocode_hl; ?>&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> <?php echo $button_map; ?></a>
                <?php } ?>
              </div>
              <div class="col-sm-3"><strong><?php echo $text_telephone; ?></strong><br>
                <?php echo $telephone; ?><br />
                <br />
                <?php if ($fax) { ?>
                <strong><?php echo $text_fax; ?></strong><br>
                <?php echo $fax; ?>
                <?php } ?>
              </div>
              <div class="col-sm-3">
                <?php if ($open) { ?>
                <strong><?php echo $text_open; ?></strong><br />
                <?php echo $open; ?><br />
                <br />
                <?php } ?>
                <?php if ($comment) { ?>
                <strong><?php echo $text_comment; ?></strong><br />
                <?php echo $comment; ?>
                <?php } ?>
              </div>
            </div>
          </div>
        </div> -->
        <?php if ($locations) { ?>
        <h3><?php echo $text_store; ?></h3>
        <div class="panel-group" id="accordion">
          <?php foreach ($locations as $location) { ?>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"><a href="#collapse-location<?php echo $location['location_id']; ?>" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><?php echo $location['name']; ?> <i class="fa fa-caret-down"></i></a></h4>
            </div>
            <div class="panel-collapse collapse" id="collapse-location<?php echo $location['location_id']; ?>">
              <div class="panel-body">
                <div class="row">
                  <?php if ($location['image']) { ?>
                  <div class="col-sm-3"><img src="<?php echo $location['image']; ?>" alt="<?php echo $location['name']; ?>" title="<?php echo $location['name']; ?>" class="img-thumbnail" /></div>
                  <?php } ?>
                  <div class="col-sm-3"><strong><?php echo $location['name']; ?></strong><br />
                    <address>
                      <?php echo $location['address']; ?>
                    </address>
                    <?php if ($location['geocode']) { ?>
                    <a href="https://maps.google.com/maps?q=<?php echo urlencode($location['geocode']); ?>&hl=<?php echo $geocode_hl; ?>&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> <?php echo $button_map; ?></a>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3"> <strong><?php echo $text_telephone; ?></strong><br>
                    <?php echo $location['telephone']; ?><br />
                    <br />
                    <?php if ($location['fax']) { ?>
                    <strong><?php echo $text_fax; ?></strong><br>
                    <?php echo $location['fax']; ?>
                    <?php } ?>
                  </div>
                  <div class="col-sm-3">
                    <?php if ($location['open']) { ?>
                    <strong><?php echo $text_open; ?></strong><br />
                    <?php echo $location['open']; ?><br />
                    <br />
                    <?php } ?>
                    <?php if ($location['comment']) { ?>
                    <strong><?php echo $text_comment; ?></strong><br />
                    <?php echo $location['comment']; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
          <fieldset>
            <!-- <legend><?php echo $text_contact; ?></legend> -->
            <div class="form-group required">
              <!--<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label> -->
              <div>
                <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control dj-field" placeholder="<?php echo $entry_name; ?>" />
                <?php if ($error_name) { ?>
                <div class="text-danger"><?php echo $error_name; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <!--<label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label> -->
              <div>
                <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control dj-field" placeholder="<?php echo $entry_email; ?>" />
                <?php if ($error_email) { ?>
                <div class="text-danger"><?php echo $error_email; ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <!--<label class="col-sm-2 control-label" for="input-enquiry"><?php echo $entry_enquiry; ?></label> -->
              <div>
                <textarea name="enquiry" rows="4" id="input-enquiry" class="form-control dj-field" placeholder="<?php echo $entry_enquiry; ?>"><?php echo $enquiry; ?></textarea>
                <?php if ($error_enquiry) { ?>
                <div class="text-danger"><?php echo $error_enquiry; ?></div>
                <?php } ?>
              </div>
            </div>
            <?php echo $captcha; ?>
          </fieldset>
          <div class="buttons">
            <div class="text-center">
              <input class="btn btn-secondary" type="submit" value="<?php echo $button_submit; ?>" />
            </div>
          </div>
        </form>
        <div class="mt-5 pb-4">
          <ul class="footer-menu m-0 justify-content-center">
          <li class="m-0">
            <a href="#">
              <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve"><desc>Facebook</desc><path style="fill:#ffffff;" d="M47.761,24c0,13.121-10.638,23.76-23.758,23.76C10.877,47.76,0.239,37.121,0.239,24c0-13.124,10.638-23.76,23.764-23.76C37.123,0.24,47.761,10.876,47.761,24 M20.033,38.85H26.2V24.01h4.163l0.539-5.242H26.2v-3.083c0-1.156,0.769-1.427,1.308-1.427h3.318V9.168L26.258,9.15c-5.072,0-6.225,3.796-6.225,6.224v3.394H17.1v5.242h2.933V38.85z"></path></svg>
            </a>
          </li>
          <li>
            <a href="#">
              <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve"><desc>VK</desc><path style="fill:#ffffff;" d="M47.761,24c0,13.121-10.639,23.76-23.76,23.76C10.878,47.76,0.239,37.121,0.239,24c0-13.123,10.639-23.76,23.762-23.76C37.122,0.24,47.761,10.877,47.761,24 M35.259,28.999c-2.621-2.433-2.271-2.041,0.89-6.25c1.923-2.562,2.696-4.126,2.45-4.796c-0.227-0.639-1.64-0.469-1.64-0.469l-4.71,0.029c0,0-0.351-0.048-0.609,0.106c-0.249,0.151-0.414,0.505-0.414,0.505s-0.742,1.982-1.734,3.669c-2.094,3.559-2.935,3.747-3.277,3.524c-0.796-0.516-0.597-2.068-0.597-3.171c0-3.449,0.522-4.887-1.02-5.259c-0.511-0.124-0.887-0.205-2.195-0.219c-1.678-0.016-3.101,0.007-3.904,0.398c-0.536,0.263-0.949,0.847-0.697,0.88c0.31,0.041,1.016,0.192,1.388,0.699c0.484,0.656,0.464,2.131,0.464,2.131s0.282,4.056-0.646,4.561c-0.632,0.347-1.503-0.36-3.37-3.588c-0.958-1.652-1.68-3.481-1.68-3.481s-0.14-0.344-0.392-0.527c-0.299-0.222-0.722-0.298-0.722-0.298l-4.469,0.018c0,0-0.674-0.003-0.919,0.289c-0.219,0.259-0.018,0.752-0.018,0.752s3.499,8.104,7.573,12.23c3.638,3.784,7.764,3.36,7.764,3.36h1.867c0,0,0.566,0.113,0.854-0.189c0.265-0.288,0.256-0.646,0.256-0.646s-0.034-2.512,1.129-2.883c1.15-0.36,2.624,2.429,4.188,3.497c1.182,0.812,2.079,0.633,2.079,0.633l4.181-0.056c0,0,2.186-0.136,1.149-1.858C38.281,32.451,37.763,31.321,35.259,28.999"></path></svg>            </a>
          </li>
          <li>
            <a href="#">
              <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 30 30" xml:space="preserve"><desc>Instagram</desc><path style="fill:#ffffff;" d="M15,11.014 C12.801,11.014 11.015,12.797 11.015,15 C11.015,17.202 12.802,18.987 15,18.987 C17.199,18.987 18.987,17.202 18.987,15 C18.987,12.797 17.199,11.014 15,11.014 L15,11.014 Z M15,17.606 C13.556,17.606 12.393,16.439 12.393,15 C12.393,13.561 13.556,12.394 15,12.394 C16.429,12.394 17.607,13.561 17.607,15 C17.607,16.439 16.444,17.606 15,17.606 L15,17.606 Z"></path><path style="fill:#ffffff;" d="M19.385,9.556 C18.872,9.556 18.465,9.964 18.465,10.477 C18.465,10.989 18.872,11.396 19.385,11.396 C19.898,11.396 20.306,10.989 20.306,10.477 C20.306,9.964 19.897,9.556 19.385,9.556 L19.385,9.556 Z"></path><path style="fill:#ffffff;" d="M15.002,0.15 C6.798,0.15 0.149,6.797 0.149,15 C0.149,23.201 6.798,29.85 15.002,29.85 C23.201,29.85 29.852,23.202 29.852,15 C29.852,6.797 23.201,0.15 15.002,0.15 L15.002,0.15 Z M22.666,18.265 C22.666,20.688 20.687,22.666 18.25,22.666 L11.75,22.666 C9.312,22.666 7.333,20.687 7.333,18.28 L7.333,11.734 C7.333,9.312 9.311,7.334 11.75,7.334 L18.25,7.334 C20.688,7.334 22.666,9.312 22.666,11.734 L22.666,18.265 L22.666,18.265 Z"></path></svg>            </a>
          </li>
        </ul>
        </div>
        <?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
  </div>
</div>
