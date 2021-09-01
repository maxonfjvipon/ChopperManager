import Lang from '../../translation/lang'
import {usePage} from "@inertiajs/inertia-react";

export const useLang = () => {
    Lang.setLocale(usePage().props.locales.current)
    return Lang
}
