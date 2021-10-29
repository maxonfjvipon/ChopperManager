import {Button, Menu, Space, Layout} from "antd";
import {Link} from "@inertiajs/inertia-react";
import {
    UnorderedListOutlined,
} from "@ant-design/icons";
import {Inertia} from "@inertiajs/inertia";
import React from "react";
import {AppTitle} from "../Components/AppTitle";
import {useStyles} from "../../../Hooks/styles.hook";

export const Header = ({title = "Admin Panel"}) => {
    const {padding} = useStyles()

    const menuItems =[
        {
            itemProps: {key: 'tenants', icon: <UnorderedListOutlined/>},
            route: 'admin.tenants.index',
            label: 'Tenants',
        },
    ]

    return (
        <Layout.Header style={{...padding.all("0 16px 0"), position: 'fixed', zIndex: 1, width: '100%' }}>
            <div style={{display: 'flex', justifyContent: 'space-between'}}>
                <Space>
                    <AppTitle title={title}/>
                </Space>
                <Menu
                    style={{marginLeft: 10, flexGrow: 1}}
                    theme="dark"
                    mode="horizontal"
                >
                    {menuItems.map(item => (
                        <Menu.Item {...item.itemProps}>
                            <Link href={route(item.route)}>
                                {item.label}
                            </Link>
                        </Menu.Item>
                    ))}
                </Menu>
                <Space>
                    <Button color="white" onClick={() => {
                        Inertia.post(route('admin.logout'))
                    }}>
                        Logout
                    </Button>
                </Space>
            </div>
        </Layout.Header>
    )
}
