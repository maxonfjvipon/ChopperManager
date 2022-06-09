import {ConfigProvider} from "antd";
import ruRU from 'antd/lib/locale/ru_RU'

export const LocaleLayout = ({children}) => {
    return <ConfigProvider locale={ruRU}>
        {children}
    </ConfigProvider>
}
