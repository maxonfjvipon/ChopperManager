import {Layout, Space} from "antd";
import React from "react";
import {Header} from "antd/es/layout/layout";
import {MessagesLayout} from "../MessagesLayout";
import {LocaleLayout} from "../LocaleLayout";
import {AppTitle} from "../Components/AppTitle";
import {Footer} from "../../Footer";
import {useStyles} from "../../../Hooks/styles.hook";

export const GuestLayout = ({children}) => {
    const {Content} = Layout
    const {backgroundColorWhite, padding, minHeight} = useStyles()

    return (
        <MessagesLayout>
            <Layout style={minHeight('100vh')}>
                <Header style={padding.all("0 16px 0")}>
                    <Space>
                        <AppTitle title="Admin Panel"/>
                    </Space>
                </Header>
                <Content style={backgroundColorWhite}>
                    {children}
                </Content>
                <Footer/>
            </Layout>
        </MessagesLayout>
    )
}
