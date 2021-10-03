import {usePage} from "@inertiajs/inertia-react";
import Lang from "../../../translation/lang";
import {ConfigProvider} from "antd";
import ruRU from 'antd/lib/locale/ru_RU'
import enUS from 'antd/lib/locale/en_US'

export const LocaleLayout = ({children}) => {
    const {locales} = usePage().props
    Lang.setLocale(locales.current)

    const _locales = {
        'ru': ruRU,
        'en': enUS,
    }

    return <ConfigProvider locale={_locales[locales.current]}>
        {children}
    </ConfigProvider>
}
