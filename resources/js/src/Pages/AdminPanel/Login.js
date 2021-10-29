import {ItemsForm} from "../../Shared/ItemsForm";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {useStyles} from "../../Hooks/styles.hook";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Inertia} from "@inertiajs/inertia";
import {JustifiedRow} from "../../Shared/JustifiedRow";
import {Col, Input} from "antd";
import React from "react";
import {RoundedCard} from "../../Shared/Cards/RoundedCard";
import {GuestLayout} from "../../Shared/Layout/AdminPanel/GuestLayout";

const Login = () => {
    // HOOKS
    const {textAlignCenter, fullWidth, margin} = useStyles()
    const {rules} = useInputRules()

    // CONST
    const formName = 'login-form'
    const items = [
        {
            values: {name: 'login', label: "Login", rules: [rules.required]},
            input: <Input/>
        },
        {
            values: {
                name: 'password',
                label: "Password",
                rules: rules.password
            },
            input: <Input.Password/>
        },
    ]

    // HANDLERS
    const loginHandler = body => {
        // console.log(route('admin.login.attempt'))
        Inertia.post(route('admin.login.attempt'), body)
    }

    // RENDER
    return (
        <JustifiedRow>
            <Col xs={17} sm={13} md={11} lg={9} xl={7} xxl={5}>
                <RoundedCard title={<div style={textAlignCenter}>Welcome!</div>}>
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
                        Login
                    </PrimaryButton>
                </div>
            </Col>
        </JustifiedRow>
    )
}

Login.layout = page => <GuestLayout children={page}/>

export default Login
