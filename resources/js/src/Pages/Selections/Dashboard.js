import React from 'react';
import {Col, message, Row} from "antd";
import {DashboardCard} from "../../Shared/DashboardCard";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import Lang from '../../../translation/lang'
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {Container} from "../../Shared/ResourcePanel/Index/Container";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {usePermissions} from "../../Hooks/permissions.hook";

const Dashboard = () => {
    // HOOKS
    const {project_id} = usePage().props
    const {tRoute} = useTransRoutes()
    const {has, filterPermissionsArray} = usePermissions()

    const serviceUnavailable = () => {
        message.info(Lang.get('messages.service_development'))
    }

    // CONSTS
    const imgPath = "/img/selections-dashboard/"
    // const paths = {
    //     singlePump: 'Nasos-',
    //     doublePump: 'NasosSD-',
    //     station: {
    //         water: 'StanVoda-',
    //         fire: 'StanPoz-'
    //     }
    // }
    // const ext = '.png'
    // const producers = {
    //     singe: 'Wilo',
    //     double: 'Grundfos',
    //     station: {
    //         water: 'Wilo',
    //         fire: 'Grundfos'
    //     }
    // }
    const cards = filterPermissionsArray([
        has('selection_create') && {
            title: Lang.get('pages.selections.dashboard.preferences.pump.single'),
            src: imgPath + "01.png",
            onClick: () => {
                Inertia.get(tRoute('selections.create', project_id))
            }
        },
        {
            title: Lang.get('pages.selections.dashboard.preferences.pump.double'),
            src: imgPath + "02.png",
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.preferences.pump.borehole'),
            src: imgPath + "03.png",
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.preferences.pump.drainage'),
            src: imgPath + "04.png",
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.preferences.station.water'),
            src: imgPath + "05.png",
            onClick: serviceUnavailable
        },
        {
            title: Lang.get('pages.selections.dashboard.preferences.station.fire'),
            src: imgPath + "06.png",
            onClick: serviceUnavailable
        },
    ])

    return (
        <Container
            title={Lang.get('pages.selections.dashboard.subtitle')}
            backTitle={has('project_access') && project_id !== "-1"
                ? Lang.get('pages.selections.dashboard.back.to_project')
                : Lang.get('pages.selections.dashboard.back.to_projects')}
            backHref={has('project_access') && project_id !== "-1"
                ? tRoute('projects.show', project_id)
                : tRoute('projects.index')}
        >
            <Row justify="space-around" gutter={[48, 16]}>
                {cards.map(card => (
                    <Col key={card.title} lg={11} xl={8}>
                        <DashboardCard {...card} key={card.title}/>
                    </Col>
                ))}
            </Row>
        </Container>
    )
}

Dashboard.layout = page => <AuthLayout children={page}/>

export default Dashboard
