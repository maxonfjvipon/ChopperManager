import React from 'react';
import {Col, message, Row} from "antd";
import {Authenticated} from "../../Shared/Layout/Authenticated";
import {DashboardCard} from "../../Shared/DashboardCard";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {Common} from "../../Shared/Layout/Common";
import Lang from '../../../translation/lang'
import {useLang} from "../../Hooks/lang.hook";

const Dashboard = () => {
    // CONSTS
    const {project_id} = usePage().props
    const Lang = useLang()
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

    const serviceUnavailable = () => {
        message.info(Lang.get('messages.service_development'))
    }

    const cards = [
        {
            title: Lang.get('pages.selections.dashboard.single_prefs'),
            src: imgPath + paths.singlePump + producers.singe + ext,
            onClick: () => {
                Inertia.get(route('selections.create', project_id))
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
        <Row style={{minHeight: 600}} justify="space-around" align="middle" gutter={[0, 10]}>
            {cards.map(card => (
                <Col key={card.title} xs={11} md={5}>
                    <DashboardCard {...card} key={card.title}/>
                </Col>
            ))}
        </Row>
    )
}

Dashboard.layout = page => <Common children={page} title={Lang.get('pages.selections.dashboard.title')} backTo={true} breadcrumbs={useBreadcrumbs().selections}/>

export default Dashboard
