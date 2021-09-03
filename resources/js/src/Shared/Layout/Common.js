import {Breadcrumb, Button, Dropdown, Layout, Menu, message, PageHeader, Typography, Divider} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {ProfileOutlined, LogoutOutlined, DownOutlined, HomeOutlined, TranslationOutlined} from "@ant-design/icons";
import {Link, usePage} from "@inertiajs/inertia-react";
import React, {useEffect} from "react";
import {MessagesLayout} from "./MessagesLayout";
import Lang from "../../../translation/lang";

export const Common = ({title, children, backTo, breadcrumbs = []}) => {
    const {Content, Footer} = Layout
    const {backgroundColorWhite, fullWidth, textAlignCenter, margin, padding} = useStyles()
    const {auth, locales} = usePage().props
    // const {breadcrumb} = useBreadcrumbs()

    // const routes = [
    //     {
    //         path: '/dashboard',
    //         breadcrumbName: 'Дашборд',
    //         icon: <HomeOutlined/>,
    //     },
    //     ...breadcrumbs
    // ]

    useEffect(() => {
        console.log(locales.current)
        console.log('somebody', locales.current_localized)
    }, [])

    return (
        <MessagesLayout>
            <Layout>
                <PageHeader
                    onBack={backTo ? () => window.history.back() : null}
                    title={title
                        ? <Link
                            href={route('dashboard')}
                            style={{color: 'black'}}>
                            Pump Manager
                        </Link>
                        : "Pump Manager"}
                    subTitle={title}
                    extra={[
                        <Dropdown key="lang-change" arrow trigger={['click']} overlay={
                            <Menu>
                                {locales.supported.map(locale => (
                                    <Menu.Item key={locale}>
                                        <Typography.Link href={locales.current_localized[locale]}>
                                            {locale.toUpperCase()}
                                        </Typography.Link>
                                    </Menu.Item>
                                ))}
                            </Menu>
                        }>
                            <Button>
                                <TranslationOutlined/>
                            </Button>
                        </Dropdown>,
                        auth.username && <Dropdown key="user-actions" arrow trigger={['click']} overlay={
                            <Menu>
                                <Menu.Item key="profile" icon={<ProfileOutlined/>}>
                                    <Link href={route('users.profile')}>{Lang.get('pages.profile.title')}</Link>
                                </Menu.Item>
                                <Menu.Divider/>
                                <Menu.Item key="logout" icon={<LogoutOutlined/>}>
                                    <Link method="post" href={route('logout')}>{Lang.get('pages.email_verification.logout')}</Link>
                                </Menu.Item>
                            </Menu>
                        }>
                            <Button>
                                {auth?.username}<DownOutlined/>
                            </Button>
                        </Dropdown>
                    ]}
                    // breadcrumb={{routes}}
                    // breadcrumbRender={(props, origin) => <Breadcrumb
                    //     itemRender={(_route, params, routes, paths) => {
                    //         const last = routes.indexOf(_route) === routes.length - 1;
                    //         return last
                    //             ? <span>{_route.icon || ""} {_route.breadcrumbName}</span>
                    //             : <Link href={_route.path}>{_route.icon || ""} {_route.breadcrumbName}</Link>
                    //     }}
                    //     routes={origin.props.routes}
                    // />
                    // }
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
