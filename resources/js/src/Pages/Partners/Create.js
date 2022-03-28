import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {ItemsForm} from "../../Shared/ItemsForm";
import {ResourceContainer} from "../../Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../Shared/Resource/Actions/SubmitAction";
import {BackToProjectsLink} from "../../Shared/BackToProjectsLink";
import {useInputRules} from "../../Hooks/input-rules.hook";
import AuthLayout from "../../Shared/Layout/AuthLayout";
import {Link, usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../Shared/Inputs/Selection";

const Create = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {areas} = usePage().props

    // CONSTS
    const formName = 'create-project-form'
    const items = [
        {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
            }, input: <Input/>,
        }, {
            values: {
                name: "itn",
                label: "ИНН",
                rules: [rules.required],
            }, input: <Input/>
        }, {
            values: {
                name: "area_id",
                label: "Область",
                rules: [rules.required],
            }, input: <Selection placeholder="Область" options={areas}/>
        }, {
            values: {
                name: "address",
                label: "Адрес",
            }, input: <Input/>
        }, {
            values: {
                name: "phone",
                label: "Телефон",
            }, input: <Input/>
        }, {
            values: {
                name: "email",
                label: "Почтовый адрес",
            }, input: <Input/>
        }, {
            values: {
                name: "description",
                label: "Описание",
            }, input: <Input.TextArea/>
        }
    ]

    // HANDLERS
    const createPartnerHandler = body => {
        Inertia.post(route('partners.store'), body)
    }

    // RENDER
    return (
        <ResourceContainer
            title="Создать контрагент"
            actions={<SubmitAction label="Создать" form={formName}/>}
            extra={<Link href={route('partners.index')}>{"<<Назад к контрагентам"}</Link>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createPartnerHandler}
            />
        </ResourceContainer>
    )
}

Create.layout = page => <AuthLayout children={page}/>

export default Create
