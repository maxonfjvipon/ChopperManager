import {Layout} from "antd";
import React from "react";
import {Footer} from "../Footer";
import {MessagesLayout} from "./MessagesLayout";
import {LocaleLayout} from "./LocaleLayout";
import {Header} from "./Header";

const {Content} = Layout;

export default function AuthLayout({children, header = <Header/>}) {
    return (
        <MessagesLayout>
            <LocaleLayout>
                <Layout className="main-layout">
                    {header}
                    <Layout className="outer-content-layout">
                        <Content className="inner-content-layout">
                            {children}
                        </Content>
                        <Footer/>
                    </Layout>
                </Layout>
            </LocaleLayout>
        </MessagesLayout>
    );
}
