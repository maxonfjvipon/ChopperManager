import React, {useContext, useEffect, useRef, useState} from 'react';
import {Card, Col, Divider, Form, Input, InputNumber, message, Row, Table, Tabs} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import {useForm} from "antd/es/form/Form";
import {Inertia} from "@inertiajs/inertia";
import {useAreasCities} from "../../Hooks/components/areas-cities.hook";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {useStyles} from "../../Hooks/styles.hook";
import {Selection} from "../../Shared/Inputs/Selection";
import {Authenticated} from "../../Shared/Layout/Authenticated";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {ItemsForm} from "../../Shared/ItemsForm";
import {DiscountsTab} from "./Components/DiscountsTab";

const Index = () => {
    const {
        setAreasWithCities,
        areasOptions,
        citiesOptions,
        setAreaValue,
        citiesToShow
    } = useAreasCities()

    const {fullWidth, reducedAntFormItemClassName} = useStyles()
    const {rules} = useInputRules()

    const {user, businesses, areasWithCities, discounts} = usePage().props
    const userdata = user.data

    useEffect(() => {
        // console.log(discounts)
    }, [discounts])

    useEffect(() => {
        setAreasWithCities(areasWithCities)
    }, [areasWithCities])

    useEffect(() => {
        setAreaValue(userdata.area_id)
    }, [userdata])


    const profileFormName = 'profile-form'
    const changePasswordForm = 'change-password-form'

    const items = [
        {
            values: {
                name: 'organization_name',
                label: 'Наименование организации',
                rules: [rules.required],
                initialValue: userdata.organization_name,
                // className: reducedAntFormItemClassName,
            }, input: <Input/>
        },
        {
            values: {
                name: 'business_id',
                label: 'Основная деятельность',
                rules: [rules.required],
                initialValue: userdata.business_id
            },
            input: <Selection options={businesses}/>
        },
        {
            values: {
                name: 'inn', label: 'ИНН', rules: rules.inn, initialValue: userdata.inn || "",
            }, input: <Input/>
        },
        {
            values: {
                name: 'phone', label: 'Контактный телефон', rules: rules.phone, initialValue: userdata.phone,
            },
            input: <Input placeholder="+7XXXXXXXXXX" readOnly/>,
        },
        {
            values: {name: 'area', label: 'Область', rules: [rules.required], initialValue: userdata.area_id},
            input: <Selection {...areasOptions}/>
        },
        {
            values: {name: 'city_id', label: 'Город', rules: rules.city(citiesToShow), initialValue: userdata.city_id},
            input: <Selection {...citiesOptions}/>
        },
        {values: {name: 'first_name', label: 'Имя', rules: [rules.required, rules.max(255)], initialValue: userdata.first_name}, input: <Input readOnly/>},
        {values: {name: 'middle_name', label: 'Фамилия', rules: [rules.required, rules.max(255)], initialValue: userdata.middle_name}, input: <Input readOnly/>},
        {values: {name: 'last_name', label: 'Отчество', rules: [rules.max(255)], initialValue: userdata.last_name}, input: <Input/>},
        {
            values: {
                name: 'email', label: 'E-mail', rules: rules.email, initialValue: userdata.email,
                className: reducedAntFormItemClassName
            },
            input: <Input readOnly/>
        },
    ]

    const updatePasswordItems = [
        {values: {name: 'current_password', label: 'Текущий пароль', rules: []}, input: <Input.Password/>},
        {values: {name: 'password', label: 'Новый пароль', rules: []}, input: <Input.Password/>},
        {
            values: {
                name: 'password_confirmation',
                label: 'Повторите новый пароль',
                rules: [],
                className: reducedAntFormItemClassName
            },
            input: <Input.Password/>
        },
    ]

    const updateProfileHandler = async body => {
        Inertia.post(route('users.update'), body)
    }

    const changePasswordHandler = body => {
        Inertia.post(route('users.password.change'), body)
    }

    return (
        <Tabs centered type="card" defaultActiveKey="user-info">
            <Tabs.TabPane tab="Информация" key="user-info">
                <Row justify="space-around" align="middle" gutter={[30, 30]}>
                    <Col md={24} lg={20} xl={15} xxl={13}>
                        <Card
                            style={{...fullWidth, borderRadius: 10}}
                            actions={[
                                // <SecondaryButton type="default" disabled>
                                //     Удалить пользователя
                                // </SecondaryButton>,
                                // <div style={{paddingLeft: 10, paddingRight: 10}}>
                                //     <PrimaryButton style={fullWidth} htmlType="submit" form={profileFormName}>
                                //         Сохранить изменения
                                //     </PrimaryButton>
                                // </div>
                                <PrimaryButton htmlType="submit" form={profileFormName}>
                                    Сохранить изменения
                                </PrimaryButton>
                            ]}
                        >
                            <ItemsForm layout="horizontal" labelSpan={6} items={items}
                                       onFinish={updateProfileHandler} name={profileFormName}/>
                        </Card>
                    </Col>
                    <Col md={24} lg={20} xl={15} xxl={13}>
                        <Card
                            style={{...fullWidth, borderRadius: 10}}
                            actions={[
                                // <div style={{paddingLeft: 10, paddingRight: 10}}>
                                //     <PrimaryButton style={fullWidth} htmlType="submit" form={changePasswordForm}>
                                //         Изменить пароль
                                //     </PrimaryButton>
                                // </div>
                                <PrimaryButton htmlType="submit" form={changePasswordForm}>
                                    Изменить пароль
                                </PrimaryButton>
                            ]}
                        >
                            <ItemsForm labelSpan={6} items={updatePasswordItems}
                                       onFinish={changePasswordHandler} name={changePasswordForm}/>
                        </Card>
                    </Col>
                </Row>
            </Tabs.TabPane>
            <Tabs.TabPane tab="Скидки производителей" key="producers-discounts">
                <DiscountsTab discounts={discounts}/>
                {/*    <Row justify="space-around" align="middle" gutter={[0, 0]}>*/}
                {/*        <Col md={24} lg={20} xl={15} xxl={12}>*/}
                {/*            <Card*/}
                {/*                title="Скидки производителей"*/}
                {/*                style={{...fullWidth, borderRadius: 10}}*/}
                {/*                actions={[*/}
                {/*                    // <SecondaryButton type="default" disabled>*/}
                {/*                    //     Удалить пользователя*/}
                {/*                    // </SecondaryButton>,*/}
                {/*                    <PrimaryButton htmlType="submit" form={discountsForm}>*/}
                {/*                        Сохранить изменения*/}
                {/*                    </PrimaryButton>*/}
                {/*                ]}*/}
                {/*            >*/}
                {/*                <Form name={discountsForm} onFinish={discountsSaveHandler}>*/}
                {/*                    <Table*/}
                {/*                        rowClassName="editable-row"*/}
                {/*                        components={{*/}
                {/*                            body: {*/}
                {/*                                cell: EditableCell,*/}
                {/*                                row: EditableRow*/}
                {/*                            },*/}
                {/*                        }}*/}
                {/*                        dataSource={discounts}*/}
                {/*                        columns={discountsColumns}*/}
                {/*                        size="small"*/}
                {/*                        scroll={{y: 570}}*/}
                {/*                    />*/}
                {/*                </Form>*/}
                {/*            </Card>*/}
                {/*        </Col>*/}
                {/*    </Row>*/}
            </Tabs.TabPane>
        </Tabs>
    )
}

Index.layout = page => <Authenticated children={page} title={"Профиль"} backTo={true}
                                      breadcrumbs={useBreadcrumbs().profile}/>

export default Index
