import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {ItemsForm} from "../../Shared/ItemsForm";
import {ResourceContainer} from "../../Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../Shared/Resource/Actions/SubmitAction";
import {BackToProjectsLink} from "../../Shared/BackToProjectsLink";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Selection} from "../../Shared/Inputs/Selection";
import {usePage} from "@inertiajs/inertia-react";

export default function Create() {
    // HOOKS
    const {rules} = useInputRules()
    const {areas, statuses} = usePage().props

    // CONSTS
    const formName = 'create-project-form'
    const items = [
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
            }, input: <Selection options={areas} placeholder={"Область"}/>,
        }, {
            values: {
                name: 'status_id',
                label: "Статус",
                rules: [rules.required],
            }, input: <Selection options={statuses} placeholder="Статус"/>,
        }, {
            values: {
                name: 'customer_id',
                label: "Заказчик",
                rules: [rules.required],
            }, input: <Selection options={users} placeholder="Заказчик"/>,
        }, {
            values: {
                name: 'status_id',
                label: "Статус",
                rules: [rules.required],
            }, input: <Selection options={statuses} placeholder="Статус"/>,
        }, {
            values: {
                name: 'status_id',
                label: "Статус",
                rules: [rules.required],
            }, input: <Selection options={statuses} placeholder="Статус"/>,
        },
    ]

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
