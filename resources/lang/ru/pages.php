<?php

return [
    'change_locale' => 'Сменить язык',
    'login' => [
        'welcome' => 'Добро пожаловать!',
        'email' => 'E-mail',
        'password' => 'Пароль',
        'login' => 'Войти',
        'not_registered' => 'Ещё не зарегистрированы?'
    ],
    'register' => [
        'please_register' => 'Пожалуйста зарегистрируйтесь',
        'organization_name' => 'Наименование организации',
        'main_business' => 'Основная деятельность',
        'itn' => 'ИНН',
        'phone' => 'Контактный телефон',
        'country' => 'Страна',
        'city' => 'Город',
        'postcode' => 'Индекс',
        'first_name' => 'Имя',
        'middle_name' => 'Фамилия',
        'last_name' => 'Отчество',
        'email' => 'E-mail',
        'password' => 'Пароль',
        'password_confirmation' => 'Повторите пароль',
        'register' => 'Заргегистрироваться',
        'already_registered' => 'Уже зарегистрированы?'
    ],
    'email_verification' => [
        'thanks' => 'Спасибо что зарегистрировались',
        'send_again' => 'Отправить ссылку еще раз',
        'text1' => 'Прежде чем приступить к работе, не могли бы вы подтвердить свой адрес электронной почты, перейдя по ссылке, которую мы только что отправили вам по электронной почте?',
        'text2' => 'Если вы не получили электронное письмо, мы с радостью вышлем вам другое. Также на всякий случай проверьте папку "Спам"',
        'logout' => 'Выйти'
    ],
    'dashboard' => [
        'title' => 'Дашборд',
        'select' => 'Подбор насосных установок',
        'marketplace' => 'Маркетплейс',
        'pumps' => 'Насосы'
    ],
    'projects' => [
        'back' => 'Назад к проектам',
        'title' => 'Проекты',
        'index' => [
            'restore' => [
                'title' => 'Восстановить проект?',
                'button' => 'Восстановить'
            ],
            'button' => 'Создать проект',
            'selection' => 'Гидравлический подбор без сохранения',
            'table' => [
                'created_at' => 'Дата создания',
                'name' => 'Наименование',
                'count' => 'Количество подборов',
                'delete' => 'Вы точно хотите удалить проект?'
            ],
        ],
        'show' => [
            'restore' => [
                'title' => 'Восстановить подбор?',
                'button' => 'Восстановить'
            ],
            'save_button' => 'Сохранить изменения',
            'selection' => 'Гидравлический подбор',
            'label' => 'Наименование',
            'table' => [
                'created_at' => 'Дата создания',
                'selected_pump' => 'Обозн. продукта',
                'part_num' => 'Артикул',
                'pressure' => 'Напор, м',
                'consumption' => 'Расход, м³/ч',
                'price' => 'Цена со скидкой',
                'total_price' => 'Итоговая цена со скидкой',
                'power' => 'P₁, кВ',
                'total_power' => 'P итого, кВ',
                'delete' => 'Вы точно хотите удалить подбор?'
            ]
        ],
        'create' => [
            'title' => 'Создать проект',
            'form' => [
                'name' => 'Наименование',
            ],
            'button' => 'Создать'
        ],
        'edit' => [
            'title' => 'Обновить проект',
            'form' => [
                'name' => 'Наименование',
            ],
            'button' => 'Обновить',
        ],
    ],
    'pump_brands' => [
        'index' => [
            'restore' => [
                'title' => 'Восстановить бренд?',
                'button' => 'Восстановить'
            ],
            'title' => 'Бренды',
            'button' => 'Новый бренд',
            'table' => [
                'name' => 'Наименование',
                'delete' => 'Вы точно хотите удалить бренд?'
            ]
        ],
        'create' => [
            'title' => 'Создать бренд',
            'form' => [
                'name' => 'Наименование',
            ],
            'button' => 'Создать'
        ],
        'edit' => [
            'title' => 'Изменить бренд',
            'form' => [
                'name' => 'Наименование',
            ],
            'button' => 'Обновить',
        ],
    ],
    'pump_series' => [
        'back' => 'Назад к сериям',
        'index' => [
            'restore' => [
                'title' => 'Восстановить серию?',
                'button' => 'Восстановить'
            ],
            'title' => 'Бренды и серии',
            'button' => 'Новая серия',
            'table' => [
                'brand' => 'Бренд',
                'name' => 'Наименование',
                'category' => 'Категория',
                'power_adjustment' => 'Встроенное регулирование',
                'applications' => 'Применения',
                'types' => 'Типы',
                'delete' => 'Вы точно хотите удалить серию?'
            ],
        ],
        'create' => [
            'title' => 'Создать серию',
            'button' => 'Создать',
            'form' => [
                'brand' => 'Бренд',
                'name' => 'Наименование',
                'category' => 'Категория',
                'power_adjustment' => 'Встроенное регулирование',
                'applications' => 'Применения',
                'types' => 'Типы'
            ],
        ],
        'edit' => [
            'title' => 'Изменить серию',
            'button' => 'Обновить',
            'form' => [
                'brand' => 'Бренд',
                'name' => 'Наименование',
                'category' => 'Категория',
                'power_adjustment' => 'Встроенное регулирование',
                'applications' => 'Применения',
                'types' => 'Типы'
            ],
        ],
    ],
    'pumps' => [
        'title' => 'Насосы',
        'props' => 'Свойства',
        'back' => 'Назад к насосам',
        'data' => [
            'article_num_main' => 'Артикул основной',
            'article_num_reserve' => 'Артикул резервный',
            'article_num_archive' => 'Артикул архивный',
            'brand' => 'Производитель',
            'series' => 'Серия',
            'name' => 'Наименование',
            'category' => 'Категория',
            'price' => 'Цена',
            'currency' => 'Валюта',
            'weight' => 'Масса, кг',
            'rated_power' => 'P, кВ',
            'rated_current' => 'Ток, A', // fixme
            'connection_type' => 'Тип соединения',
            'fluid_temp_min' => 'Мин.темп.жидк, °C',
            'fluid_temp_max' => 'Макс.темп.жидк, °C',
            'ptp_length' => 'Монтажная длина, мм',
            'dn_suction' => 'ДУ входа',
            'dn_pressure' => 'ДУ выхода',
            'power_adjustment' => 'Встроенное регулирование',
            'connection' => 'Соединение',
            'types' => 'Типы',
            'applications' => 'Применения'
        ],
        'upload' => 'Загрузить насосы',
        'upload_price_lists' => 'Загрузить прайс-листы',
        'errors_title' => 'Ошибки загрузки:',
        'hydraulic_perf' => 'Гидравлическая характеристика',
        'pressure' => 'Напор, м',
        'consumption' => 'Расход, м³/ч',
    ],
    'profile' => [
        'title' => 'Профиль',
        'props' => 'Свойства',
        'back' => 'Назад к насосам',
        'index' => [
            'cards' => [
                'user_info' => 'Обновить информацию о пользователе',
                'password' => 'Сменить пароль'
            ],
            'tab' => 'Информация',
            'organization_name' => 'Наименование организации',
            'main_business' => 'Основная деятельность',
            'itn' => 'ИНН',
            'phone' => 'Контактный телефон',
            'country' => 'Страна',
            'city' => 'Город',
            'postcode' => 'Индекс',
            'currency' => [
                'label' => 'Валюта',
                'tooltip' => 'Валюта влияет на ...'
            ],
            'first_name' => 'Имя',
            'middle_name' => 'Фамилия',
            'last_name' => 'Отчество',
            'email' => 'E-mail',
            'current_password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
            'new_password_confirmation' => 'Повторите новый пароль',
            'save_changes' => 'Сохранить изменения',
            'change_password' => 'Изменить пароль'
        ],
        'discounts' => [
            'tab' => 'Скидки производителей',
            'name' => 'Наименование',
            'discount' => 'Скидка, %'
        ]
    ], // TODO
    'selections' => [
        'back' => [
            'to_selections_dashboard' => 'Назад к дашборду подборов',
            'to_project' => 'Назад к проекту'
        ],
        'dashboard' => [
            'title' => 'Гидравлический подбор',
            'subtitle' => 'Выберите тип подбора',
            'back' => [
                'to_project' => 'Назад к проекту',
                'to_projects' => 'Назад к проектам'
            ],
            'preferences' => [
                'pump' => [
                    'single' => 'Подбор одинарного насоса',
                    'double' => 'Подбор сдвоенного насоса',
                    'borehole' => 'Подбор скважинного насоса',
                    'drainage' => 'Подбор дренажного насоса',
                ],
                'station' => [
                    'water' => 'Подбор станции водоснабжения',
                    'fire' => 'Подбор станции пожаротушения'
                ]
            ],
            'single_prefs' => 'Подбор насоса по парметрам',
            'double_prefs' => 'Подбор сдвоенного насоса по парметрам',
            'water_station_prefs' => 'Подбор станции водоснабжения по параметрам',
            'fire_station_prefs' => 'Подбор пожаротушения водоснабжения по параметрам',
            'single_analogy' => 'Подбор насоса по аналогу',
            'double_analogy' => 'Подбор сдвоенного насоса по аналогу',
            'water_station_analogy' => 'Подбор станции водоснабжения по аналогу',
            'fire_station_analogy' => 'Подбор пожаротушения водоснабжения по аналогу',
        ],
        'single' => [
            'title_show' => 'Просмотр подбор',
            'title_new' => 'Подбор насоса по параметрам',
            'grouping' => 'Группировка по брендам',
            'brands' => 'Бренды',
            'types' => [
                'label' => 'Типы',
                'tooltip' => 'Для серии проверяется наличие всех выбранных типов!'
            ],
            'applications' => [
                'label' => 'Применения',
                'tooltip' => 'Для серии проверяется наличие всех выбранных применений!'
            ],
            'power_adjustments' => 'Встроенное регулирование',
            'fluid_temp' => 'Температура жидкости, °C',
            'pressure' => 'Напор, м',
            'consumption' => 'Расход, м³/ч',
            'limit' => 'Запас/допуск, %',
            'main_pumps_count' => 'Количество основных насосов',
            'backup_pumps_count' => 'Количество резервных насосов',
            'connection_type' => 'Тип соединения',
            'phase' => 'Фаза',
            'range' => [
                'label' => 'Диапазон',
                'custom' => [
                    'label' => 'Произвольный диапазон',
                    'value' => 'Произв.'
                ]
            ],
            'additional_filters' => [
                'checkbox' => 'Использовать доп. фильтры',
                'title' => 'Допольнительные фильтры',
                'apply' => 'Применить',
                'button' => 'Доп.фильтры'
            ],
            'condition' => 'Условие',
            'power_limit' => 'Ограничение мощности насоса',
            'ptp_length_limit' => 'Ограничение монтажной длины',
            'dn_input_limit' => 'Ограничение ДУ входа',
            'dn_output_limit' => 'Ограничение ДУ выхода',
            'power' => 'Мощность, кВ',
            'ptp_length' => 'Монтажная длина',
            'dn_input' => 'ДУ входа',
            'dn_output' => 'ДУ выхода',
            'select' => 'Подобрать',
            'exit' => 'Выйти',
            'add' => 'Добавить к проекту',
            'update' => 'Обновить подбор',
            'selected_pump' => 'Подобранный насос: ',
            'table' => [
                'title' => 'Подобранные насосы',
                'name' => 'Наименование',
                'part_num' => 'Артикул',
                'retail_price' => 'Розничная цена',
                'discounted_price' => 'Цена со скидкой',
                'total_retail_price' => 'Итоговая розничная цена',
                'total_discounted_price' => 'Итоговая цена со скидкой',
                'dn_input' => 'ДУ входа',
                'dn_output' => 'ДУ выхода',
                'power' => 'P, кВ',
                'total_power' => 'P итого, кВ',
                'ptp_length' => 'Монтажная длина, мм'
            ],
        ]
    ]
];
