/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');
require('antd/dist/antd.compact.css')
// require('antd/dist/antd.css')
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
import {InertiaApp, createInertiaApp} from '@inertiajs/inertia-react'
import {InertiaProgress} from '@inertiajs/progress';

InertiaProgress.init({
    color: '#ED8936',
    // showSpinner: true
});

// const app = document.getElementById('app');

// render(
//     <InertiaApp
//         initialPage={JSON.parse(app.dataset.page)}
//         resolveComponent={async name => {
//             let parts = name.split('::')
//             if (parts.length > 1) {
//                 if (parts[0] === "AdminPanel")
//                     return import(`~/AdminPanel/Resources/assets/js/${parts[1]}`).then(module => module.default)
//                 if (parts[0] === "Pump")
//                     return import(`~/Pump/Resources/assets/js/${parts[1]}`).then(module => module.default)
//                 if (parts[0] === "PumpProducer")
//                     return import(`~/PumpProducer/Resources/assets/js/${parts[1]}`).then(module => module.default)
//             } else {
//                 return import('./src/Pages/' + name).then(module => module.default)
//             }
//         }}
//     />,
//     app
// );

createInertiaApp({
    resolve: name => {
        let parts = name.split('::')
        if (parts.length > 1) {
            switch (parts[0]) {
                case "AdminPanel": return require(`~/AdminPanel/Resources/assets/js/Pages/${parts[1]}`)
                case "Pump": return require(`~/Pump/Resources/assets/js/Pages/${parts[1]}`)
                case "Core": return require(`~/Core/Resources/assets/js/Pages/${parts[1]}`)
                case "Auth": return require(`~/Auth/Resources/assets/js/Pages/${parts[1]}`)
                case "User": return require(`~/User/Resources/assets/js/Pages/${parts[1]}`)
                case "PumpProducer": return require(`~/PumpProducer/Resources/assets/js/Pages/${parts[1]}`)
            }
        } else {
            return require('./src/Pages/' + name)
        }
    },
    setup({el, App, props}) {
        render(<App {...props} />, el)
    },
})
