import {
    Layout,
} from "antd";
import React from "react";
import {Footer} from "../Footer";
import {MessagesLayout} from "./MessagesLayout";
import {LocaleLayout} from "./LocaleLayout";
import {useStyles} from "../../Hooks/styles.hook";
import {Header} from "./Header";

const {Content} = Layout;

export const AuthLayout = ({children, header = <Header/>}) => {
    const {minHeight} = useStyles()

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
