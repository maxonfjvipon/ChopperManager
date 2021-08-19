import React, {useEffect} from 'react';
import {Authenticated} from "../Shared/Layout/Authenticated";
import {Card, Col, Divider, Form, Input, message, Row, Tabs} from "antd";
import {ItemsForm} from "../Shared/ItemsForm";
import {useStyles} from "../Hooks/styles.hook";
import {Selection} from "../Shared/Inputs/Selection";
import {useInputRules} from "../Hooks/input-rules.hook";
import {useAreasCities} from "../Hooks/components/areas-cities.hook";
import {PrimaryButton} from "../Shared/Buttons/PrimaryButton";
import {SecondaryButton} from "../Shared/Buttons/SecondaryButton";
import {usePage} from "@inertiajs/inertia-react";
import {useBreadcrumbs} from "../Hooks/breadcrumbs.hook";
import {useForm} from "antd/es/form/Form";

const Profile = () => {
    const {setAreasWithCities, areasOptions, setAreaValue, citiesOptions} = useAreasCities()
    const {fullWidth, reducedAntFormItemClassName} = useStyles()
    const {rules} = useInputRules()
    const [passwordForm] = useForm()

    const {user, roles, businesses, areasWithCities} = usePage().props
    const userdata = user.data

    useEffect(() => {
        setAreasWithCities(areasWithCities)
    }, [areasWithCities])

    useEffect(() => {
        setAreaValue(areasWithCities.find(area => area.name === userdata.area).id)
    }, [userdata])

    const profileFormName = 'profile-form'
    const updatePasswordForm = 'update-password-form'

    const items = [
        {
            values: {
                name: 'name',
                label: 'Наименование организации',
                rules: [rules.required],
                initialValue: userdata.name,
                // className: reducedAntFormItemClassName,
            }, input: <Input/>
        },
        {
            values: {
                name: 'inn', label: 'ИНН', rules: rules.inn, initialValue: userdata.inn || "",
                // className: reducedAntFormItemClassName,
            }, input: <Input/>
        },
        {
            values: {
                name: 'phone', label: 'Контактный телефон', rules: rules.phone, initialValue: userdata.phone,
                // className: reducedAntFormItemClassName,
            },
            input: <Input placeholder="+7XXXXXXXXXX"/>,
        },
        {
            values: {name: 'area', label: 'Область', rules: [rules.required], initialValue: userdata.area},
            input: <Selection {...areasOptions}/>
        },
        {
            values: {name: 'city_id', label: 'Город', rules: [rules.required], initialValue: userdata.city},
            input: <Selection {...citiesOptions}/>
        },
        {
            values: {name: 'fio', label: 'ФИО', rules: [rules.max(255)], initialValue: userdata.fio || ""},
            input: <Input/>
        },
        {values: {name: 'email', label: 'E-mail', rules: rules.email, initialValue: userdata.email}, input: <Input/>},
        {
            values: {
                name: 'business_id',
                label: 'Основная деятельность',
                rules: [rules.required],
                initialValue: userdata.business
            },
            input: <Selection options={businesses}/>
        },
        {
            values: {name: 'role_id', label: 'Роль', rules: [rules.required], initialValue: userdata.role},
            input: <Selection options={roles}/>
        },
    ]

    const updatePasswordItems = [
        {values: {name: 'current_password', label: 'Текущий пароль', rules: []}, input: <Input.Password/>},
        {values: {name: 'password', label: 'Новый пароль', rules: []}, input: <Input.Password/>},
        {
            values: {name: 'password_confirmation', label: 'Повторите новый пароль', rules: []},
            input: <Input.Password/>
        },
    ]

    const updateProfileHandler = async body => {
        message.info('Функция в разработке')
        // Inertia.put(route('users.update'), {...body, ...await passwordForm.validateFields()})
    }

    return (
        <Tabs centered type="card" defaultActiveKey="user-info">
            <Tabs.TabPane tab="Информация" key="user-info">
                <Row justify="space-around" align="middle" gutter={[0, 0]}>
                    <Col md={24} lg={20} xl={15} xxl={12}>
                        <Card
                            style={{...fullWidth, borderRadius: 10}}
                            actions={[
                                <SecondaryButton type="default" disabled>
                                    Удалить пользователя
                                </SecondaryButton>,
                                <PrimaryButton htmlType="submit" form={profileFormName}>
                                    Сохранить изменения
                                </PrimaryButton>
                            ]}
                        >
                            {/* TODO: can't update main user fields */}
                            <ItemsForm layout="horizontal" labelSpan={7} wrapperSpan={17} items={items}
                                       onFinish={updateProfileHandler} name={profileFormName}/>
                            <Divider/>
                            <ItemsForm form={passwordForm} labelSpan={7} items={updatePasswordItems} name={updatePasswordForm}/>
                        </Card>
                    </Col>
                </Row>
            </Tabs.TabPane>
            <Tabs.TabPane tab="Скидки производителей" key="producers-discounts">
                <Row justify="space-around" align="middle" gutter={[0, 0]}>
                    <Col md={24} lg={20} xl={15} xxl={12}>
                        <Card
                            title="В разработке..."
                            style={{...fullWidth, borderRadius: 10}}
                            // actions={[
                            //     <SecondaryButton type="default" disabled>
                            //         Удалить пользователя
                            //     </SecondaryButton>,
                            //     <PrimaryButton htmlType="submit" form={profileFormName}>
                            //         Сохранить изменения
                            //     </PrimaryButton>
                            // ]}
                        >
                            {/* TODO: can't update main user fields */}
                            {/*<ItemsForm layout="horizontal" labelSpan={7} wrapperSpan={17} items={items}*/}
                            {/*           onFinish={updateProfileHandler} name={profileFormName}/>*/}
                            {/*<Divider/>*/}
                            {/*<ItemsForm form={passwordForm} labelSpan={7} items={updatePasswordItems} name={updatePasswordForm}/>*/}
                        </Card>
                    </Col>
                </Row>
            </Tabs.TabPane>
        </Tabs>
    )
}

Profile.layout = page => <Authenticated children={page} title={"Профиль"} backTo={true}
                                        breadcrumbs={useBreadcrumbs().profile}/>

export default Profile
