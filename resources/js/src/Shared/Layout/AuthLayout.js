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
                <Layout style={minHeight("100vh")}>
                    {header}
                    <Layout style={{marginTop: 64}} className="site-layout">
                        {/*<Sider theme={"light"} collapsedWidth={0} trigger={null} collapsible*/}
                        {/*       collapsed={collapsed}>*/}
                        {/*    <Menu*/}
                        {/*        // theme={"dark"}*/}
                        {/*        mode="inline"*/}
                        {/*    >*/}
                        {/*        {menuItems.map(item => (*/}
                        {/*            <Menu.Item {...item.itemProps}>*/}
                        {/*                <Link href={item.href}>*/}
                        {/*                    {item.label}*/}
                        {/*                </Link>*/}
                        {/*            </Menu.Item>*/}
                        {/*        ))}*/}
                        {/*    </Menu>*/}
                        {/*</Sider>*/}
                        <Layout>
                            <Content className={"auth-content-layout"}>
                                {children}
                                {/*<Breadcrumb style={{ margin: '16px 0' }}>*/}
                                {/*    <Breadcrumb.Item>User</Breadcrumb.Item>*/}
                                {/*    <Breadcrumb.Item>Bill</Breadcrumb.Item>*/}
                                {/*</Breadcrumb>*/}
                            </Content>
                            <Footer/>
                        </Layout>
                    </Layout>
                </Layout>
            </LocaleLayout>
        </MessagesLayout>
    );
}
