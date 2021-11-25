import React from 'react';
import {Col, Input, Row, Tabs} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {useStyles} from "../../Hooks/styles.hook";
import {Selection} from "../../Shared/Inputs/Selection";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {ItemsForm} from "../../Shared/ItemsForm";
import {DiscountsTab} from "./Components/DiscountsTab";
import Lang from '../../../translation/lang'
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {RoundedCard} from "../../Shared/Cards/RoundedCard";
import {Header} from "../../Shared/Layout/Header";
import {Container} from "../../Shared/ResourcePanel/Index/Container";
import {JustifiedRow} from "../../Shared/JustifiedRow";
import {BoxFlex} from "../../Shared/Box/BoxFlex";

const Index = () => {
    const {reducedAntFormItemClassName, margin} = useStyles()
    const {rules} = useInputRules()
    const {user, businesses, countries, currencies, discounts} = usePage().props
    const {tRoute} = useTransRoutes()

    // console.log(discounts)

    const userdata = user.data
    const profileFormName = 'profile-form'
    const changePasswordForm = 'change-password-form'

    const items = [
        {
            values: {
                name: 'organization_name',
                label: Lang.get('pages.profile.index.organization_name'),
                rules: [rules.required],
                initialValue: userdata.organization_name,
            }, input: <Input/>
        },
        {
            values: {
                name: 'business_id',
                label: Lang.get('pages.profile.index.main_business'),
                rules: [rules.required],
                initialValue: userdata.business_id
            },
            input: <Selection options={businesses}/>
        },
        {
            values: {
                name: 'itn',
                label: Lang.get('pages.profile.index.itn'),
                rules: rules.itn,
                initialValue: userdata.itn,
            }, input: <Input/>
        },
        {
            values: {
                name: 'phone',
                label: Lang.get('pages.profile.index.phone'),
                rules: rules.phone,
                initialValue: userdata.phone,
            },
            input: <Input readOnly/>,
        },
        {
            values: {
                name: 'country_id',
                label: Lang.get('pages.profile.index.country'),
                rules: [rules.required],
                initialValue: userdata.country_id
            },
            input: <Selection options={countries}/>
        },
        {
            values: {name: 'city', label: Lang.get('pages.profile.index.city'), initialValue: userdata.city, rules: [rules.required]},
            input: <Input/>
        },
        {
            values: {
                name: 'currency_id',
                label: Lang.get('pages.profile.index.currency.label'),
                initialValue: userdata.currency_id,
                // tooltip: Lang.get('pages.profile.index.currency.tooltip'),
                rules: [rules.required]
            },
            input: <Selection options={currencies}/>
        },
        {
            values: {
                name: 'first_name',
                label: Lang.get('pages.profile.index.first_name'),
                rules: [rules.required, rules.max(255)],
                initialValue: userdata.first_name
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'middle_name',
                label: Lang.get('pages.profile.index.middle_name'),
                rules: [rules.required, rules.max(255)],
                initialValue: userdata.middle_name
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'last_name',
                label: Lang.get('pages.profile.index.last_name'),
                rules: [rules.max(255)],
                initialValue: userdata.last_name
            }, input: <Input/>
        },
        {
            values: {
                name: 'email',
                label: Lang.get('pages.profile.index.email'),
                rules: rules.email,
                initialValue: userdata.email,
                className: reducedAntFormItemClassName
            },
            input: <Input readOnly/>
        },
    ]

    const updatePasswordItems = [
        {
            values: {name: 'current_password', label: Lang.get('pages.profile.index.current_password'), rules: []},
            input: <Input.Password/>
        },
        {
            values: {name: 'password', label: Lang.get('pages.profile.index.new_password'), rules: []},
            input: <Input.Password/>
        },
        {
            values: {
                name: 'password_confirmation',
                label: Lang.get('pages.profile.index.new_password_confirmation'),
                rules: [],
                className: reducedAntFormItemClassName
            },
            input: <Input.Password/>
        },
    ]

    const updateProfileHandler = async body => {
        Inertia.post(tRoute('profile.update'), body, {
            preserveScroll: true,
        })
    }

    const changePasswordHandler = body => {
        Inertia.post(tRoute('profile.password.change'), body, {
            preserveScroll: true
        })
    }

    return (
        <Container>
            <Tabs centered type="card" defaultActiveKey="user-info">
                <Tabs.TabPane tab={Lang.get('pages.profile.index.tab')} key="user-info">
                    <JustifiedRow>
                        <Col xs={22} sm={20} md={18} lg={16} xl={11} xxl={9}>
                            <Row>
                                <Col xs={24}>
                                    <RoundedCard
                                        type="inner"
                                        title={Lang.get('pages.profile.index.cards.user_info')}
                                    >
                                        <ItemsForm
                                            layout="horizontal"
                                            labelSpan={{xxl: 7, xl: 10, lg: 10, md: 12, sm: 12}}
                                            items={items}
                                            onFinish={updateProfileHandler}
                                            name={profileFormName}
                                        />
                                    </RoundedCard>
                                    <BoxFlex style={margin.top(16)}>
                                        <PrimaryButton htmlType="submit" form={profileFormName}>
                                            {Lang.get('pages.profile.index.save_changes')}
                                        </PrimaryButton>
                                    </BoxFlex>
                                </Col>
                                <Col xs={24}>
                                    <RoundedCard
                                        type="inner"
                                        title={Lang.get('pages.profile.index.cards.password')}
                                        style={margin.top(16)}
                                    >
                                        <ItemsForm
                                            layout="horizontal"
                                            labelSpan={{xxl: 6, xl: 10, lg: 10, md: 12, sm: 12}}
                                            items={updatePasswordItems}
                                            onFinish={changePasswordHandler} name={changePasswordForm}
                                        />
                                    </RoundedCard>
                                    <BoxFlex style={margin.top(16)}>
                                        <PrimaryButton htmlType="submit" form={changePasswordForm}>
                                            {Lang.get('pages.profile.index.change_password')}
                                        </PrimaryButton>
                                    </BoxFlex>
                                </Col>
                            </Row>
                        </Col>
                    </JustifiedRow>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.profile.discounts.tab')} key="producers-discounts">
                    <DiscountsTab discounts={discounts}/>
                </Tabs.TabPane>
            </Tabs>
        </Container>
    )
}

Index.layout = page => <AuthLayout children={page} header={<Header/>}/>

export default Index
