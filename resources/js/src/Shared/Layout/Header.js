import {Button, Dropdown, Menu, Space, Layout} from "antd";
import {AppTitle} from "./Components/AppTitle";
import {Link, usePage} from "@inertiajs/inertia-react";
import {LocaleDropdown} from "./Components/LocaleDropdown";
import {
    ClusterOutlined,
    DownOutlined,
    LogoutOutlined,
    ProfileOutlined,
    UnorderedListOutlined,
    SnippetsOutlined,
    UserOutlined, SplitCellsOutlined,
} from "@ant-design/icons";
import Lang from "../../../translation/lang";
import React from "react";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {useStyles} from "../../Hooks/styles.hook";
import {usePermissions} from "../../Hooks/permissions.hook";

export const Header = () => {
    const {auth} = usePage().props
    const {has, filterPermissionsArray} = usePermissions()
    const {padding} = useStyles()
    const tRoute = useTransRoutes()

    const menuItems = filterPermissionsArray([
        has('project_access') && {
            itemProps: {key: 'projects', icon: <UnorderedListOutlined/>},
            route: 'projects.index',
            label: Lang.get('pages.projects.title')
        },
        has('series_access', 'brand_access') && {
            itemProps: {key: 'series', icon: <SnippetsOutlined/>},
            route: 'pump_series.index',
            label: Lang.get('pages.pump_series.index.title'),
        },
        has('pump_access') && {
            itemProps: {key: 'pumps', icon: <ClusterOutlined/>},
            route: 'pumps.index',
            label: Lang.get('pages.pumps.title')
        },
        has('user_access') && {
            itemProps: {key: 'users', icon: <UserOutlined/>},
            route: 'users.index',
            label: Lang.get('pages.users.title')
        },
        has('project_statistics') && {
            itemProps: {key: 'projects_statistics', icon: <SplitCellsOutlined/>},
            route: 'projects.statistics',
            label: Lang.get('pages.projects.statistics.title')
        }
    ])

    return (
        <Layout.Header style={{...padding.all("0 16px 0"), position: 'fixed', zIndex: 1, width: '100%'}}>
            <div style={{display: 'flex', justifyContent: 'space-between'}}>
                <Space>
                    <AppTitle/>
                </Space>
                <Menu
                    style={{marginLeft: 10, flexGrow: 1}}
                    theme="dark"
                    mode="horizontal"
                >
                    {menuItems.map(item => (
                            <Menu.Item {...item.itemProps}>
                                <Link href={tRoute(item.route)}>
                                    {item.label}
                                </Link>
                            </Menu.Item>
                        )
                    )}
                </Menu>
                <Space>
                    <LocaleDropdown/>
                    {auth.full_name &&
                    <Dropdown
                        key="user-actions" arrow trigger={['click']}
                        overlay={
                            <Menu>
                                <Menu.Item key="profile" icon={<ProfileOutlined/>}>
                                    <Link href={tRoute('profile.index')}>
                                        {Lang.get('pages.profile.title')}
                                    </Link>
                                </Menu.Item>
                                <Menu.Divider/>
                                <Menu.Item
                                    key="logout"
                                    icon={<LogoutOutlined/>}
                                >
                                    <Link method="post" href={tRoute('logout')}>
                                        {Lang.get('pages.email_verification.logout')}
                                    </Link>
                                </Menu.Item>
                            </Menu>
                        }
                    >
                        <Button color={"white"}>
                            {auth?.full_name}<DownOutlined/>
                        </Button>
                    </Dropdown>}
                </Space>
            </div>
        </Layout.Header>
    )
}
