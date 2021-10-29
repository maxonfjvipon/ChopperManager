import {
    Layout,
} from "antd";
import React from "react";
import {MessagesLayout} from "../MessagesLayout";
import {Footer} from "../../Footer";
import {Header} from "./Header";

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
