import React from 'react';
import {Col, message} from "antd";
import {DashboardCard} from "../Shared/DashboardCard";
import {Inertia} from "@inertiajs/inertia";
import Lang from '../../translation/lang'
import {JustifiedRow} from "../Shared/JustifiedRow";

const Dashboard = () => {
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
        <JustifiedRow>
            {cards.map(card => (
                <Col key={card.title} md={7} xs={11}>
                    <DashboardCard {...card} key={card.title}/>
                </Col>
            ))}
        </JustifiedRow>
    )
}

Dashboard.layout = page => <Common children={page} title={Lang.get('pages.dashboard.title')}/>

export default Dashboard
