import React from 'react';
import {Input, Select, Switch} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {SubmitAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {usePage} from "@inertiajs/inertia-react";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {BackToUsersLink} from "../Components/BackToUsersLink";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";

export default function CreateOrEdit() {
    // HOOKS
    const {rules} = useInputRules()
    const {filter_data, user} = usePage().props

    // CONSTS
    const labels = {
        role: "Роль",
        dealer: "Дилер",
        itn: "ИНН",
        phone: "Контактный телефон",
        area: "Область",
        first_name: "Имя",
        middle_name: "Фамилия",
        last_name: "Отчество",
        email: "Email",
        password: "Пароль",
        password_confirmation: "Подтверждение пароля",
        is_active: "Активен",
    }
    const formName = 'user-form'
    const items = [
        {
            values: {
                name: 'role',
                label: labels.role,
                rules: [rules.required],
                initialValue: user?.role
            },
            input: <Selection placeholder={labels.role} options={filter_data.roles}/>
        },
        {
            values: {
                name: 'dealer_id',
                label: labels.dealer,
                rules: [rules.required],
                initialValue: user?.dealer_id,
            }, input: <Selection placeholder={labels.dealer} options={filter_data.dealers}/>
        },
        {
            values: {
                name: 'itn',
                label: labels.itn,
                rules: rules.itn,
                initialValue: user?.itn,
            }, input: <Input placeholder={labels.itn}/>
        },
        {
            values: {
                name: 'phone',
                label: labels.phone,
                rules: [rules.phone],
                initialValue: user?.phone,
            }, input: <Input placeholder={labels.phone}/>,
        },
        {
            values: {
                name: 'area_id',
                label: labels.area,
                rules: [rules.required],
                initialValue: user?.area_id,
            }, input: <Selection placeholder={labels.area} options={filter_data.areas}/>
        },
        {
            values: {
                name: 'first_name',
                label: labels.first_name,
                rules: [rules.required, rules.max(255)],
                initialValue: user?.first_name,
            }, input: <Input placeholder={labels.first_name}/>
        },
        {
            values: {
                name: 'middle_name',
                label: labels.middle_name,
                rules: [rules.required, rules.max(255)],
                initialValue: user?.middle_name,
            }, input: <Input placeholder={labels.middle_name}/>
        },
        {
            values: {
                name: 'last_name',
                label: labels.last_name,
                rules: [rules.max(255)],
                initialValue: user?.last_name,
            }, input: <Input placeholder={labels.last_name}/>
        },
        {
            values: {
                name: 'email',
                label: labels.email,
                rules: rules.rq_email,
                initialValue: user?.email,
            }, input: <Input placeholder={labels.email}/>
        },
        {
            values: {
                name: 'password',
                label: labels.password,
                rules: user ? [] : rules.password,
            }, input: <Input.Password placeholder={labels.password}/>
        },
        {
            values: {
                name: 'password_confirmation',
                label: labels.password_confirmation,
                rules: user ? [] : rules.password,
            }, input: <Input.Password placeholder={labels.password_confirmation}/>
        },
        {
            values: {
                name: 'is_active',
                label: labels.is_active,
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: user ? user.is_active : true,
            }, input: <Switch/>,
        },
    ]

    // HANDLERS
    const createOrUpdateUserHandler = body => {
        if (user) {
            Inertia.post(route('users.update', user.id), body)
        } else {
            Inertia.post(route('users.store'), body)
        }
    }

    // RENDER
    return (
        <ResourceContainer
            title={user ? "Изменить пользователя" : "Создать пользователя"}
            actions={<SubmitAction label={user ? "Изменить" : "Создать"} form={formName}/>}
            extra={<BackToUsersLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createOrUpdateUserHandler}
            />
        </ResourceContainer>
    )
}
