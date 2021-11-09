import {
    Layout,
} from "antd";
import React from "react";
import {Header} from "./Header";
import {MessagesLayout} from "../../../../../../../resources/js/src/Shared/Layout/MessagesLayout";
import {Footer} from "../../../../../../../resources/js/src/Shared/Footer";

const {Content} = Layout;

export const AuthLayout = ({children, header = <Header title="Admin Panel"/>}) => {
    return (
        <MessagesLayout>
            <Layout className="main-layout">
                {header}
                <Layout className="outer-content-layout">
                    <Content className="inner-content-layout">
                        {children}
                    </Content>
                    <Footer/>
                </Layout>
            </Layout>
        </MessagesLayout>
    );
}
