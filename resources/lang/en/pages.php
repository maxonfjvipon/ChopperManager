<?php

return [
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
        'area' => 'Area',
        'city' => 'City',
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
        'title' => 'Projects',
        'index' => [
            'new' => [
                'button' => 'New project',
                'title' => 'Enter the name of the new project',
                'label' => 'Name',
                'placeholder' => 'Enter the name...',
                'cancel' => 'Cancel',
                'create' => 'Create'
            ],
            'selection' => 'Hydraulic selection without saving',
            'table' => [
                'created_at' => 'Created at',
                'name' => 'Name',
                'count' => 'Selections count',
                'delete' => 'Are you sure you want to delete the project?'
            ],
        ],
        'show' => [
            'save_changes' => 'Save changes',
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
        ]
    ],
    'pumps' => [
        'title' => 'Pumps',
        'data' => [
            'part_num_main' => 'Main article number',
            'part_num_backup' => 'Reserve article number',
            'part_num_archive' => 'Archive article number',
            'producer' => 'Brand',
            'series' => 'Series',
            'name' => 'Name',
            'category' => 'Category',
            'price' => 'Price',
            'currency' => 'Currency',
            'weight' => 'Weight, kg',
            'power' => 'P, kW',
            'amperage' => 'Rated current, A', // fixme
            'connection_type' => 'Connection',
            'min_fluid_temp' => 'Min. fluid temp, °C',
            'max_fluid_temp' => 'Max. fluid temp, °C',
            'between_axes_dist' => 'Between axes dist, mm',
            'dn_input' => 'Suction side DN',
            'dn_output' => 'Pressure side DN',
            'regulation' => 'Built-in regulation',
            'phase' => 'Current phase',
            'types' => 'Types',
            'applications' => 'Applications'
        ],
        'upload' => 'Upload pumps',
        'hydraulic_perf' => 'Hydraulic performance',
        'pressure' => 'Delivery head, m',
        'consumption' => 'Volume flow, m³/h',
    ],
    'profile' => [
        'title' => 'Profile',
        'index' => [
            'tab' => 'User info',
            'organization_name' => 'Name of the organization',
            'main_business' => 'Main business',
            'itn' => 'Individual taxpayer number',
            'phone' => 'Phone',
            'area' => 'Area',
            'city' => 'City',
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
        'dashboard' => [
            'title' => 'Hydraulic selections',
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
            'producers' => 'Brands',
            'types' => [
                'label' => 'Types',
                'tooltip' => 'The presence of all the selected types for the series is checked!'
            ],
            'applications' => [
                'label' => 'Applications',
                'tooltip' => 'The presence of all the selected applications for the series is checked!'
            ],
            'regulations' => 'Built-in regulations',
            'fluid_temp' => 'Fluid temperature, °C',
            'pressure' => 'Delivery head, m',
            'consumption' => 'Volume flow, m³/h',
            'limit' => 'deviation, %',
            'main_pumps_count' => 'Main pumps count',
            'backup_pumps_count' => 'Reserve pumps count',
            'connection_type' => 'Connection',
            'phase' => 'Current phase',
            'condition' => 'Condition',
            'power_limit' => 'Limiting the pump power',
            'between_axes_limit' => 'Limiting the between axes distance',
            'dn_input_limit' => 'Limiting the suction side DN',
            'dn_output_limit' => 'Limiting the pressure side DN',
            'power' => 'Power, kW',
            'between_axes_dist' => 'Between axes distance',
            'dn_input' => 'Suction side DN',
            'dn_output' => 'Pressure side DN',
            'select' => 'Select',
            'exit' => 'Exit',
            'add' => 'Add to project',
            'update' => 'Update selection',
            'selected_pump' => 'Selected pump: ',
            'table' => [
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
                'between_axes_dist' => 'Between axes distance'
            ],
        ]
    ]
];
