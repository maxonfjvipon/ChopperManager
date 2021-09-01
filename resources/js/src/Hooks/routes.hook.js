import {usePage} from "@inertiajs/inertia-react";

export const useTransRoutes = () => {
    const {locales} = usePage().props

    const cur_path = () => window.location.pathname

    const trans_path = (path, locale) => {
        const splitted = path.split('/')
        splitted.splice(1, locales.supported.includes(splitted[1]) ? 1 : 0, locale)
        return splitted.join('/')
    }

    return {
        trans_route: (name, ...params) => {
            console.log('cur locale', locales.current)
            console.log('route', route(name))
            const splitted = route(name, ...params).split('/')
            const segment = splitted[3]
            console.log('segment', segment)
            if (locales.supported.includes(segment)) { // path contains locale
                splitted.splice(3, 1, locales.current)
            }
            console.log('splitted', splitted)
            return splitted.join('/')
        },
        trans_cur_path: (locale) => {
            return trans_path(cur_path(), locale)
        }
    }
}
