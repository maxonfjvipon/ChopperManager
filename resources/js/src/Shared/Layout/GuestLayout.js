import {Layout, Space} from "antd";
import React from "react";
import {Header} from "antd/es/layout/layout";
import {MessagesLayout} from "./MessagesLayout";
import {useStyles} from "../../Hooks/styles.hook";
import {Footer} from "../Footer";
import {AppTitle} from "./Components/AppTitle";

export const GuestLayout = ({children}) => {
    const {Content} = Layout
    const {backgroundColorWhite, padding, minHeight} = useStyles()

    return (
        <MessagesLayout>
            <Layout style={minHeight('100vh')}>
                <Header style={padding.all("0 16px 0")}>
                    <Space>
                        <AppTitle/>
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
