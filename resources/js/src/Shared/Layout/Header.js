import {Menu, Layout} from "antd";
import {Link} from "@inertiajs/inertia-react";
import {UnorderedListOutlined, UserOutlined,} from "@ant-design/icons";
import React from "react";
import {useStyles} from "../../Hooks/styles.hook";

export const Header = () => {
    const {padding} = useStyles()

    const menuItems = [
        {
            itemProps: {key: 'projects', icon: <UnorderedListOutlined/>},
            route: 'projects.index',
            label: "Проекты"
        },
        // {
        //     itemProps: {key: 'series', icon: <UserOutlined/>},
        //     route: 'partners.index',
        //     label: "Контрагенты",
        // },
        // has('pump_access') && {
        //     itemProps: {key: 'pumps', icon: <ClusterOutlined/>},
        //     route: 'pumps.index',
        //     label: Lang.get('pages.pumps.title')
        // },
        // has('user_access') && {
        //     itemProps: {key: 'users', icon: <UserOutlined/>},
        //     route: 'users.index',
        //     label: Lang.get('pages.users.title')
        // },
    ]

    return (
        <Layout.Header style={{position: 'fixed', zIndex: 1, width: '100%'}}>
            <Menu
                style={{flexGrow: 1}}
                theme="dark"
                mode="horizontal"
            >
                {menuItems.map(item => (
                        <Menu.Item {...item.itemProps}>
                            <Link href={route(item.route)}>
                                {item.label}
                            </Link>
                        </Menu.Item>
                    )
                )}
            </Menu>
        </Layout.Header>
    )
}
