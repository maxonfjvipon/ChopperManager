/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import AuthLayout from "./src/Shared/Layout/AuthLayout"

require('./bootstrap');
require('antd/dist/antd.compact.css')
require('../css/app.css')

import React from 'react'
import {render} from 'react-dom'
import {InertiaApp, createInertiaApp} from '@inertiajs/inertia-react'
import {InertiaProgress} from '@inertiajs/progress';

InertiaProgress.init({
    color: '#ED8936',
    // showSpinner: true
});

const app = document.getElementById('app');
const context = require.context("../../Modules", true, /js\/app\.js$/, "lazy");

const resolver = name => {
    let parts = name.split('::')
    let page
    if (parts.length > 1) {
        page = context("./" + parts[0] + '/Resources/assets/js/app.js')
    } else {
        page = import('./src/Pages/' + name)
    }
    return page.then(module => {
        module = module.default[parts[1]]
        module.layout = module.layout || (module => <AuthLayout children={module}/>)
        return module
    })
}

render(<InertiaApp initialPage={JSON.parse(app.dataset.page)} resolveComponent={resolver}/>, app);
