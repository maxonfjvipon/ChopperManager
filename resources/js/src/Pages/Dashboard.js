import React from 'react';
import {Authenticated} from "../Shared/Layout/Authenticated";
import {Col, message, Row} from "antd";
import {DashboardCard} from "../Shared/DashboardCard";
import {Inertia} from "@inertiajs/inertia";

const Dashboard = () => {
    const cards = [
        {
            title: 'Подбор насосных установок', src: 'img/dashboard/pumpselection.jpg', onClick: () => {
                Inertia.get(route('projects.index'))
            },
        },
        {
            title: 'Маркетплейс', src: 'img/dashboard/marketplace.png', onClick: () => {
                message.info('Сервис в разработке')
            }
        },
        {
            title: 'Насосы', src: 'img/dashboard/marketplace.png', onClick: () => {
                Inertia.get(route('pumps.index'))
            }
        },
    ]

    return (
        <Row style={{minHeight: 600}} justify="space-around" align="middle" gutter={[0, 0]}>
            {cards.map(card => (
                <Col key={card.title} md={7} xs={11}>
                    <DashboardCard {...card} key={card.title}/>
                </Col>
            ))}
        </Row>
    )
}

Dashboard.layout = page => <Authenticated children={page} title={"Дашборд"}/>

export default Dashboard
