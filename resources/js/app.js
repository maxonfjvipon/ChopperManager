/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');
// require('antd/dist/antd.compact.css')
require('antd/dist/antd.compact.css')
// require('antd/dist/antd.less')
// require('antd/dist/antd.dark.css')
require('../css/app.css')

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding src to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import React from 'react'
import {render} from 'react-dom'
import {createInertiaApp} from '@inertiajs/inertia-react'
import {InertiaProgress} from '@inertiajs/progress';
import AuthLayout from './src/Shared/Layout/AuthLayout'

InertiaProgress.init({
    color: '#ED8936',
    showSpinner: true
});

createInertiaApp({
    resolve: name => {
        return import(`./src/Pages/${name}`).then(module => {
            module = module.default
            module.layout = module.layout || (module => <AuthLayout children={module}/>)
            return module
        })
    },
    setup({el, App, props}) {
        render(<App {...props} />, el)
    },
})
