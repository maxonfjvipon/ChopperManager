<?php

return [
    'change_locale' => 'Change locale',
    'login' => [
        'welcome' => 'Welcome!',
        'email' => 'E-mail',
        'password' => 'Password',
        'login' => 'Login',
        'not_registered' => 'Not registered yet?'
    ],
    'register' => [
        'please_register' => 'Please register',
        'organization_name' => 'Name of the organization',
        'main_business' => 'Main business',
        'itn' => 'Individual taxpayer number',
        'phone' => 'Phone',
        'country' => 'Country',
        'city' => 'City',
        'postcode' => 'Postcode',
        'first_name' => 'First name',
        'middle_name' => 'Middle name',
        'last_name' => 'Last name',
        'email' => 'E-mail',
        'password' => 'Password',
        'password_confirmation' => 'Confirm your password',
        'register' => 'Register',
        'already_registered' => 'Already registered?'
    ],
    'email_verification' => [
        'thanks' => 'Thank you for registering',
        'send_again' => 'Send email again',
        'text1' => 'Before you get started, could you please confirm your email address, by clicking on the link that we just sent you by email?',
        'text2' => 'If you have not received an email, we will be happy to send you another one. Also, just in case, check the "Spam" folder',
        'logout' => 'Logout'
    ],
    'dashboard' => [
        'title' => 'Dashboard',
        'select' => 'Selection of pumping stations',
        'marketplace' => 'Marketplace',
        'pumps' => 'Pumps'
    ],
    'projects' => [
        'back' => 'Back to projects',
        'title' => 'Projects',
        'index' => [
            'restore' => [
                'title' => 'Restore project?',
                'button' => 'Restore'
            ],
            'button' => 'New project',
            'selection' => 'Hydraulic selection without saving',
            'table' => [
                'created_at' => 'Created at',
                'name' => 'Name',
                'count' => 'Selections count',
                'delete' => 'Are you sure you want to delete the project?'
            ],
        ],
        'show' => [
            'restore' => [
                'title' => 'Restore selection?',
                'button' => 'Restore'
            ],
            'save_button' => 'Save changes',
            'selection' => 'Hydraulic selection',
            'label' => 'Name',
            'table' => [
                'created_at' => 'Created at',
                'selected_pump' => 'Product name',
                'part_num' => 'Article number',
                'pressure' => 'Delivery head, m',
                'consumption' => 'Volume flow, m³/h',
                'price' => 'Discounted price',
                'total_price' => 'Total discounted price',
                'power' => 'P₁, kW',
                'total_power' => 'P total, kW',
                'delete' => 'Are you sure you want to delete the selection?'
            ]
        ],
        'create' => [
            'title' => 'Create project',
            'form' => [
                'name' => 'Name',
            ],
            'button' => 'Create'
        ],
        'edit' => [
            'title' => 'Update project',
            'form' => [
                'name' => 'Name',
            ],
            'button' => 'Update project',
        ],
    ],
    'pump_brands' => [
        'index' => [
            'restore' => [
                'title' => 'Restore brand?',
                'button' => 'Restore'
            ],
            'title' => 'Brands',
            'button' => 'New brand',
            'table' => [
                'name' => 'Name',
                'delete' => 'Are you sure you want to delete the brand?'
            ]
        ],
        'create' => [
            'title' => 'Create brand',
            'form' => [
                'name' => 'Name',
            ],
            'button' => 'Create'
        ],
        'edit' => [
            'title' => 'Update brand',
            'form' => [
                'name' => 'Name',
            ],
            'button' => 'Update brand',
        ],
    ],
    'pump_series' => [
        'back' => 'Back to series',
        'index' => [
            'restore' => [
                'title' => 'Restore series?',
                'button' => 'Restore'
            ],
            'title' => 'Series',
            'button' => 'New series',
            'table' => [
                'brand' => 'Brand',
                'name' => 'Name',
                'category' => 'Category',
                'power_adjustment' => 'El.power adjustment',
                'applications' => 'Applications',
                'types' => 'Types',
                'delete' => 'Are you sure you want to delete the series?'
            ],
        ],
        'create' => [
            'title' => 'Create series',
            'button' => 'Create',
            'form' => [
                'brand' => 'Brand',
                'name' => 'Name',
                'category' => 'Category',
                'power_adjustment' => 'El.power adjustment',
                'applications' => 'Applications',
                'types' => 'Types'
            ],
        ],
        'edit' => [
            'title' => 'Edit series',
            'button' => 'Update',
            'form' => [
                'brand' => 'Brand',
                'name' => 'Name',
                'category' => 'Category',
                'power_adjustment' => 'El.power adjustment',
                'applications' => 'Applications',
                'types' => 'Types'
            ],
        ],
    ],
    'pumps' => [
        'title' => 'Pumps',
        'props' => 'Properties',
        'back' => 'Back to pumps',
        'data' => [
            'article_num_main' => 'Main article number',
            'article_num_reserve' => 'Reserve article number',
            'article_num_archive' => 'Archive article number',
            'brand' => 'Brand',
            'series' => 'Series',
            'name' => 'Name',
            'category' => 'Category',
            'price' => 'Price',
            'currency' => 'Currency',
            'weight' => 'Weight, kg',
            'rated_power' => 'P, kW',
            'rated_current' => 'Rated current, A', // fixme
            'connection_type' => 'Connection',
            'fluid_temp_min' => 'Min. fluid temp, °C',
            'fluid_temp_max' => 'Max. fluid temp, °C',
            'ptp_length' => 'Port-to-port length, mm',
            'dn_suction' => 'Suction side DN',
            'dn_pressure' => 'Pressure side DN',
            'power_adjustment' => 'El. power adjustment',
            'connection' => 'Mains connection',
            'types' => 'Types',
            'applications' => 'Applications'
        ],
        'upload' => 'Upload pumps',
        'upload_price_lists' => 'Upload price lists',
        'errors_title' => 'Import errors:',
        'hydraulic_perf' => 'Hydraulic performance',
        'pressure' => 'Delivery head, m',
        'consumption' => 'Volume flow, m³/h',
    ],
    'profile' => [
        'title' => 'Profile',
        'index' => [
            'tab' => 'User info',
            'cards' => [
                'user_info' => 'Update user info',
                'password' => 'Change password'
            ],
            'organization_name' => 'Name of the organization',
            'main_business' => 'Main business',
            'itn' => 'Individual taxpayer number',
            'phone' => 'Phone',
            'country' => 'Country',
            'city' => 'City',
            'postcode' => 'Postcode',
            'currency' => [
                'label' => 'Currency',
                'tooltip' => 'Currency affects on ...'
            ],
            'first_name' => 'First name',
            'middle_name' => 'Middle name',
            'last_name' => 'Last name',
            'email' => 'E-mail',
            'current_password' => 'Current password',
            'new_password' => 'New password',
            'new_password_confirmation' => 'Confirm new password',
            'save_changes' => 'Save changes',
            'change_password' => 'Change password'
        ],
        'discounts' => [
            'tab' => "Producer's discounts",
            'name' => 'Name',
            'discount' => 'Discount, %'
        ]
    ],
    'selections' => [
        'back' => [
            'to_selections_dashboard' => 'Back to selections dashboard',
            'to_project' => 'Back to project'
        ],
        'dashboard' => [
            'title' => 'Hydraulic selections',
            'subtitle' => 'Choose selection type',
            'back' => [
                'to_project' => 'Back to project',
                'to_projects' => 'Back to projects'
            ],
            'preferences' => [
                'pump' => [
                    'single' => 'Single pump selection',
                    'double' => 'Double pump selection',
                    'borehole' => 'Borehole pump selection',
                    'drainage' => 'Drainage pump selection',
                ],
                'station' => [
                    'water' => 'Water supply pumping station selection',
                    'fire' => 'Fire extinguishing pumping station selection'
                ]
            ],
            'single_prefs' => 'Single pump selection by preferences',
            'double_prefs' => 'Double pump selection by preferences',
            'water_station_prefs' => 'Watter supply pumping station selection by preferences',
            'fire_station_prefs' => 'Fire extinguishing pumping station selection by preferences',
            'single_analogy' => 'Single pump selection by analogy',
            'double_analogy' => 'Double pump selection by analogy',
            'water_station_analogy' => 'Watter supply pumping station selection by analogy',
            'fire_station_analogy' => 'Fire extinguishing pumping station selection by analogy',
        ],
        'single' => [
            'title_show' => 'Viewing the selection',
            'title_new' => 'Single pump selection by preferences',
            'grouping' => 'Grouping by brands',
            'brands' => 'Brands',
            'types' => [
                'label' => 'Types',
                'tooltip' => 'The presence of all the selected types for the series is checked!'
            ],
            'applications' => [
                'label' => 'Applications',
                'tooltip' => 'The presence of all the selected applications for the series is checked!'
            ],
            'power_adjustments' => 'Power adjustments',
            'fluid_temp' => 'Fluid temperature, °C',
            'pressure' => 'Delivery head, m',
            'consumption' => 'Volume flow, m³/h',
            'limit' => 'Reserve/deviation, %',
            'main_pumps_count' => 'Main pumps count',
            'backup_pumps_count' => 'Reserve pumps count',
            'connection_type' => 'Connection type',
            'range' => [
                'label' => 'Range',
                'custom' => [
                    'label' => 'Custom range',
                    'value' => 'Custom'
                ]
            ],
            'additional_filters' => [
                'checkbox' => 'Use additional filters',
                'title' => 'Additional filters',
                'apply' => 'Apply',
                'button' => 'More filters'
            ],
            'phase' => 'Mains connection',
            'condition' => 'Condition',
            'power_limit' => 'Limiting the pump power',
            'ptp_length_limit' => 'Limiting the port-to-port length',
            'dn_input_limit' => 'Limiting the suction side DN',
            'dn_output_limit' => 'Limiting the pressure side DN',
            'power' => 'Power, kW',
            'ptp_length' => 'Port-to-port length',
            'dn_input' => 'Suction side DN',
            'dn_output' => 'Pressure side DN',
            'select' => 'Select',
            'exit' => 'Exit',
            'add' => 'Add to project',
            'update' => 'Update selection',
            'selected_pump' => 'Selected pump: ',
            'table' => [
                'title' => 'Selected pumps',
                'name' => 'Name',
                'part_num' => 'Article number',
                'retail_price' => 'Retail price',
                'discounted_price' => 'Discounted price',
                'total_retail_price' => 'Total retail price',
                'total_discounted_price' => 'Total discounted price',
                'dn_input' => 'Suction side DN',
                'dn_output' => 'Pressure side DN',
                'power' => 'P, kW',
                'total_power' => 'P total, kW',
                'ptp_length' => 'Port-to-port length, mm'
            ],
        ]
    ]
];
