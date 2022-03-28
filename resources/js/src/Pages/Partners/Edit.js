import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {ItemsForm} from "../../Shared/ItemsForm";
import {ResourceContainer} from "../../Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../Shared/Resource/Actions/SubmitAction";
import {useInputRules} from "../../Hooks/input-rules.hook";
import AuthLayout from "../../Shared/Layout/AuthLayout";
import {Link, usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../Shared/Inputs/Selection";

const Edit = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {partner, filter_data} = usePage().props

    // CONSTS
    const formName = 'create-project-form'
    const items = [
        {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
                initialValue: partner.data.name,
            }, input: <Input/>,
        }, {
            values: {
                name: "itn",
                label: "ИНН",
                rules: [rules.required],
                initialValue: partner.data.itn,
            }, input: <Input/>
        }, {
            values: {
                name: "area_id",
                label: "Область",
                rules: [rules.required],
                initialValue: partner.data.area_id,
            }, input: <Selection placeholder="Область" options={filter_data.areas}/>
        }, {
            values: {
                name: "address",
                label: "Адрес",
                initialValue: partner.data.address,
            }, input: <Input/>
        }, {
            values: {
                name: "phone",
                label: "Телефон",
                initialValue: partner.data.phone,
            }, input: <Input/>
        }, {
            values: {
                name: "email",
                label: "Почтовый адрес",
                initialValue: partner.data.email,
            }, input: <Input/>
        }, {
            values: {
                name: "description",
                label: "Описание",
                initialValue: partner.data.description,
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

Edit.layout = page => <AuthLayout children={page}/>

export default Edit
