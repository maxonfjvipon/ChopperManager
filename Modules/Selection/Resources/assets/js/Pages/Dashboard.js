import React from 'react';
import {Button, Col, Result, Row} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {DashboardCard} from "../Components/DashboardCard";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {BackToProjectsLink} from "../../../../../Project/Resources/assets/js/Components/BackToProjectsLink";
import {BackLink} from "../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";

export default function Dashboard() {
    // HOOKS
    const {project_id, selection_types, station_types} = usePage().props

    console.log(selection_types, station_types)

    const cards = selection_types.reduce((arr1, selection_type) => [
        ...arr1, ...station_types.reduce((arr2, station_type) => [
            ...arr2, {
                title: station_type.description + ', ' + selection_type.description,
                onClick: () => {
                    Inertia.get(route('selections.create', [project_id, station_type.key, selection_type.key]))
                }
            }
        ], [])
    ], [])

    return (
        <IndexContainer
            title="Выберите тип подбора"
            extra={<BackLink
                href={route('projects.show', project_id)}
                title="Назад к проекту"
            />}
        >
            {cards.length > 0 && <Row justify="space-around" gutter={[48, 16]}>
                {cards.map(card => (
                    <Col key={card.title} lg={11} xl={8}>
                        <DashboardCard {...card} key={card.title}/>
                    </Col>
                ))}
            </Row>}
            {cards.length === 0 && <Result
                status="404"
                title="Извините, у вас нет доступа ни к одному типу подбора"
                subTitle="Пожалуйста свяжитесь с администратором"
                extra={<Button type="primary" onClick={() => {
                    Inertia.get(route('projects.index'))
                }}>Вернуться к проектам</Button>}
            />}
        </IndexContainer>
    )
}
