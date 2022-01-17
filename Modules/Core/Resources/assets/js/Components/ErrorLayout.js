import {Layout, Space} from "antd";
import React from "react";
import {Header} from "antd/es/layout/layout";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {AppTitle} from "../../../../../../resources/js/src/Shared/Layout/Components/AppTitle";
import {Footer} from "../../../../../../resources/js/src/Shared/Footer"

export default function ErrorLayout({children}) {
    const {minHeight, padding, backgroundColorWhite} = useStyles()

    return (
        <Layout style={minHeight('100vh')}>
            <Header style={padding.all("0 16px 0")}>
                <Space>
                    <AppTitle/>
                </Space>
            </Header>
            <Layout.Content style={backgroundColorWhite}>
                {children}
            </Layout.Content>
            <Footer/>
        </Layout>
    )
}
