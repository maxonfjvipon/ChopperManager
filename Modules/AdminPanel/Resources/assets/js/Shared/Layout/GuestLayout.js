import {Layout, Space} from "antd";
import React from "react";
import {MessagesLayout} from "../../../../../../../resources/js/src/Shared/Layout/MessagesLayout";
import {AppTitle} from "../../../../../../../resources/js/src/Shared/Layout/Components/AppTitle";
import {Footer} from "../../../../../../../resources/js/src/Shared/Footer";
import {useStyles} from "../../../../../../../resources/js/src/Hooks/styles.hook";

export const GuestLayout = ({children}) => {
    const {Content, Header} = Layout
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
