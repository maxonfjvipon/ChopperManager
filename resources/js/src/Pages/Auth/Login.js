import {ItemsForm} from "../../Shared/ItemsForm";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {useStyles} from "../../Hooks/styles.hook";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Inertia} from "@inertiajs/inertia";
import {JustifiedRow} from "../../Shared/JustifiedRow";
import {Col, Input} from "antd";
import React from "react";
import {RoundedCard} from "../../Shared/Cards/RoundedCard";
import {GuestLayout} from "../../Shared/Layout/GuestLayout";
import {Link} from "@inertiajs/inertia-react";
import {useTransRoutes} from "../../Hooks/routes.hook";
import Lang from "../../../translation/lang";

const Login = () => {
    // HOOKS
    const {textAlignCenter, fullWidth, margin} = useStyles()
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()

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
                <div style={margin.top(16)}>
                    <div style={textAlignCenter}>
                        <Link href={tRoute('register')}>
                            {Lang.get('pages.login.not_registered')}
                        </Link>
                    </div>
                </div>
            </Col>
        </JustifiedRow>
    )
}

Login.layout = page => <GuestLayout children={page}/>

export default Login
