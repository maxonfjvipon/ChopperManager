import React from 'react';
import {Col, message, Row} from "antd";
import {DashboardCard} from "../../Shared/DashboardCard";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import Lang from '../../../translation/lang'
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {Container} from "../../Shared/ResourcePanel/Index/Container";
import {useTransRoutes} from "../../Hooks/routes.hook";

const Dashboard = () => {
    // HOOKS
    const {project_id} = usePage().props
    const {tRoute} = useTransRoutes()

    const serviceUnavailable = () => {
        message.info(Lang.get('messages.service_development'))
    }

    // CONSTS
    const imgPath = "/img/selections-dashboard/"
    const paths = {
        singlePump: 'Nasos-',
        doublePump: 'NasosSD-',
        station: {
            water: 'StanVoda-',
            fire: 'StanPoz-'
        }
    }
    const ext = '.png'
    const producers = {
        singe: 'Wilo',
        double: 'Grundfos',
        station: {
            water: 'Wilo',
            fire: 'Grundfos'
        }
    }
    const cards = [
        {
            title: Lang.get('pages.selections.dashboard.single_prefs'),
            src: imgPath + paths.singlePump + producers.singe + ext,
            onClick: () => {
                Inertia.get(tRoute('selections.create', project_id))
            }
        },
        {
            title: Lang.get('pages.selections.dashboard.double_prefs'),
            src: imgPath + paths.doublePump + producers.double + ext,
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.water_station_prefs'),
            src: imgPath + paths.station.water + producers.station.water + ext,
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.fire_station_prefs'),
            src: imgPath + paths.station.fire + producers.station.fire + ext,
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.single_analogy'),
            src: imgPath + paths.singlePump + producers.singe + ext,
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.double_analogy'),
            src: imgPath + paths.doublePump + producers.double + ext,
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.water_station_analogy'),
            src: imgPath + paths.station.water + producers.station.water + ext,
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.fire_station_analogy'),
            src: imgPath + paths.station.fire + producers.station.fire + ext,
            onClick: serviceUnavailable
        },
    ]

    return (
        <Container
            title={Lang.get('pages.selections.dashboard.subtitle')}
            backTitle={project_id !== "-1"
                ? Lang.get('pages.selections.dashboard.back.to_project')
                : Lang.get('pages.selections.dashboard.back.to_projects')}
            backHref={project_id !== "-1"
                ? tRoute('projects.show', project_id)
                : tRoute('projects.index')}
        >
            <Row justify="space-around" gutter={[0, 10]}>
                {cards.map(card => (
                    <Col key={card.title} xs={11} md={5}>
                        <DashboardCard {...card} key={card.title}/>
                    </Col>
                ))}
            </Row>
        </Container>
    )
}

Dashboard.layout = page => <AuthLayout children={page}/>

export default Dashboard
