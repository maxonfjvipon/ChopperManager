import {BoxFlexSpaceBetween} from "../Box/BoxFlexSpaceBetween";
import {Button, Dropdown, Menu, Space, Layout} from "antd";
import {AppTitle} from "./Components/AppTitle";
import {Link, usePage} from "@inertiajs/inertia-react";
import {LocaleDropdown} from "./Components/LocaleDropdown";
import {
    ClusterOutlined,
    DownOutlined,
    LogoutOutlined,
    ProfileOutlined,
    UnorderedListOutlined
} from "@ant-design/icons";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../translation/lang";
import React from "react";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {useStyles} from "../../Hooks/styles.hook";

export const Header = () => {
    const menuKey = 'menu'

    const getMenuKey = () => {
        let _key = localStorage.getItem(menuKey)
        if (_key == null) {
            _key = 'projects'
            localStorage.setItem(menuKey, _key)
        }
        return _key
    }

    const {auth} = usePage().props
    const {tRoute} = useTransRoutes()
    const {minHeight, padding} = useStyles()

    const menuItems = [
        {
            itemProps: {key: 'projects', icon: <UnorderedListOutlined/>},
            route: 'projects.index',
            label: Lang.get('pages.projects.title')
        },
        {
            itemProps: {key: 'pumps', icon: <ClusterOutlined/>},
            route: 'pumps.index',
            label: Lang.get('pages.pumps.title')
        },
    ]

    const menuClickHandler = event => {
        localStorage.setItem(menuKey, event.key)
    }

    return (
        <Layout.Header style={{...padding.all("0 16px 0"), position: 'fixed', zIndex: 1, width: '100%' }}>
            <BoxFlexSpaceBetween>
                <Space>
                    <AppTitle/>
                </Space>
                <Menu
                    theme="dark"
                    mode="horizontal"
                    // defaultSelectedKeys={getMenuKey()}
                    // onClick={menuClickHandler}
                >
                    {menuItems.map(item => (
                        <Menu.Item {...item.itemProps}>
                            <Link href={tRoute(item.route)}>
                                {item.label}
                            </Link>
                        </Menu.Item>
                    ))}
                </Menu>
                {/*<Space>*/}

                {/*</Space>*/}
                <Space>
                    <LocaleDropdown/>
                    {auth.full_name &&
                    <Dropdown
                        key="user-actions" arrow trigger={['click']}
                        overlay={
                            <Menu>
                                <Menu.Item key="profile" icon={<ProfileOutlined/>}>
                                    {/*<div onClick={event => {*/}
                                    {/*    console.log("click", tRoute('users.profile'))*/}
                                    {/*    Inertia.get(tRoute('users.profile'))*/}
                                    {/*}}>*/}
                                    {/*    {Lang.get('pages.profile.title')}*/}
                                    {/*</div>*/}
                                    <Link href={tRoute('users.profile')}>
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
            </BoxFlexSpaceBetween>
        </Layout.Header>
    )
}
