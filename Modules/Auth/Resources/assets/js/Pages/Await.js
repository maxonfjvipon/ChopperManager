import {Col, Row, Typography, Button} from "antd";
import React from "react";
import {Inertia} from "@inertiajs/inertia";
import {JustifiedRow} from "../../../../../../resources/js/src/Shared/JustifiedRow";
import {RoundedCard} from "../../../../../../resources/js/src/Shared/Cards/RoundedCard";
import {GuestLayout} from "../../../../../../resources/js/src/Shared/Layout/GuestLayout";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";

function Await() {
    const {textAlignCenter, fullWidth, margin} = useStyles()
    return (
        <JustifiedRow>
            <Col xs={20} md={20} lg={20} xl={15} xxl={10}>
                <RoundedCard
                    title={
                        <div style={textAlignCenter}>
                            Спасибо за регистрацию!
                        </div>}
                >
                    <Typography>
                        Здесь какой-то текст, уведомляющий о том, что менеджер проверит ваши данные и свяжется с вами
                    </Typography>
                </RoundedCard>
                <Row style={margin.top(16)} gutter={16}>
                    <Col xs={12}>
                        <Button style={fullWidth} onClick={() => {
                            Inertia.post(route('logout'))
                        }}>
                            Изменить данные?
                        </Button>
                    </Col>
                    <Col xs={12}>
                        <Button style={fullWidth} onClick={() => {
                            Inertia.post(route('logout'))
                        }}>
                            Выйти
                        </Button>
                    </Col>
                </Row>
            </Col>
        </JustifiedRow>
    )
}

Await.layout = page => <GuestLayout children={page}/>

export default Await
