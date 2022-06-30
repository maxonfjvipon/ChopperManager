import React from 'react';
import {Col, Row, Tabs} from "antd";
import Lang from "../../../../../../resources/js/translation/lang";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
// import {DiscountsTab} from "../Components/DiscountsTab";
import {UserInfoTab} from "../Components/UserInfoTab";
import {ChangePasswordTab} from "../Components/ChangePasswordTab";

export default function Profile() {
    const tabs = [
        {name: "Профиль", key: "user-info", comp: <UserInfoTab/> },
        {name: "Изменить пароль", key: "change-password", comp: <ChangePasswordTab/>},
        // {name: Lang.get('pages.profile.discounts.tab'), key: "producers-discounts", comp: <DiscountsTab/>},
    ]

    return (
        <IndexContainer>
            <Tabs centered type="card" defaultActiveKey={tabs[0].key}>
                {tabs.map(tab => (
                    <Tabs.TabPane tab={tab.name} key={tab.key}>
                        <Row justify="space-around">
                            <Col xs={22} sm={20} md={18} lg={16} xl={11} xxl={9}>
                                {tab.comp}
                            </Col>
                        </Row>
                    </Tabs.TabPane>
                ))}
            </Tabs>
        </IndexContainer>
    )
}
