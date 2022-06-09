import {Inertia} from "@inertiajs/inertia";
import {Col, Input} from "antd";
import React from "react";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {JustifiedRow} from "../../../../../../resources/js/src/Shared/JustifiedRow";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {GuestLayout} from "../../../../../../resources/js/src/Shared/Layout/GuestLayout";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {Link, usePage} from "@inertiajs/inertia-react";

const Register = () => {

    // HOOKS
    const {textAlignCenter, fullWidth, margin} = useStyles()
    const {rules} = useInputRules()
    const {areas} = usePage().props

    // CONSTS
    const formName = 'register-form'
    const items = [
        {
            values: {
                name: 'first_name',
                label: "Имя",
                rules: [rules.required, rules.max(255)]
            }, input: <Input/>
        },
        {
            values: {
                name: 'middle_name',
                label: "Фамилия",
                rules: [rules.required, rules.max(255)]
            }, input: <Input/>
        },
        {
            values: {
                name: 'last_name',
                label: "Отчество",
                rules: [rules.max(255)]
            },
            input: <Input/>
        },
        {
            values: {
                name: 'organization_name',
                label: "Наименование организации",
                rules: [rules.max(255)]
            },
            input: <Input/>
        },
        {
            values: {name: 'itn', label: "ИНН", rules: rules.itn},
            input: <Input/>
        },
        {
            values: {name: 'phone', label: "Телефон", rules: rules.phone},
            input: <Input/>,
        },
        {
            values: {
                name: 'area_id',
                label: "Область",
                rules: [rules.required]
            },
            input: <Selection options={areas}/>
        },
        {
            values: {name: 'email', label: "Email", rules: rules.email},
            input: <Input/>
        },
        {
            values: {
                name: 'password',
                label: "Пароль",
                rules: rules.password
            },
            input: <Input.Password/>
        },
        {
            values: {
                name: 'password_confirmation',
                label: "Повторите пароль",
                rules: rules.password
            },
            input: <Input.Password/>
        },
    ]

    // HANDLERS
    const registerHandler = body => {
        // console.log(tRoute('register.attempt'))
        Inertia.post(route('register.attempt'), body)
    }

    // RENDER
    return (
        <JustifiedRow>
            <Col xs={22} sm={20} md={18} lg={16} xl={11} xxl={9}>
                <RoundedCard
                    title={
                        <div style={textAlignCenter}>
                            Пожалуйста зарегистрируйтесь
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
                        Зарегистрироваться
                    </PrimaryButton>
                </div>
                <div style={margin.top(16)}>
                    <div style={textAlignCenter}>
                        <Link href={route('login')}>
                            Уже зарегистрированы?
                        </Link>
                    </div>
                </div>
            </Col>
        </JustifiedRow>
    )
}

Register.layout = page => <GuestLayout children={page}/>

export default Register
