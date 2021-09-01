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
import {useLang} from "../../Hooks/lang.hook";
import {Common} from "../../Shared/Layout/Common";


const Register = () => {
    const {setAreasWithCities, areasOptions, citiesOptions, citiesToShow} = useAreasCities()
    const {textAlignCenter, fullWidth} = useStyles()
    const {rules} = useInputRules()
    const Lang = useLang()

    // CONSTS
    const {businesses, areasWithCities} = usePage().props

    const formName = 'register-form'
    const items = [
        {values: {name: 'organization_name', label: Lang.get('pages.register.organization_name'), rules: [rules.required]}, input: <Input/>},
        {
            values: {
                name: 'business_id',
                label: Lang.get('pages.register.main_business'),
                rules: [rules.required]
            },
            input: <Selection options={businesses}/>
        },
        {values: {name: 'itn', label: Lang.get('pages.register.itn'), rules: rules.itn}, input: <Input/>},
        {
            values: {name: 'phone', label: Lang.get('pages.register.phone'), rules: rules.phone},
            input: <Input placeholder="+7XXXXXXXXXX"/>,
        },
        {values: {name: 'area', label: Lang.get('pages.register.area'), rules: [rules.required]}, input: <Selection {...areasOptions}/>},
        {
            values: {name: 'city_id', label: Lang.get('pages.register.city'), rules: rules.city(citiesToShow)},
            input: <Selection {...citiesOptions}/>
        },
        {values: {name: 'first_name', label: Lang.get('pages.register.first_name'), rules: [rules.required, rules.max(255)]}, input: <Input/>},
        {values: {name: 'middle_name', label: Lang.get('pages.register.middle_name'), rules: [rules.required, rules.max(255)]}, input: <Input/>},
        {values: {name: 'last_name', label: Lang.get('pages.register.last_name'), rules: [rules.max(255)]}, input: <Input/>},
        {values: {name: 'email', label: Lang.get('pages.register.email'), rules: rules.email}, input: <Input/>},
        {values: {name: 'password', label: Lang.get('pages.register.password'), rules: rules.password}, input: <Input.Password/>},
        {
            values: {name: 'password_confirmation', label: Lang.get('pages.register.password_confirmation'), rules: rules.password},
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
                                {Lang.get('pages.register.please_register')}
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
                        {Lang.get('pages.register.register')}
                    </PrimaryButton>
                    <div style={textAlignCenter}>
                        <Typography.Link href={route('login')}>
                            {Lang.get('pages.register.already_registered')}
                        </Typography.Link>
                    </div>
                </Card>
            </Col>
        </Row>
    )
}

Register.layout = page => <Common children={page}/>

export default Register
