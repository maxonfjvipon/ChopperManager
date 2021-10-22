import {Col, Input, Typography} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {ItemsForm} from "../../Shared/ItemsForm";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Link, usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../Shared/Inputs/Selection";
import React from "react";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {Inertia} from "@inertiajs/inertia";
import {JustifiedRow} from "../../Shared/JustifiedRow";
import {RoundedCard} from "../../Shared/Cards/RoundedCard";
import {GuestLayout} from "../../Shared/Layout/GuestLayout";
import Lang from "../../../translation/lang";
import {useTransRoutes} from "../../Hooks/routes.hook";


const Register = () => {
    // HOOKS
    const {textAlignCenter, fullWidth, margin} = useStyles()
    const {rules} = useInputRules()
    const {businesses, countries} = usePage().props
    const {tRoute} = useTransRoutes()

    // CONSTS
    const formName = 'register-form'
    const items = [
        {
            values: {
                name: 'organization_name',
                label: Lang.get('pages.register.organization_name'),
                rules: [rules.required]
            }, input: <Input/>
        },
        {
            values: {
                name: 'business_id',
                label: Lang.get('pages.register.main_business'),
                rules: [rules.required]
            },
            input: <Selection options={businesses}/>
        },
        {
            values: {name: 'itn', label: Lang.get('pages.register.itn'), rules: rules.itn},
            input: <Input/>
        },
        {
            values: {name: 'phone', label: Lang.get('pages.register.phone'), rules: rules.phone},
            input: <Input/>,
        },
        {
            values: {
                name: 'country_id',
                label: Lang.get('pages.register.country'),
                rules: [rules.required]
            },
            input: <Selection options={countries}/>
        },
        {
            values: {name: 'city', label: Lang.get('pages.register.city'), rules: [rules.required]},
            input: <Input/>
        },
        {
            values: {name: 'postcode', label: Lang.get('pages.register.postcode')}, input: <Input/>
        },
        {
            values: {
                name: 'first_name',
                label: Lang.get('pages.register.first_name'),
                rules: [rules.required, rules.max(255)]
            }, input: <Input/>
        },
        {
            values: {
                name: 'middle_name',
                label: Lang.get('pages.register.middle_name'),
                rules: [rules.required, rules.max(255)]
            }, input: <Input/>
        },
        {
            values: {
                name: 'last_name',
                label: Lang.get('pages.register.last_name'),
                rules: [rules.max(255)]
            },
            input: <Input/>
        },
        {
            values: {name: 'email', label: Lang.get('pages.register.email'), rules: rules.email},
            input: <Input/>
        },
        {
            values: {
                name: 'password',
                label: Lang.get('pages.register.password'),
                rules: rules.password
            },
            input: <Input.Password/>
        },
        {
            values: {
                name: 'password_confirmation',
                label: Lang.get('pages.register.password_confirmation'),
                rules: rules.password
            },
            input: <Input.Password/>
        },
    ]

    // HANDLERS
    const registerHandler = body => {
        // console.log(tRoute('register.attempt'))
        Inertia.post(tRoute('register.attempt'), body)
    }

    // RENDER
    return (
        <JustifiedRow>
            <Col xs={22} sm={20} md={18} lg={16} xl={11} xxl={9}>
                <RoundedCard
                    title={
                        <div style={textAlignCenter}>
                            {Lang.get('pages.register.please_register')}
                        </div>
                    }
                >
                    <ItemsForm
                        labelSpan={{xxl: 8, xl: 10, lg: 10, md: 12, sm: 12}}
                        items={items}
                        name={formName}
                        onFinish={registerHandler}
                    />
                </RoundedCard>
                <div style={margin.top(16)}>
                    <PrimaryButton style={fullWidth} htmlType="submit" form={formName}>
                        {Lang.get('pages.register.register')}
                    </PrimaryButton>
                </div>
                <div style={margin.top(16)}>
                    <div style={textAlignCenter}>
                        <Link href={tRoute('login')}>
                            {Lang.get('pages.register.already_registered')}
                        </Link>
                    </div>
                </div>
            </Col>
        </JustifiedRow>
    )
}

Register.layout = page => <GuestLayout children={page}/>

export default Register
