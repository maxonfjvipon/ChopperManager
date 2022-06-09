import {Layout} from "antd";
import React from "react";
import {Footer} from "../Footer";
import {MessagesLayout} from "./MessagesLayout";
import {Header} from "./Header";
import {LocaleLayout} from "./LocaleLayout";

const {Content} = Layout;

export default function AuthLayout({children}) {
    return (
        <LocaleLayout>
            <MessagesLayout>
                <Layout className="main-layout">
                    <Header/>
                    <Layout className="outer-content-layout">
                        <Content className="inner-content-layout">
                            {children}
                        </Content>
                        <Footer/>
                    </Layout>
                </Layout>
            </MessagesLayout>
        </LocaleLayout>
    );
}
