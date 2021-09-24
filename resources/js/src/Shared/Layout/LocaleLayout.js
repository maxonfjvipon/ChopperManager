import {usePage} from "@inertiajs/inertia-react";
import Lang from "../../../translation/lang";

export const LocaleLayout = ({children}) => {
    const {locales} = usePage().props
    Lang.setLocale(locales.current)

    return children
}
