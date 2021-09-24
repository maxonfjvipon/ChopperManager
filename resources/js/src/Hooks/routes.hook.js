import {usePage} from "@inertiajs/inertia-react";
import * as url from "url";

export const useTransRoutes = () => {
    const {locales} = usePage().props

    const tRoute = (name, ...params) => {
        const splitUrl = route(name, ...params).split("/")
        const locale = splitUrl[3] // locale from url

        splitUrl.splice(3, locales.supported.includes(locale) ? 1 : 0)
        if (locales.default !== locales.current) {
            splitUrl.splice(3, 0, locales.current)
        }

        return splitUrl.join('/')
    }

    return {
        tRoute
    }
}
