import Guest from "../../Shared/Layout/Guest";
import {Card, Col, Row, Typography} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import React from "react";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {Link} from "@inertiajs/inertia-react";
import {Inertia} from "@inertiajs/inertia";

function VerifyEmail() {
    const {textAlignCenter, fullWidth} = useStyles()
    return (
        <Row style={{minHeight: 600}} justify="space-around" align="middle" gutter={[0, 0]}>
            <Col md={24} lg={20} xl={15} xxl={12}>
                <Card
                    title={
                        <div style={textAlignCenter}>
                            <Typography>
                                Спасибо, что зарегистрировались!
                            </Typography>
                        </div>}
                    style={{...fullWidth, borderRadius: 10}}
                    actions={[
                        <PrimaryButton onClick={() => {
                            Inertia.post(route('verification.send'))
                        }}>
                            Отправить ссылку еще раз
                        </PrimaryButton>,
                        <Link method="post" href={route('logout')}>Выйти</Link>
                    ]}
                >
                    <Typography>
                        Прежде чем приступить к работе, не могли бы вы подтвердить свой адрес электронной почты,
                        перейдя по ссылке, которую мы только что отправили вам по электронной почте?
                        Если вы не получили электронное письмо, мы с радостью вышлем вам другое.
                        Также на всякий случай проверьте папку "Спам"
                    </Typography>
                </Card>
            </Col>
        </Row>
    )
}

VerifyEmail.layout = page => <Guest children={page}/>

export default VerifyEmail
