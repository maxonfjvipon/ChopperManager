import React from 'react';
import {Col, message, Row} from "antd";
import {DashboardCard} from "../Shared/DashboardCard";
import {Inertia} from "@inertiajs/inertia";
import {Common} from "../Shared/Layout/Common";
import {useLang} from "../Hooks/lang.hook";
import Lang from '../../translation/lang'

const Dashboard = () => {
    const Lang = useLang()

    const cards = [
        {
            title: Lang.get('pages.dashboard.select'), src: '/img/dashboard/pumpselection.jpg', onClick: () => {
                Inertia.get(route('projects.index'))
            },
        },
        {
            title: Lang.get('pages.dashboard.marketplace'), src: '/img/dashboard/marketplace.png', onClick: () => {
                message.info(Lang.get('messages.service_development'))
            }
        },
        {
            title: Lang.get('pages.dashboard.pumps'), src: '/img/dashboard/pumps.jpg', onClick: () => {
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

Dashboard.layout = page => <Common children={page} title={Lang.get('pages.dashboard.title')}/>

export default Dashboard
