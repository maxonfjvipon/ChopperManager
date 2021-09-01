/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

// require('./bootstrap');
require('antd/dist/antd.compact.css')
// require('antd/dist/antd.less')
// require('antd/dist/antd.dark.css')
require('../css/app.css')

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import React from 'react'
import {render} from 'react-dom'
import {createInertiaApp, InertiaApp} from '@inertiajs/inertia-react'
import { InertiaProgress } from '@inertiajs/progress';

InertiaProgress.init({
    color: '#ED8936',
    // showSpinner: true
});

createInertiaApp({
    resolve: name => require(`./src/Pages/${name}`),
    setup({el, App, props}) {
        render(<App {...props} />, el)
    },
})
