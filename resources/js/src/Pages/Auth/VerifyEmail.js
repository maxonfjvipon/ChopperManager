import Guest from "../../Shared/Layout/Guest";
import {Card, Col, Row, Typography} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import React from "react";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {Link} from "@inertiajs/inertia-react";
import {Inertia} from "@inertiajs/inertia";
import {useLang} from "../../Hooks/lang.hook";

function VerifyEmail() {
    const {textAlignCenter, fullWidth} = useStyles()
    const Lang = useLang()
    return (
        <Row style={{minHeight: 600}} justify="space-around" align="middle" gutter={[0, 0]}>
            <Col xs={20} md={20} lg={20} xl={15} xxl={10}>
                <Card
                    title={
                        <div style={textAlignCenter}>
                            <Typography>
                                {Lang.trans('pages.email_verification.thanks')}
                            </Typography>
                        </div>}
                    style={{...fullWidth, borderRadius: 10}}
                    actions={[
                        <PrimaryButton onClick={() => {
                            Inertia.post(route('verification.send'))
                        }}>
                            {Lang.trans('pages.email_verification.send_again')}
                        </PrimaryButton>,
                        <Link method="post" href={route('logout')}>{Lang.get('pages.email_verification.logout')}</Link>
                    ]}
                >
                    <Typography>
                        {Lang.trans('pages.email_verification.text1')}
                        <br/>
                        {Lang.trans('pages.email_verification.text2')}
                    </Typography>
                </Card>
            </Col>
        </Row>
    )
}

VerifyEmail.layout = page => <Guest children={page}/>

export default VerifyEmail
