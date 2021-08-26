import Guest from "../../Shared/Layout/Guest";
import {Card, Col, Divider, Input, Row, Typography, Space} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {ItemsForm} from "../../Shared/ItemsForm";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../Shared/Inputs/Selection";
import {useAreasCities} from "../../Hooks/components/areas-cities.hook";
import React, {useEffect} from "react";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {Inertia} from "@inertiajs/inertia";


function Register() {
    const {setAreasWithCities, areasOptions, citiesOptions, citiesToShow} = useAreasCities()
    const {textAlignCenter, fullWidth} = useStyles()
    const {rules} = useInputRules()

    // CONSTS
    const {businesses, areasWithCities} = usePage().props

    const formName = 'register-form'
    const items = [
        {values: {name: 'name', label: 'Наименование организации', rules: [rules.required]}, input: <Input/>},
        {
            values: {
                name: 'business_id',
                label: 'Основная деятельность',
                rules: [rules.required]
            },
            input: <Selection options={businesses}/>
        },
        {values: {name: 'inn', label: 'ИНН', rules: rules.inn}, input: <Input/>},
        {
            values: {name: 'phone', label: 'Контактный телефон', rules: rules.phone},
            input: <Input placeholder="+7XXXXXXXXXX"/>,
        },
        {values: {name: 'area', label: 'Область', rules: [rules.required]}, input: <Selection {...areasOptions}/>},
        {
            values: {name: 'city_id', label: 'Город', rules: rules.city(citiesToShow)},
            input: <Selection {...citiesOptions}/>
        },
        {values: {name: 'fio', label: 'ФИО', rules: [rules.required, rules.max(255)]}, input: <Input/>},
        {values: {name: 'email', label: 'E-mail', rules: rules.email}, input: <Input/>},
        {values: {name: 'password', label: 'Пароль', rules: rules.password}, input: <Input.Password/>},
        {
            values: {name: 'password_confirmation', label: 'Повторите пароль', rules: rules.password},
            input: <Input.Password/>
        },
    ]

    useEffect(() => {
        setAreasWithCities(areasWithCities)
    }, [areasWithCities])

    const registerHandler = body => {
        Inertia.post(route('register.attempt', body))
    }

    return (
        <Row style={{minHeight: 600}} justify="space-around" align="middle" gutter={[0, 0]}>
            <Col md={24} lg={20} xl={15} xxl={12}>
                <Card
                    title={
                        <div style={textAlignCenter}>
                            <Typography>
                                Пожалуйста зарегистрируйтесь
                            </Typography>
                        </div>}
                    style={{...fullWidth, borderRadius: 10}}
                >
                    < ItemsForm
                        labelSpan={6}
                        // wrapperSpan={17}
                        items={items}
                        name={formName}
                        onFinish={registerHandler}
                    />
                    <Divider/>
                    <PrimaryButton style={{...fullWidth, marginBottom: 10}} htmlType="submit" form={formName}>
                        Зарегистрироваться
                    </PrimaryButton>
                    <div style={textAlignCenter}>
                        <Typography.Link href={route('login')}>
                            Уже зарегистрированы?
                        </Typography.Link>
                    </div>
                </Card>
            </Col>
        </Row>
    )
}

Register.layout = page => <Guest children={page}/>

export default Register
