import React from 'react'
import Guest from "../../Shared/Layout/Guest";
import {Card, Col, Divider, Input, Row, Typography} from "antd";
import {ItemsForm} from "../../Shared/ItemsForm";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {useStyles} from "../../Hooks/styles.hook";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Inertia} from "@inertiajs/inertia";

const Login = () => {
    const {textAlignCenter, fullWidth} = useStyles()
    const {rules} = useInputRules()
    const formName = 'login-form'

    const items = [
        {values: {name: 'email', label: 'E-mail', rules: rules.email}, input: <Input/>},
        {values: {name: 'password', label: 'Пароль', rules: rules.password}, input: <Input.Password/>},
    ]

    const loginHandler = body => {
        Inertia.post(route('login.attempt', body))
    }

    return (
        <Row style={{minHeight: 600}} justify="space-around" align="middle" gutter={[0, 0]}>
            <Col md={24} lg={20} xl={15} xxl={12}>
                <Card
                    title={
                        <div style={textAlignCenter}>
                            <Typography>
                                Добро пожаловать!
                            </Typography>
                        </div>}
                    style={{...fullWidth, borderRadius: 10}}
                >
                    <ItemsForm
                        labelSpan={6}
                        items={items}
                        name={formName}
                        onFinish={loginHandler}
                    />
                    <Divider/>
                    <PrimaryButton style={{...fullWidth, marginBottom: 10}} htmlType="submit" form={formName}>
                        Войти
                    </PrimaryButton>
                    <div style={textAlignCenter}>
                        <Typography.Link href={route('register')}>
                            Еще не зарегистрированы?
                        </Typography.Link>
                    </div>
                </Card>
            </Col>
        </Row>
    )
}

Login.layout = page => <Guest children={page}/>

export default Login
