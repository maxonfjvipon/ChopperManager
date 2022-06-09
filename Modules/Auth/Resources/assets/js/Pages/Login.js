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
import {Link} from "@inertiajs/inertia-react";

const Login = () => {

    // HOOKS
    const {textAlignCenter, fullWidth, margin} = useStyles()
    const {rules} = useInputRules()

    // CONST
    const formName = 'login-form'
    const items = [
        {
            values: {name: 'email', label: "Email", rules: rules.email},
            input: <Input/>
        },
        {
            values: {name: 'password', label: 'Пароль', rules: rules.password},
            input: <Input.Password/>
        },
    ]

    // HANDLERS
    const loginHandler = body => {
        Inertia.post(route('login.attempt'), body)
    }

    // RENDER
    return (
        <JustifiedRow>
            <Col xs={17} sm={13} md={11} lg={9} xl={7} xxl={5}>
                <RoundedCard title={<div style={textAlignCenter}>Пожалуйста авторизуйтесь!</div>}>
                    <ItemsForm
                        layout="vertical"
                        items={items}
                        name={formName}
                        onFinish={loginHandler}
                    />
                </RoundedCard>
                <div style={margin.top(16)}>
                    <PrimaryButton style={fullWidth} htmlType="submit" form={formName}>
                        Войти
                    </PrimaryButton>
                </div>
                <div style={margin.top(16)}>
                    <div style={textAlignCenter}>
                        <Link href={route('register')}>
                            Еще не зарегистрированы?
                        </Link>
                    </div>
                </div>
            </Col>
        </JustifiedRow>
    )
}

Login.layout = page => <GuestLayout children={page}/>

export default Login
