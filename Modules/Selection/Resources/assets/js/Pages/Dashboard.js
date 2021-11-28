import React from 'react';
import {Button, Col, Result, Row} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import Lang from "../../../../../../resources/js/translation/lang";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {DashboardCard} from "../Components/DashboardCard";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {BackToProjectsLink} from "../../../../../Core/Resources/assets/js/Components/BackToProjectsLink";
import {BackLink} from "../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";

export default function Dashboard() {
    // HOOKS
    const {project_id, selection_types} = usePage().props
    const tRoute = useTransRoutes()
    const {has, filterPermissionsArray} = usePermissions()

    // CONSTS
    const span = () => {
        if (selection_types.length === 1) {
            return 16
        }
        if (selection_types.length === 2)
            return 11
        return 8
    }

    const _cards = filterPermissionsArray(selection_types.map(type => {
        return has('selection_create') && {
            title: type.name,
            src: type.img,
            onClick: () => {
                const route = 'projects.' + type.prefix + '_selections.create'
                Inertia.get(tRoute(route, project_id))
            }
        }
    }))

    return (
        <IndexContainer
            title={Lang.get('pages.selections.dashboard.subtitle')}
            extra={project_id === "-1"
                ? <BackToProjectsLink/>
                : <BackLink href={tRoute('projects.show', project_id)} title={Lang.get('pages.selections.dashboard.back.to_project')}/>
            }
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
                title={Lang.get('pages.selections.dashboard.404.title')}
                subTitle={Lang.get('pages.selections.dashboard.404.subtitle')}
                extra={<Button type="primary" onClick={() => {
                    Inertia.get(tRoute('projects.index'))
                }}>{Lang.get('pages.selections.dashboard.404.back')}</Button>}
            />}
        </IndexContainer>
    )
}
