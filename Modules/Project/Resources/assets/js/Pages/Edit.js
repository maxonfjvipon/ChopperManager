import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {usePage} from "@inertiajs/inertia-react";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToProjectsLink} from "../Components/BackToProjectsLink";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";

export default function Edit() {
    // HOOKS
    const {rules} = useInputRules()
    const {filteredBoolArray} = usePermissions()
    const {project, areas, statuses, users, auth} = usePage().props

    // CONSTS
    const formName = 'edit-project-form'
    const items = filteredBoolArray([
        {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
                initialValue: project.name
            }, input: <Input placeholder="Наименование"/>,
        }, {
            values: {
                name: 'area_id',
                label: "Область",
                rules: [rules.required],
                initialValue: project.area_id
            }, input: <Selection options={areas} placeholder="Область"/>,
        }, {
            values: {
                name: 'status',
                label: "Статус",
                rules: [rules.required],
                initialValue: project.status,
            }, input: <Selection options={statuses} placeholder="Статус"/>,
        },
        auth.is_admin && {
            values: {
                name: 'customer_id',
                label: "Заказчик",
                initialValue: project.customer_id
            }, input: <Selection options={users} placeholder="Заказчик"/>,
        },
        auth.is_admin && {
            values: {
                name: 'installer_id',
                label: "Монтажник",
                initialValue: project.installer_id
            }, input: <Selection options={users} placeholder="Установщик"/>,
        },
        auth.is_admin && {
            values: {
                name: 'designer_id',
                label: "Проектировщик",
                initialValue: project.designer_id
            }, input: <Selection options={users} placeholder="Установщик"/>,
        },
        auth.is_admin && {
            values: {
                name: 'dealer_id',
                label: "Диллер",
                initialValue: project.dealer_id
            }, input: <Selection options={users} placeholder="Диллер"/>,
        }, {
            values: {
                name: 'description',
                label: "Описание",
                initialValue: project.description
            }, input: <Input.TextArea placeholder="Описание"/>,
        }
    ]);

    // HANDLERS
    const updateProjectHandler = body => {
        Inertia.put(route('projects.update', project.id), body)
    }

    // RENDER
    return (
        <ResourceContainer
            title="Изменить проект"
            actions={<SubmitAction label="Изменить" form={formName}/>}
            extra={<BackToProjectsLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={updateProjectHandler}
            />
        </ResourceContainer>
    )
}
