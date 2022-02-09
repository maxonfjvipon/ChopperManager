import Lang from "../../../../../../resources/js/translation/lang";
import {Drawer, List, Space, Tabs} from "antd";
import {DiscountsTab} from "./DiscountsTab";
import {useEffect} from "react";
import {UserProjectsTab} from "./UserProjectsTab";

export const UserStatisticsDrawer = ({user, visible, setVisible}) => {
    const tabs = [
        {
            name: Lang.get('pages.users.index.statistics.discounts.tab'),
            key: 'discounts',
            comp: <DiscountsTab userInfo={user}/>
        },
        // {
        //     name: Lang.get('pages.users.index.statistics.analytics.tab'),
        //     key: 'analytics',
        //     comp: <DiscountsTab/>
        // },
        {
            name: Lang.get('pages.users.index.statistics.projects.tab'),
            key: 'projects',
            comp: <UserProjectsTab projects={user?.projects}/>
        }
    ]

    useEffect(() => {
        if (user) {
            setVisible(true)
        }
    }, [user])

    return (
        <Drawer
            width={"60%"}
            placement="right"
            title={user?.full_name}
            visible={visible}
            onClose={() => {
                setVisible(false)
            }}
        >
            <Tabs type="card" defaultActiveKey={tabs[0].key}>
                {tabs.map(tab => (
                    <Tabs.TabPane tab={tab.name} key={tab.key}>
                        {tab.comp}
                    </Tabs.TabPane>
                ))}
            </Tabs>
        </Drawer>
    )
}
