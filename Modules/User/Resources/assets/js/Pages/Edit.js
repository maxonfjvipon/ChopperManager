import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {AuthLayout} from "../../../../../../resources/js/src/Shared/Layout/AuthLayout";
import Lang from "../../../../../../resources/js/translation/lang";
import {SubmitAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToUsersLink} from "../../../../../../resources/js/src/Shared/Resource/BackLinks/BackToUsersLink";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {usePage} from "@inertiajs/inertia-react";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {MultipleSelection} from "../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";

const Edit = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
    const {has} = usePermissions()
    const {user, filter_data} = usePage().props

    const formName = 'edit-project-form'
    const items = [
        {
            values: {
                name: 'organization_name',
                label: Lang.get('pages.users.edit.form.organization_name'),
                rules: [rules.required],
                initialValue: user.data.organization_name,
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'business',
                label: Lang.get('pages.users.edit.form.main_business'),
                rules: [rules.required],
                initialValue: user.data.business
            },
            input: <Input readOnly/>
        },
        {
            values: {
                name: 'itn',
                label: Lang.get('pages.users.edit.form.itn'),
                rules: rules.itn,
                initialValue: user.data.itn,
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'phone',
                label: Lang.get('pages.users.edit.form.phone'),
                rules: rules.phone,
                initialValue: user.data.phone,
            },
            input: <Input readOnly/>,
        },
        {
            values: {
                name: 'country',
                label: Lang.get('pages.users.edit.form.country'),
                rules: [rules.required],
                initialValue: user.data.country
            },
            input: <Input readOnly/>
        },
        {
            values: {name: 'city', label: Lang.get('pages.users.edit.form.city'),
                initialValue: user.data.city, rules: [rules.required]},
            input: <Input readOnly/>
        },
        {
            values: {
                name: 'postcode',
                label: Lang.get('pages.users.edit.form.postcode'),
                initialValue: user.data.postcode,
                // tooltip: Lang.get('pages.profile.index.currency.tooltip'),
            },
            input: <Input readOnly/>
        },
        {
            values: {
                name: 'first_name',
                label: Lang.get('pages.users.edit.form.first_name'),
                rules: [rules.required, rules.max(255)],
                initialValue: user.data.first_name
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'middle_name',
                label: Lang.get('pages.users.edit.form.middle_name'),
                rules: [rules.required, rules.max(255)],
                initialValue: user.data.middle_name
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'last_name',
                label: Lang.get('pages.users.edit.form.last_name'),
                rules: [rules.max(255)],
                initialValue: user.data.last_name
            }, input: <Input readOnly/>
        },
        {
            values: {
                name: 'email',
                label: Lang.get('pages.users.edit.form.email'),
                rules: rules.email,
                initialValue: user.data.email,
            },
            input: <Input readOnly/>
        },
        {
            values: {
                name: 'available_series_ids',
                label: Lang.get('pages.users.edit.form.available_series'),
                initialValue: user.data.available_series_ids,
            },
            input: <MultipleSelection options={filter_data.series}/>
        },
        {
            values: {
                name: 'available_selection_type_ids',
                label: Lang.get('pages.users.edit.form.available_selection_types'),
                initialValue: user.data.available_selection_type_ids,
            },
            input: <MultipleSelection options={filter_data.selection_types}/>
        },
    ]

    // HANDLERS
    const updateUserHandler = body => {
        Inertia.put(tRoute('users.update', user.data.id), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.users.edit.title')}
            actions={has('user_edit') && <SubmitAction label={Lang.get('pages.users.edit.button')} form={formName}/>}
            extra={has('user_access') && <BackToUsersLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={updateUserHandler}
            />
        </ResourceContainer>
    )
}

Edit.layout = page => <AuthLayout children={page}/>

export default Edit
