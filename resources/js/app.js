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
// const context = require.context("../../Modules", true, /Pages\/(.+)\.js$/, "lazy");
const context = require.context("../../Modules", true, /js\/app\.js$/, "lazy");

const resolver = name => {
    let parts = name.split('::')
    let page
    if (parts.length > 1) {
        page = context("./" + parts[0] + '/Resources/assets/js/app.js')
    } else {
        page = import('./src/Pages/' + name)
    }
    console.log(page)
    return page.then(module => {
        console.log(parts[1])
        module = module.default[parts[1]]
        module.layout = module.layout || (module => <AuthLayout children={module}/>)
        return module
    })
}

render(<InertiaApp initialPage={JSON.parse(app.dataset.page)} resolveComponent={resolver}/>, app);
//
// createInertiaApp({
//     resolve: name => {
//         let page
//         let parts = name.split('::')
//         switch (parts[0]) {
//             case "AdminPanel":
//                 page = require(`~/AdminPanel/Resources/assets/js/Pages/${parts[1]}`)
//                 break
//             case "Pump":
//                 page = require(`~/Pump/Resources/assets/js/Pages/${parts[1]}`)
//                 break
//             case "Core":
//                 page = require(`~/Core/Resources/assets/js/Pages/${parts[1]}`)
//                 break
//             case "Auth":
//                 page = require(`~/Auth/Resources/assets/js/Pages/${parts[1]}`)
//                 break
//             case "User":
//                 page = require(`~/User/Resources/assets/js/Pages/${parts[1]}`)
//                 break
//             case "PumpProducer":
//                 page = require(`~/PumpProducer/Resources/assets/js/Pages/${parts[1]}`)
//                 break
//             default:
//                 page = require('./src/Pages/' + name).default
//         }
//         page.layout = page => <AuthLayout children={page}/>
//         return page
//     },
//     setup({el, App, props}) {
//         render(<App {...props} />, el)
//     },
// })
