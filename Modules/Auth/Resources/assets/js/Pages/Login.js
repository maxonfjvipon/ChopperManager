import {Inertia} from "@inertiajs/inertia";
import {Col, Input} from "antd";
import React from "react";
import {Link, usePage} from "@inertiajs/inertia-react";
import Lang from "../../../../../../resources/js/translation/lang";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {JustifiedRow} from "../../../../../../resources/js/src/Shared/JustifiedRow";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {GuestLayout} from "../../../../../../resources/js/src/Shared/Layout/GuestLayout"
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";

const Login = () => {
    // HOOKS
    const {textAlignCenter, fullWidth, margin} = useStyles()
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
    const {has_registration} = usePage().props

    // CONST
    const formName = 'login-form'
    const items = [
        {
            values: {name: 'email', label: Lang.get('pages.login.email'), rules: rules.email},
            input: <Input/>
        },
        {
            values: {
                name: 'password',
                label: Lang.get('pages.login.password'),
                rules: rules.password
            },
            input: <Input.Password/>
        },
    ]

    // HANDLERS
    const loginHandler = body => {
        Inertia.post(tRoute('login.attempt'), body)
    }

    // RENDER
    return (
        <JustifiedRow>
            <Col xs={17} sm={13} md={11} lg={9} xl={7} xxl={5}>
                <RoundedCard title={<div style={textAlignCenter}>{Lang.get('pages.login.welcome')}</div>}>
                    <ItemsForm
                        layout="vertical"
                        labelSpan={7}
                        items={items}
                        name={formName}
                        onFinish={loginHandler}
                    />
                </RoundedCard>
                <div style={margin.top(16)}>
                    <PrimaryButton style={fullWidth} htmlType="submit" form={formName}>
                        {Lang.get('pages.login.login')}
                    </PrimaryButton>
                </div>
                {has_registration && <div style={margin.top(16)}>
                    <div style={textAlignCenter}>
                        <Link href={tRoute('register')}>
                            {Lang.get('pages.login.not_registered')}
                        </Link>
                    </div>
                </div>}
            </Col>
        </JustifiedRow>
    )
}

Login.layout = page => <GuestLayout children={page}/>

export default Login
