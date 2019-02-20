<?php
// Heading
$_['heading_title']         					= 'Сбербанк Эквайринг';
$_['text_edit']                     	= 'Редактирование';

// Text
$_['text_payment']          					= 'Оплата';
$_['text_success']          					= 'Настройки модуля обновлены!';
$_['text_sberbank_acquiring']         = '<a href="http://sberbank.ru/ru/s_m_business/bankingservice/acquiring" target="_blank"><img src="view/image/payment/sberbank_acquiring.png" alt="Сбербанк Эквайринг" title="Сбербанк Эквайринг"/></a>';

// Entry
$_['sberbank_acquiring_name']                         = 'Оплата банковской картой';
$_['entry_sberbank_acquiring_name']                   = 'Название по умолчанию';
$_['help_sberbank_acquiring_name']                    = 'Название метода оплаты на странице оформления заказа';

$_['entry_sberbank_acquiring_username']               = 'Имя пользователя:';
$_['help_sberbank_acquiring_username']                = 'Имя пользователя для работы с REST протоколом. Как правило, имеет следующий формат: user-api';

$_['entry_sberbank_acquiring_password']               = 'Пароль:';
$_['help_sberbank_acquiring_password']                = 'Пароль в системе для REST протокола.';

$_['entry_sberbank_acquiring_rest_url']               = 'Режим работы:';
$_['help_sberbank_acquiring_rest_url']                = 'В зависимости от режима работы, используется соответствующий URL для запросов по REST протоколу Сбербанка';
$_['tip_sberbank_acquiring_rest_url']                = '<b>Внимание</b>: Тестовый режим предназначен для проверки работы оплаты. В данном режиме оплата возможна только тестовыми картами';

$_['sberbank_acquiring_modes_test_name']              = 'Тестовый режим';               
$_['sberbank_acquiring_modes_prod_name']              = 'Рабочий режим';               

$_['sberbank_acquiring_order_status_before_nochange'] = 'Не менять';
$_['tip_sberbank_acquiring_order_status_before_id']   = '<b>Внимание</b>: в данном случае, заказа не будет видно ни в админ-панели, ни в личном кабинете пользователя до тех пор, пока он не произведет оплату.';

$_['entry_sberbank_acquiring_order_status_before_id'] = 'Статус заказа до оплаты:';
$_['help_sberbank_acquiring_order_status_before_id']  = 'Выбранный статус устанавливается перед оплатой заказа.';

$_['entry_sberbank_acquiring_order_status_id']        = 'Статус заказа после оплаты:';
$_['help_sberbank_acquiring_order_status_id']         = 'Выбранный статус устанавливается после оплаты заказа.';

$_['entry_sberbank_acquiring_geo_zone_id']            = 'Географическая зона:';
$_['help_sberbank_acquiring_geo_zone_id']             = 'Географическая зона, для которой доступен данный метод оплаты.';

$_['entry_sberbank_acquiring_status']                 = 'Статус:';
$_['help_sberbank_acquiring_status']                  = 'Статус.';

$_['entry_sberbank_acquiring_sort_order']             = 'Порядок сортировки:';
$_['help_sberbank_acquiring_sort_order']              = 'Порядок сортировки в списке методов оплаты.';

$_['entry_sberbank_acquiring_ccy_select']             = 'Выберите валюту счета:';
$_['help_sberbank_acquiring_ccy_select']              = 'Валюта, в которой будет производиться оплата.';

// Error
$_['error_permission'] = 'У Вас нет прав для управления этим модулем!';
$_['error_username']   = 'Отсутствует имя пользователя для REST протокола';
$_['error_password']   = 'Отсутствует пароль для REST протокола';
$_['error_rest_url']   = 'Не указан URL REST протокола';
