import {Col, Row, Typography, Button} from "antd";
import React from "react";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../../../../resources/js/translation/lang";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {JustifiedRow} from "../../../../../../resources/js/src/Shared/JustifiedRow";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {GuestLayout} from "../../../../../../resources/js/src/Shared/Layout/GuestLayout";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";

function VerifyEmail() {
    const {textAlignCenter, fullWidth, margin} = useStyles()
    const {tRoute} = useTransRoutes()
    return (
        <JustifiedRow>
            <Col xs={20} md={20} lg={20} xl={15} xxl={10}>
                <RoundedCard
                    title={
                        <div style={textAlignCenter}>
                            {Lang.get('pages.email_verification.thanks')}
                        </div>}
                >
                    <Typography>
                        {Lang.trans('pages.email_verification.text1')}
                        <br/>
                        {Lang.trans('pages.email_verification.text2')}
                    </Typography>
                </RoundedCard>
                <Row style={margin.top(16)} gutter={16}>
                    <Col xs={12}>
                        <PrimaryButton style={fullWidth} onClick={() => {
                            Inertia.post(tRoute('verification.send'))
                        }}>
                            {Lang.get('pages.email_verification.send_again')}
                        </PrimaryButton>
                    </Col>
                    <Col xs={12}>
                        <Button style={fullWidth} onClick={() => {
                            Inertia.post(tRoute('logout'))
                        }}>
                            {Lang.get('pages.email_verification.logout')}
                        </Button>
                    </Col>
                </Row>
            </Col>
        </JustifiedRow>
    )
}

VerifyEmail.layout = page => <GuestLayout children={page}/>

export default VerifyEmail
