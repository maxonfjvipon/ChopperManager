import {Layout, Space} from "antd";
import React from "react";
import {Header} from "antd/es/layout/layout";
import {MessagesLayout} from "./MessagesLayout";
import {useStyles} from "../../Hooks/styles.hook";
import {Footer} from "../Footer";
import {BoxFlexSpaceBetween} from "../Box/BoxFlexSpaceBetween";
import {LocaleDropdown} from "./Components/LocaleDropdown";
import {AppTitle} from "./Components/AppTitle";
import {LocaleLayout} from "./LocaleLayout";

export const GuestLayout = ({children}) => {
    const {Content} = Layout
    const {backgroundColorWhite, padding, minHeight} = useStyles()

    return (
        <MessagesLayout>
            <LocaleLayout>
                <Layout style={minHeight('100vh')}>
                    <Header style={padding.all("0 16px 0")}>
                        <BoxFlexSpaceBetween>
                            <Space>
                                <AppTitle/>
                            </Space>
                            <Space>
                                <LocaleDropdown/>
                            </Space>
                        </BoxFlexSpaceBetween>
                    </Header>
                    <Content style={backgroundColorWhite}>
                        {children}
                    </Content>
                    <Footer/>
                </Layout>
            </LocaleLayout>
        </MessagesLayout>
    )
}
