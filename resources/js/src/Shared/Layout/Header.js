import {Button, Dropdown, Menu, Space, Layout} from "antd";
import {AppTitle} from "./Components/AppTitle";
import {Link, usePage} from "@inertiajs/inertia-react";
import {
    ClusterOutlined,
    DownOutlined,
    LogoutOutlined, UserSwitchOutlined,
    ProfileOutlined, SnippetsOutlined, SolutionOutlined,
    UnorderedListOutlined, OrderedListOutlined, UserOutlined,
} from "@ant-design/icons";
import React from "react";
import {useStyles} from "../../Hooks/styles.hook";
import {usePermissions} from "../../Hooks/permissions.hook";

export const Header = () => {
    const {auth} = usePage().props
    const {filteredBoolArray} = usePermissions()
    const {padding} = useStyles()

    const menuItems = filteredBoolArray([
        {
            itemProps: {key: 'projects', icon: <UnorderedListOutlined/>},
            route: 'projects.index',
            label: "Проекты"
        }, auth.is_admin && {
            itemProps: {key: 'pump_series', icon: <SnippetsOutlined/>},
            route: 'pump_series.index',
            label: "Бренды и серии"
        }, auth.is_admin && {
            itemProps: {key: 'pumps', icon: <ClusterOutlined/>},
            route: 'pumps.index',
            label: "Насосы"
        }, auth.is_admin && {
            itemProps: {key: 'dealers', icon: <SolutionOutlined/>},
            route: 'dealers.index',
            label: "Дилеры",
        }, auth.is_admin && {
            itemProps: {key: 'contractors', icon: <UserSwitchOutlined/>},
            route: 'contractors.index',
            label: 'Контрагенты',
        }, auth.is_admin && {
            itemProps: {key: 'users', icon: <UserOutlined/>},
            route: 'users.index',
            label: 'Пользователи'
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
                                <Link href={route(item.route)}>
                                    {item.label}
                                </Link>
                            </Menu.Item>
                        )
                    )}
                    {auth.is_admin && <Menu.SubMenu
                        icon={<OrderedListOutlined/>}
                        key='components'
                        title="Компоненты"
                        popupOffset={[0, -5]}
                    >
                        <Menu.Item key='armature'>
                            <Link href={route('armature.index')}>
                                Арматура
                            </Link>
                        </Menu.Item>
                        <Menu.Item key='collectors'>
                            <Link href={route('collectors.index')}>
                                Коллекторы
                            </Link>
                        </Menu.Item>
                        <Menu.Item key='assembly-jobs'>
                            <Link href={route('assembly_jobs.index')}>
                                Работы по сборке
                            </Link>
                        </Menu.Item>
                        <Menu.Item key='chassis'>
                            <Link href={route('chassis.index')}>
                                Рамы
                            </Link>
                        </Menu.Item>
                        <Menu.Item key='control-systems'>
                            <Link href={route('control_systems.index')}>
                                Системы управления
                            </Link>
                        </Menu.Item>
                    </Menu.SubMenu>}
                </Menu>
                <Space>
                    {auth.full_name &&
                    <Dropdown
                        key="user-actions" arrow trigger={['click']}
                        overlay={
                            <Menu>
                                <Menu.Item key="profile" icon={<ProfileOutlined/>}>
                                    <Link href={route('profile.index')}>
                                        Профиль
                                    </Link>
                                </Menu.Item>
                                <Menu.Divider/>
                                <Menu.Item
                                    key="logout"
                                    icon={<LogoutOutlined/>}
                                >
                                    <Link method="post" href={route('logout')}>
                                        Выйти
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
