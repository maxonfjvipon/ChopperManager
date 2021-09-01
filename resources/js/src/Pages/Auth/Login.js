import React, {useEffect} from 'react'
import {Card, Col, Divider, Input, Row, Typography} from "antd";
import {ItemsForm} from "../../Shared/ItemsForm";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {useStyles} from "../../Hooks/styles.hook";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Inertia} from "@inertiajs/inertia";
import {Common} from "../../Shared/Layout/Common";
import {useLang} from "../../Hooks/lang.hook";

const Login = () => {
    const {textAlignCenter, fullWidth} = useStyles()
    const {rules} = useInputRules()
    const Lang = useLang()
    const formName = 'login-form'

    const items = [
        {values: {name: 'email', label: Lang.get('pages.login.email'), rules: rules.email}, input: <Input/>},
        {values: {name: 'password', label: Lang.get('pages.login.password'), rules: rules.password}, input: <Input.Password/>},
    ]

    const loginHandler = body => {
        Inertia.post(route('login.attempt'), body)
    }

    return (
        <Row style={{minHeight: 600}} justify="space-around" align="middle" gutter={[0, 0]}>
            <Col md={24} lg={20} xl={15} xxl={12}>
                <Card
                    title={
                        <div style={textAlignCenter}>
                            <Typography>
                                {Lang.get('pages.login.welcome')}
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
                        {Lang.get('pages.login.login')}
                    </PrimaryButton>
                    <div style={textAlignCenter}>
                        <Typography.Link href={route('register')}>
                            {Lang.get('pages.login.not_registered')}
                        </Typography.Link>
                    </div>
                </Card>
            </Col>
        </Row>
    )
}

// Login.layout = page => <Guest children={page}/>
Login.layout = page => <Common children={page}/>

export default Login
