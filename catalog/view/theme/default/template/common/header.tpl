<!DOCTYPE html>
<head>
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; minimum-scale=1.0; user-scalable=no; target-densityDpi=device-dpi" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<link href="catalog/view/javascript/bootstrap/css/animate.css" rel="stylesheet" media="screen" />
  <script src="catalog/view/javascript/bootstrap/js/popper.min.js" type="text/javascript"></script>
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>
</head>
<body class="<?php echo $class; ?>">
<div id="eventdj-content">



<div class="top-navbar">
  <div class="container">
    <nav class="navbar">
      <a class="navbar-brand" href="/">
        #topeventdj
      </a>
      <button id="nav-icon">
        <svg class="inline-svg" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             width="32px" height="22px" viewBox="0 0 32 22.5" enable-background="new 0 0 32 22.5" xml:space="preserve">
					<title>Mobile Menu</title>
          <g class="svg-menu-toggle">

            <path class="bar" d="M20.945,8.75c0,0.69-0.5,1.25-1.117,1.25H3.141c-0.617,0-1.118-0.56-1.118-1.25l0,0
						c0-0.69,0.5-1.25,1.118-1.25h16.688C20.445,7.5,20.945,8.06,20.945,8.75L20.945,8.75z">
            </path>
            <path class="bar" d="M20.923,15c0,0.689-0.501,1.25-1.118,1.25H3.118C2.5,16.25,2,15.689,2,15l0,0c0-0.689,0.5-1.25,1.118-1.25 h16.687C20.422,13.75,20.923,14.311,20.923,15L20.923,15z">
            </path>
            <path class="bar" d="M20.969,21.25c0,0.689-0.5,1.25-1.117,1.25H3.164c-0.617,0-1.118-0.561-1.118-1.25l0,0
						c0-0.689,0.5-1.25,1.118-1.25h16.688C20.469,20,20.969,20.561,20.969,21.25L20.969,21.25z">
            </path>
            <!-- needs to be here as a 'hit area' -->
            <rect width="32" height="22" fill="none">

            </rect>
          </g>
		</svg>
      </button>
    </nav>
  </div>
</div>
<header>
  <ul class="nav navbar-nav">
    <li>
      <a href="/">Главная</a>
    </li>
    <?php foreach ($categories as $category) { ?>
    <?php if ($category['children']) { ?>
    <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
      <div class="dropdown-menu">
        <div class="dropdown-inner">
          <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
          <ul class="list-unstyled">
            <?php foreach ($children as $child) { ?>
            <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
            <?php } ?>
          </ul>
          <?php } ?>
        </div>
        <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
    </li>
    <?php } else { ?>
    <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
    <?php } ?>
    <?php } ?>
    <li>
      <a href="/contact">Контакты</a>
    </li>
    <p class="text-white">Рекомендуйте нас друзьям:</p>
    <div class="d-flex share-btn-box">
      <a id="fb-share-button">
        <span class="share-btn">
          <i class="fa fa-facebook" aria-hidden="true"></i>
        </span>
      </a>
      <a id="vk-btn" onclick="window.open(this.href, 'vkwindow','left=20,top=20,width=600,height=700,toolbar=0,resizable=1'); return false;">
        <span class="share-btn">
          <i class="fa fa-vk" aria-hidden="true"></i>
        </span>
      </a>
    </div>
  </ul>
</header>

<!-- Button trigger modal -->
<button class="small-calendar" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-cal">
  <img src="image/event.svg" width="40">
</button>

<!-- Modal -->
<div class="modal fade" id="modal-cal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
        <h3>Выберете дату вашего мероприятия</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <!-- swicth -->
        <div class="switch-content text-center align-items-center d-none">
          <span class="calendar_name2">Общий календарь</span>
            <label class="switch m-0 ml-2 mr-2">
              <input class="switch-case" type="checkbox" name="graduate">
              <span class="slider round"></span>
            </label>
            <span class="calendar_name text-primary">календарь диджея</span>
        </div>
      </div>
      <div class="modal-body p-3">
        <div class="animated fadeIn calendar-width" id="eventCalendar" style="margin: 0px auto;">
          <img id="scroll-warn" src="image/Scroll-Down-icon-Home.gif" />
        </div>

        <?php if(isset($calendars_user) AND count($calendars_user)){ ?>
        <div class="animated fadeIn calendar-width" id="eventCalendar_user" style="margin: 0px auto;display: none;">
          <div class="info-box">
            <span class="busy">Занято</span>
            <span class="reservation">Бронь</span>
            <span class="free">Свободен</span>
          </div>
        </div>
        <?php } ?> 
      </div>
    </div>
  </div>
</div>

	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>
 <script src="/catalog/calendar/js/moment.js"></script>
 <script src="/catalog/calendar/js/jquery.eventCalendar.js"></script>
<link href="/catalog/calendar/css/eventCalendar.css?1.0" rel="stylesheet" type="text/css" />
<link href="/catalog/calendar/css/eventCalendar_theme_responsive.css" rel="stylesheet" type="text/css" />
<style>
  .busy_color1{
     background-color:orange;
  }
  .busy_color2{
    background-color:blue;
  }
  .busy_color3{
    background-color:green;
  }
</style>

	<script>
	$(function(){
 		var data = [
      <?php foreach($calendars as $date => $products){ ?>
      <?php foreach($products as $products_id => $event){ ?>
				{ "date": "<?php echo $date; ?> 00:00:00", "busy": "<?php echo $event['busy']; ?>", "title": "<div class=\"product_image\"><img width='100px' src='<?php echo $event['image']; ?>'></div><div class=\"product_text\"><span class='user_name'><?php echo $event['name']; ?></span> - <span class='busy<?php echo $event['busy']; ?>'><?php echo $event['busy_name']; ?></span><?php echo ($event['text']!='') ? '<br><span class=\"event_text\">'.$event['text'].'</span>' : ''; ?></div>", "description": "<?php //echo $event['text']; ?>", "url": "<?php echo $event['href'].'?date='.$date; ?>" }, 
     <?php } ?>
      <?php } ?>
		];
 console.log('Первый календарь');
  
		$('#eventCalendar').eventCalendar({
			jsonData: data,
			eventsjson: 'data.json',
			jsonDateFormat: 'human',
			startWeekOnMonday: false,
			openEventInNewWindow: true,
			dateFormat: 'DD-MM-YYYY',
   msg: 'all',
			showDescription: false,
			locales: {
				locale: "ru",
				txt_noEvents: "Нет свободных ДиДжеев",
				txt_SpecificEvents_prev: "",
				txt_SpecificEvents_after: "события:",
				txt_NextEvents: "Следующие события:",
				txt_GoToEventUrl: "Смотреть",
				moment: {
					"months" : [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
					"Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
					"monthsShort" : [ "Янв", "Фев", "Мар", "Апр", "Май", "Июн",
					"Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ],
					"weekdays" : [ "Воскресенье", "Понедельник","Вторник","Среда","Четверг",
					"Пятница","Суббота" ],
					"weekdaysShort" : [ "Вс","Пн","Вт","Ср","Чт",
					"Пт","Сб" ],
					"weekdaysMin" : [ "Вс","Пн","Вт","Ср","Чт",
					"Пт","Сб" ]
				}
			}
		});
	});
	</script>
 <?php if(isset($calendars_user) AND count($calendars_user)){ ?>
 	<script>
   
	$(function(){
 		var data_user = [
        <?php foreach($calendars_user as $date => $products){ ?>
      <?php foreach($products as $products_id => $event){ ?>
				{ "date": "<?php echo $date; ?> 00:00:00", "busy": "<?php echo $event['busy']; ?>", "title": "<div class=\"product_image\"><img width='100px' src='<?php echo $event['image']; ?>'></div><div class=\"product_text\"><span class='user_name'><?php echo $event['name']; ?></span> - <span class='busy<?php echo $event['busy']; ?>'><?php echo $event['busy_name']; ?></span><?php echo ($event['text']!='') ? '<br><span class=\"event_text\">'.$event['text'].'</span>' : ''; ?></div>", "description": "<?php //echo $event['text']; ?>", "url": "<?php echo $event['href'].'?date='.$date; ?>" }, 
      <?php } ?>
       <?php } ?>
	];
  console.log('Второй календарь');
  
		$('#eventCalendar_user').eventCalendar({
			jsonData: data_user,
			eventsjson: 'data.json',
			jsonDateFormat: 'human',
			startWeekOnMonday: false,
			openEventInNewWindow: true,
			dateFormat: 'DD-MM-YYYY',
   msg: 'user',
   url: '<?php echo $href; ?>',
			showDescription: false,
			locales: {
				locale: "ru",
				txt_noEvents: "Нет свободных ДиДжеев",
				txt_SpecificEvents_prev: "",
				txt_SpecificEvents_after: "события:",
				txt_NextEvents: "Следующие события:",
				txt_GoToEventUrl: "Смотреть",
				moment: {
					"months" : [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь",
					"Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
					"monthsShort" : [ "Янв", "Фев", "Мар", "Апр", "Май", "Июн",
					"Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ],
					"weekdays" : [ "Воскресенье", "Понедельник","Вторник","Среда","Четверг",
					"Пятница","Суббота" ],
					"weekdaysShort" : [ "Вс","Пн","Вт","Ср","Чт",
					"Пт","Сб" ],
					"weekdaysMin" : [ "Вс","Пн","Вт","Ср","Чт",
					"Пт","Сб" ]
				}
			}
		});
	});
	</script>

 <?php } ?>
 
 
   
  <script>
   $(document).ready(function(){
    setTimeout(function(){
     $('#eventCalendar .today a').trigger('click');
     console.log('Текущая дата');
     }, 1000);
   });
   
  </script>