import {Breadcrumb, Dropdown, Layout, Menu, message, PageHeader} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {ProfileOutlined, LogoutOutlined, DownOutlined, HomeOutlined} from "@ant-design/icons";
import {Link, usePage} from "@inertiajs/inertia-react";
import React from "react";
import {PrimaryButton} from "../Buttons/PrimaryButton";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {MessagesLayout} from "./MessagesLayout";

export const Authenticated = ({title, children, backTo, breadcrumbs = []}) => {
    const {Content, Footer} = Layout
    const {backgroundColorWhite, fullWidth, textAlignCenter, margin, padding} = useStyles()
    const {auth} = usePage().props
    const {breadcrumb} = useBreadcrumbs()

    const routes = [
        {
            path: '/dashboard',
            breadcrumbName: 'Дашборд',
            icon: <HomeOutlined/>,
        },
        ...breadcrumbs
    ]

    return (
        <MessagesLayout>
            <Layout>
                <PageHeader
                    onBack={backTo ? () => window.history.back() : null}
                    title={"PUMP MANAGER"}
                    subTitle={title}
                    extra={[
                        <Dropdown key="user-actions" arrow trigger={['click']} overlay={
                            <Menu>
                                <Menu.Item key="profile" icon={<ProfileOutlined/>}>
                                    <Link href={route('users.profile')}>Профиль</Link>
                                </Menu.Item>
                                <Menu.Divider/>
                                <Menu.Item key="logout" icon={<LogoutOutlined/>}>
                                    <Link method="post" href={route('logout')}>Выйти</Link>
                                </Menu.Item>
                            </Menu>
                        }>
                            <PrimaryButton ghost>
                                {auth?.username}<DownOutlined/>
                            </PrimaryButton>
                        </Dropdown>
                    ]}
                    breadcrumb={{routes}}
                    breadcrumbRender={(props, origin) => <Breadcrumb
                        itemRender={(_route, params, routes, paths) => {
                            const last = routes.indexOf(_route) === routes.length - 1;
                            return last
                                ? <span>{_route.icon || ""} {_route.breadcrumbName}</span>
                                : <Link href={_route.path}>{_route.icon || ""} {_route.breadcrumbName}</Link>
                        }}
                        routes={origin.props.routes}
                    />}
                />
                <Content style={{...backgroundColorWhite, padding: 10}}>
                    {children}
                </Content>
                {/*<Footer style={{...textAlignCenter, position: 'fixed', bottom: 0, ...fullWidth}}>*/}
                {/*    © Trunnikov M.V., 2021*/}
                {/*</Footer>*/}
            </Layout>
        </MessagesLayout>
    )
}
