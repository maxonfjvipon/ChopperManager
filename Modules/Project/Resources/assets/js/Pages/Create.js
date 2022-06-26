import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToProjectsLink} from "../Components/BackToProjectsLink";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";

export default function Create() {
    // HOOKS
    const {rules} = useInputRules()
    const {areas, statuses, users, auth, contractors} = usePage().props
    const {filteredBoolArray} = usePermissions()

    // CONSTS
    const formName = 'create-project-form'
    const items = filteredBoolArray([
        {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
            }, input: <Input placeholder="Наименование"/>,
        }, {
            values: {
                name: 'area_id',
                label: "Область",
                rules: [rules.required],
            }, input: <Selection options={areas} placeholder="Область"/>,
        }, {
            values: {
                name: 'status',
                label: "Статус",
                rules: [rules.required],
            }, input: <Selection options={statuses} placeholder="Статус"/>,
        }, auth.is_admin && {
            values: {
                name: 'customer_id',
                label: "Заказчик",
            }, input: <Selection options={contractors} placeholder="Заказчик"/>,
        }, auth.is_admin && {
            values: {
                name: 'installer_id',
                label: "Установщик",
            }, input: <Selection options={contractors} placeholder="Установщик"/>,
        }, auth.is_admin && {
            values: {
                name: 'designer_id',
                label: "Проектировщик",
            }, input: <Selection options={contractors} placeholder="Установщик"/>,
        }, auth.is_admin && {
            values: {
                name: 'dealer_id',
                label: "Диллер",
            }, input: <Selection options={users} placeholder="Диллер"/>,
        }, {
            values: {
                name: 'description',
                label: "Описание",
            }, input: <Input.TextArea placeholder="Описание"/>,
        }
    ]);

    // HANDLERS
    const createProjectHandler = body => {
        Inertia.post(route('projects.store'), body)
    }

    // RENDER
    return (
        <ResourceContainer
            title="Создать проект"
            actions={<SubmitAction label="Создать" form={formName}/>}
            extra={<BackToProjectsLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createProjectHandler}
            />
        </ResourceContainer>
    )
}
