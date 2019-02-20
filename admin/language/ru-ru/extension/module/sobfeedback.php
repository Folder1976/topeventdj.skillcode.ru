<?php
//standart module
$_['heading_title'] = 'Конструктор форм sobFeedback';
$_['text_module']      = 'Модули';
$_['text_success']     = 'Настройки успешно изменены!';
$_['text_edit']        = 'Настройки модуля';
$_['text_module_name']      = 'Имя модуля';
$_['text_modal']      = 'Выводить модальным окном';
$_['text_anyplace']      = 'Выводить в любом месте';
$_['text_paste_controller']      = 'Вставьте в файл контроллера код:';
$_['text_paste_temlate']      = 'Вставьте в файл темплейта код:';
$_['text_form_name']      = 'Имя формы (тайтл)';
$_['text_form_success']      = 'Уведомление об успешной отправке';
$_['text_error_field_name']      = 'Название поля должно быть заполнено';
$_['text_error_field_option']      = 'Опции поля должны быть заполнены';
$_['text_error_name_module']      = 'Имя модуля должно быть заполнено';
$_['text_error_empty_field'] = 'Форма должна иметь хотя бы одно поле';
$_['placeholder_email'] = 'Оставьте пустым чтобы использовать системный';
$_['error_permission'] = 'У Вас нет прав для управления данным модулем!';
$_['error_anyplace'] = 'Нужно сначала сохранить модуль чтобы активировать функцию вывода модуля в любом месте!';
$_['entry_status'] = 'Статус';
$_['entry_comment'] = 'Выводить комментарий';
$_['entry_name'] = 'Выводить имя';
$_['entry_phone'] = 'Выводить телефон';
$_['entry_email'] = 'Отправлять на E-mail'; 
$_['text_success'] = 'Успешно'; 
$_['button_add'] = 'Добавить поле'; 
$_['button_delete'] = 'Удалить поле'; 
$_['button_list'] = 'Фидбек лист'; 
$_['button_save'] = 'Сохранить'; 
$_['button_cancel'] = 'Вернутся к списку модулей'; 
$_['text_modal_button'] = 'Название кнопки';

//2.0
$_['text_help_regexp_quant'] = 'Кванторы позволяют определить часть шаблона, которая должна повторяться несколько раз подряд. Например,
               если вы хотите выяснить, содержит ли документ строку из от 10 до 20 (включительно) букв «a»,
               то можно использовать этот шаблон.
               По умолчанию кванторы — «жадные». Поэтому квантор +, означающий «один или больше раз»,
                будет соответствовать максимально возможному значению. Иногда это вызывает проблемы, и тогда
                 вы можете сказать квантору перестать быть жадным (стать «ленивым»), используя специальный модификатор.';
$_['text_help_regexp_circle'] = 'Приведенный выше код начинается с поиска любых символов, кроме пробела ([^\s]*), за которыми следует q.
               Затем парсер достигает «смотрящего вперед» утверждения. Это автоматически делает предшествующий элемент
               (символ, группу или символьный класс) условным — он будет соответствовать шаблону, только если утверждение верно.
                В нашем случае, утверждение является отрицательным (?!), т. е. оно будет верным, если то, что в нем ищется, не будет найдено.
                Итак, парсер проверяет несколько следующих символов по предложенному шаблону (werty). Если они найдены, то утверждение ложно,
                а значит символ q будет «проигнорирован», т. е. не будет соответствовать шаблону. Если же werty не найдено, то утверждение верно,
                 и с q все в порядке. Затем продолжается поиск любых символов, кроме пробела ([^\s]*).';
$_['text_help_regexp_classes'] = 'Символьные классы в регулярных выражениях соответствуют сразу некоторому набору символов. Например,
                \d соответствует любой цифре от 0 до 9 включительно, \w соответствует буквам и цифрам, а \W — всем символам,
                 кроме букв и цифр. Шаблон, идентифицирующий буквы, цифры и пробел, выглядит так';
$_['text_help_regexp_anchors'] = 'Якоря в регулярных выражениях указывают на начало или конец чего-либо.
                Например, строки или слова. Они представлены определенными символами.
                К примеру, шаблон, соответствующий строке, начинающейся с цифры, должен иметь такой вид.
                Здесь символ ^ обозначает начало строки. Без него шаблон соответствовал бы любой строке, содержащей цифру.';
$_['text_help_regexp_quant_title'] = 'Кванторы';
$_['text_help_regexp_circle_title'] = 'Утверждения';
$_['text_help_regexp_classes_title'] = 'Символьные классы';
$_['text_help_regexp_anchors_title'] = 'Якоря';
$_['text_help_regexp_description'] = 'Описание';
$_['text_help_regexp_short_description'] = 'Краткое Описание';
$_['text_help_regexp_name'] = 'Имя';
$_['text_help_regexp_descr_all'] = 'Эти шаблоны предназначены для ознакомительных целей и основательно 
                не проверялись. Используйте их с осторожностью и предварительно 
                тестируйте';
$_['text_help_regexp_html_tags'] = 'HTML теги';
$_['text_help_regexp_length'] = 'От 8 до 15 символов с минимум одной цифрой, одной 
                      заглавной и одной строчной буквой (полезно для 
                      паролей).';
$_['text_help_regexp_code_color'] = 'Шестнадцатиричный код цвета';
$_['text_help_regexp_1_50'] = 'Любое число от 1 до 50 включительно';
$_['text_help_regexp_format_file'] = 'Имя файла jpg, gif или png';
$_['text_help_regexp_format_date'] = 'Дата (напр., 21/3/2006)';
$_['text_help_regexp_format_number'] = 'Буквы, числа и знаки переноса';
$_['text_help_regexp_helper'] = 'Привиденные ниже шабьлоны не лимитируют вас и не задают функциональные рамки модуля sobFeedback.
                  С помощью регулярных выражений вы можете написать практически любую проверку валидации.';
$_['text_field_validation_regexp_templates_tab'] = 'Шаблоны';
$_['text_field_validation_regexp_instructions_tab'] = 'Инструкции';
$_['text_field_please_add'] = 'Добавьте поля в свою форму';
$_['text_field_type_text'] = 'Text';
$_['text_field_type_email'] = 'Email';
$_['text_field_type_tel'] = 'Phone number';
$_['text_field_type_password'] = 'Password';
$_['text_field_type_textarea'] = 'Textarea';
$_['text_field_type_date'] = 'Date and time';
$_['text_field_type_checkbox'] = 'Checkbox';
$_['text_field_type_radio'] = 'Radio button';
$_['text_field_type_select'] = 'Select';
$_['text_field_type_file'] = 'Скачать файл';
$_['text_field_type_custom_text'] = 'Произвольный текст';
$_['text_field_type_text_type'] = 'Тип Поля';
$_['text_field_name_text'] = 'Имя поля';
$_['text_field_status_disabled'] = 'Отключен';
$_['text_field_status_enabled'] = 'Включен';
$_['text_setting_form_general'] = 'Настройки модуля';
$_['text_setting_form_fields'] = 'Настройки полей';
$_['text_field_status'] = 'Статус поля';
$_['text_basic_setting'] = 'Основные настройки';
$_['text_specified_setting'] = 'Дополнительные настройки';
$_['text_email_subject_placeholder'] = 'По умолчанию - имя формы';
$_['text_email_subject'] = 'Тема письма админу';
$_['text_message_link'] = 'Отправлено со страницы';
$_['text_capcha'] = 'Использовать CAPCHA';
$_['text_recapcha'] = 'Google reCAPCHA Ключ';
$_['text_recapcha_get_key'] = 'Получить ключ';
$_['text_template_form'] = 'Шаблон';
$_['text_field_name'] = 'Имя Поля';
$_['text_field_type'] = 'Тип Поля';
$_['text_field_sort'] = 'Порядок сортировки';
$_['text_field_description'] = 'Описание';
$_['text_field_options'] = 'Опции';
$_['text_field_options_placeholder'] = 'опция1:опция2:опция3 (через двоиточие)';
$_['text_field_placeholder'] = 'Placeholder поля';
$_['text_field_validation_setting'] = 'Настройки валидации';
$_['text_field_validation_not_empty'] = 'Не пустое';
$_['text_field_validation_error'] = 'Текст ошибки';
$_['text_field_validation_regexp'] = 'regexp (регулярное выражение)';
$_['text_field_validation_regexp_templates'] = 'Как использовать?';
$_['text_field_validation_regexp_value'] = 'Значение регулярного выражения';
$_['text_field_validation_symbol_length'] = 'Количество символов';
$_['text_field_validation_min_length'] = 'Минимум';
$_['text_field_validation_max_length'] = 'Максимум';
$_['text_field_select_file'] = 'Выбрать файл (название не должно содержать латинские буквы)';
$_['text_field_custom_text'] = 'Произвольный текст';
$_['text_field_reload_file'] = 'Перезагрузить файл';
$_['text_field_your_file'] = 'Ваш файл';
$_['text_help_mask_modal_title'] = 'Примеры использования маски в модуле sobFeedback';
$_['text_help_mask_modal_left'] = 'Пример';
$_['text_help_mask_modal_right'] = 'Результат';
$_['text_help_mask_modal_body'] = '<p>Под символом "9" маска понимает любую одну цифру от 0 до 9. <br>
          Также латинская буква "a" подразумавает ввод одного символа<br>
          Символ "*" позволяет ввести одну любую букву или цифру<br>
          Подробнее читайте на сайте плагина по <a href="http://digitalbush.com/projects/masked-input-plugin/" target="_blank">ссылке</a></p>';
$_['text_field_your_nofile'] = 'Нет файла';
$_['text_field_chose_file'] = 'Выбрать файл';
$_['text_validate_form_error_modal'] = 'Ошибка сохоанения!';
$_['text_validate_form_error_modal2'] = 'Поля обозначенны звездочкой (*) обязательны для заполнения<br>пожалуйста проверьте все поля';
$_['text_field_mask_input'] = 'Маска ввода';
$_['text_field_title_helper_show'] = 'Показывать описание как подсказку';
$_['text_field_mask_placeholder'] = '9 = один символ: +9(999) 999-99-99';
$_['text_field_date_setting'] = 'Выберите дату';
$_['text_field_date_calendar'] = 'Дата (календарь)';
$_['text_field_date_clock'] = 'Время';
$_['text_field_datetime'] = 'Дата и время';
$_['text_field_date_diapason'] = 'Диапазон';
$_['text_download_pdf_regexp'] = 'Скачать PDF Документацию';
$_['text_field_new_neme'] = 'Новое пустое поле Id:';
//2.0

// table
$_['table_fiald_name'] = 'Текст кнопки';
$_['table_fiald_type'] = 'Тип поля';
$_['table_fiald_status'] = 'Статус поля';
$_['table_fiald_required'] = 'Обязятельно';
$_['table_placeholder_option'] = 'Вариант1:Вариант2:Вариант3';
//list
$_['error_nomodule'] = 'Пока нечего настраивать, создайте форму';
$_['button_create_form'] = 'Создать форму';
$_['button_text_confirm'] = 'Вы уверены?';
$_['button_delete'] = 'Удалить выбраные сообщения';
$_['text_feedback_list'] = 'Фидбек лист (Принятые сообщения)';
$_['text_filter_name_form'] = 'Фильтр по названию формы';
$_['text_filter_text_form'] = 'Поиск по тексту форм';
$_['text_filter_all_form'] = 'Все формы';
$_['text_filter_status'] = 'Фильтр по статусу';
$_['text_filter_allstatus'] = 'Все статусы';
$_['text_status_noread'] = 'Не прочитан';
$_['text_status_read'] = 'Прочитан';
$_['button_filter'] = 'Фильтровать';
$_['text_date'] = 'Дата';
$_['text_name_form'] = 'Название формы';
$_['text_page_send'] = 'Пришло из страницы';
$_['text_page_link'] = 'Ссылка';
$_['text_more'] = 'Детальнее';
$_['text_nomassage'] = 'Пока еще никто не писал';
$_['text_entry_text'] = 'Введите текст';
$_['text_setting_form'] = 'Настроить форму';
//popup
$_['text_massage_number'] = 'Сообщение №';
$_['text_popup_value'] = 'Значение';
$_['text_popup_data'] = 'Данные';
$_['text_popup_date'] = 'Дата отправления';
$_['text_popup_close'] = 'Закрыть';
$_['text_setting_form_style'] = 'Персонализация';
$_['text_custom_css'] = 'Пользовательский CSS';
$_['text_custom_js'] = 'Пользовательский JS';
$_['text_name_button_send'] = 'Текст кнопки отправки';

//header
$_['text_notif_feedbacks'] = 'Фидбек сообщения';