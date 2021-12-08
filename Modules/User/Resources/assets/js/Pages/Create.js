import React from 'react';
import {Input, Switch} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import Lang from "../../../../../../resources/js/translation/lang";
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
    const tRoute = useTransRoutes()
    const {has} = usePermissions()
    const filter_data = usePage().props


    const formName = 'edit-project-form'
    const items = [
        {
            values: {
                name: 'organization_name',
                label: Lang.get('pages.users.create.form.organization_name'),
                rules: [rules.required],
                // initialValue: user.data.organization_name,
            }, input: <Input/>
        },
        {
            values: {
                name: 'business_id',
                label: Lang.get('pages.users.create.form.main_business'),
                rules: [rules.required],
                // initialValue: user.data.business_id,
            },
            input: <Selection options={filter_data.businesses}/>
        },
        {
            values: {
                name: 'itn',
                label: Lang.get('pages.users.create.form.itn'),
                rules: rules.itn,
                // initialValue: user.data.itn,
            }, input: <Input/>
        },
        {
            values: {
                name: 'phone',
                label: Lang.get('pages.users.create.form.phone'),
                rules: rules.phone,
                // initialValue: user.data.phone,
            },
            input: <Input/>,
        },
        {
            values: {
                name: 'country_id',
                label: Lang.get('pages.users.create.form.country'),
                rules: [rules.required],
                // initialValue: user.data.country_id
            },
            input: <Selection options={filter_data.countries}/>
        },
        {
            values: {
                name: 'city', label: Lang.get('pages.users.create.form.city'),
                rules: [rules.required],
                // initialValue: user.data.city, rules: [rules.required]},
            },
            input: <Input/>
        },
        {
            values: {
                name: 'postcode',
                label: Lang.get('pages.users.create.form.postcode'),
                // initialValue: user.data.postcode,
                tooltip: Lang.get('pages.profile.index.currency.tooltip'),
            },
            input: <Input/>
        },
        {
            values: {
                name: 'first_name',
                label: Lang.get('pages.users.create.form.first_name'),
                rules: [rules.required, rules.max(255)],
                // initialValue: user.data.first_name
            }, input: <Input/>
        },
        {
            values: {
                name: 'middle_name',
                label: Lang.get('pages.users.create.form.middle_name'),
                rules: [rules.required, rules.max(255)],
                // initialValue: user.data.middle_name
            }, input: <Input/>
        },
        {
            values: {
                name: 'last_name',
                label: Lang.get('pages.users.create.form.last_name'),
                rules: [rules.max(255)],
                // initialValue: user.data.last_name
            }, input: <Input/>
        },
        {
            values: {
                name: 'email',
                label: Lang.get('pages.users.create.form.email'),
                rules: rules.email,
                // initialValue: user.data.email,
            },
            input: <Input/>
        },
        {
            values: {
                name: 'password',
                label: Lang.get('pages.register.password'),
                rules: rules.password
            },
            input: <Input.Password/>
        },
        {
            values: {
                name: 'password_confirmation',
                label: Lang.get('pages.register.password_confirmation'),
                rules: rules.password
            },
            input: <Input.Password/>
        },
        {
            values: {
                name: 'available_series_ids',
                label: Lang.get('pages.users.create.form.available_series'),
                // initialValue: user.data.available_series_ids,
            },
            input: <MultipleSelection options={filter_data.series}/>
        },
        {
            values: {
                name: 'available_selection_type_ids',
                label: Lang.get('pages.users.create.form.available_selection_types'),
                // initialValue: user.data.available_selection_type_ids,
            },
            input: <MultipleSelection options={filter_data.selection_types}/>
        },
        {
            values: {
                name: 'is_active',
                label: Lang.get('pages.users.create.form.is_active'),
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Switch/>,
        },
        {
            values: {
                name: 'email_verified',
                label: Lang.get('pages.users.create.form.email_verified'),
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Switch/>,
        }
    ]

    // HANDLERS
    const createUserHandler = body => {
        Inertia.post(tRoute('users.store'), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.users.create.title')}
            actions={has('user_create') && <SubmitAction label={Lang.get('pages.users.create.button')} form={formName}/>}
            extra={has('user_access') && <BackToUsersLink/>}
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
