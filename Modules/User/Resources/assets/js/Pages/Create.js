import React from 'react';
import {Input, Switch} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {SubmitAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {usePage} from "@inertiajs/inertia-react";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {MultipleSelection} from "../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {BackToUsersLink} from "../Components/BackToUsersLink";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";

export default function Create () {
    // HOOKS
    const {rules} = useInputRules()
    const {filter_data} = usePage().props


    const formName = 'create-project-form'
    const items = [
        {
            values: {
                name: 'role',
                label: "Роль",
                rules: [rules.required],
            },
            input: <Selection options={filter_data.roles}/>
        },
        {
            values: {
                name: 'organization_name',
                label: "Наименование организации",
            }, input: <Input/>
        },
        {
            values: {
                name: 'itn',
                label: "ИНН",
                rules: rules.itn,
            }, input: <Input/>
        },
        {
            values: {
                name: 'phone',
                label: "Контактный телефон",
                rules: [rules.phone],
            },
            input: <Input/>,
        },
        {
            values: {
                name: 'area_id',
                label: "Область",
                rules: [rules.required],
            },
            input: <Selection options={filter_data.areas}/>
        },
        {
            values: {
                name: 'first_name',
                label: "Имя",
                rules: [rules.required, rules.max(255)],
            }, input: <Input/>
        },
        {
            values: {
                name: 'middle_name',
                label: "Фамилия",
                rules: [rules.required, rules.max(255)],
            }, input: <Input/>
        },
        {
            values: {
                name: 'last_name',
                label: "Отчество",
                rules: [rules.max(255)],
            }, input: <Input/>
        },
        {
            values: {
                name: 'email',
                label: "Email",
                rules: rules.email,
            },
            input: <Input/>
        },
        {
            values: {
                name: 'password',
                label: "Пароль",
                rules: rules.password
            },
            input: <Input.Password/>
        },
        {
            values: {
                name: 'password_confirmation',
                label: "Подтверждение пароля",
                rules: rules.password
            },
            input: <Input.Password/>
        },
        {
            values: {
                name: 'available_series_ids',
                label: "Доступные серии",
            },
            input: <MultipleSelection options={filter_data.series}/>
        },
        {
            values: {
                name: 'is_active',
                label: "Активен",
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Switch/>,
        },
    ]

    // HANDLERS
    const createUserHandler = body => {
        Inertia.post(route('users.store'), body)
    }

    return (
        <ResourceContainer
            title="Создать пользователя"
            actions={<SubmitAction label="Создать" form={formName}/>}
            extra={<BackToUsersLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createUserHandler}
            />
        </ResourceContainer>
    )
}
