import React from 'react';
import {Button, Col, message, Result, Row} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {DashboardCard} from "../../../../../../../resources/js/src/Shared/DashboardCard";
import {AuthLayout} from "../../../../../../../resources/js/src/Shared/Layout/AuthLayout";
import {Container} from "../../../../../../../resources/js/src/Shared/ResourcePanel/Index/Container";
import Lang from "../../../../../../../resources/js/translation/lang";

const Dashboard = () => {
    // HOOKS
    const {project_id, selection_types} = usePage().props
    const {tRoute} = useTransRoutes()
    const {has, filterPermissionsArray} = usePermissions()

    const serviceUnavailable = () => {
        message.info(Lang.get('messages.service_development'))
    }

    const span = () => {
        if (selection_types.length === 1) {
            return 16
        }
        if (selection_types.length === 2)
            return 11
        return 8
    }

    // CONSTS
    const imgPath = "/img/selections-dashboard/"

    const _cards = filterPermissionsArray(selection_types.map(type => {
        console.log(type.img)
        return has('selection_create') && {
            title: type.name,
            src: type.img,
            onClick: () => {
                const route = 'selections.' + type.prefix + '.create'
                Inertia.get(tRoute(route, project_id))
            }
        }
    }))

    // const cards = filterPermissionsArray([
    //     has('selection_create') && {
    //         title: Lang.get('pages.selections.dashboard.preferences.pump.single'),
    //         src: imgPath + "01.png",
    //         onClick: () => {
    //             Inertia.get(tRoute('selections.create', project_id))
    //         }
    //     },
    //     {
    //         title: Lang.get('pages.selections.dashboard.preferences.pump.double'),
    //         src: imgPath + "02.png",
    //         onClick: serviceUnavailable
    //     },
    //     {
    //         title: Lang.get('pages.selections.dashboard.preferences.pump.borehole'),
    //         src: imgPath + "03.png",
    //         onClick: serviceUnavailable
    //     },
    //     {
    //         title: Lang.get('pages.selections.dashboard.preferences.pump.drainage'),
    //         src: imgPath + "04.png",
    //         onClick: serviceUnavailable
    //     },
    //     {
    //         title: Lang.get('pages.selections.dashboard.preferences.station.water'),
    //         src: imgPath + "05.png",
    //         onClick: serviceUnavailable
    //     },
    //     {
    //         title: Lang.get('pages.selections.dashboard.preferences.station.fire'),
    //         src: imgPath + "06.png",
    //         onClick: serviceUnavailable
    //     },
    // ])

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
            {_cards.length > 0 && <Row justify="space-around" gutter={[48, 16]}>
                {_cards.map(card => (
                    <Col key={card.title} lg={11} xl={span()}>
                        <DashboardCard {...card} key={card.title}/>
                    </Col>
                ))}
            </Row>}
            {_cards.length === 0 && <Result
                status="404"
                title="Sorry, you don't have access to any of selection types"
                subTitle="Please contact the administrator"
                extra={<Button type="primary" onClick={() => {
                    Inertia.get(tRoute('projects.index'))
                }}>Go to projects</Button>}
            />}
        </Container>
    )
}

Dashboard.layout = page => <AuthLayout children={page}/>

export default Dashboard
