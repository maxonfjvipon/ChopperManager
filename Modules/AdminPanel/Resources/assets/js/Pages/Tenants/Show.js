import React, {useState} from "react";
import {usePage} from "@inertiajs/inertia-react";
import {Input, Switch} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {BackToTenantsLink} from "../../Shared/BackToTenantsLink";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";

const Show = () => {
    const {tenant, selection_types, tenant_types} = usePage().props
    const formName = "show-tenant-form"
    const requiredRule = {
        required: true,
        message: 'Field is required'
    }

    const items = [
        {
            values: {
                name: 'name',
                label: 'Name',
                initialValue: tenant.data.name,
                rules: [requiredRule],
            }, input: <Input/>,
        },
        {
            values: {
                name: 'domain',
                rules: [requiredRule],
                label: 'Domain',
                initialValue: tenant.data.domain,
            }, input: <Input/>,

        },
        {
            values: {
                name: 'database',
                label: 'Database',
                rules: [requiredRule],
                initialValue: tenant.data.database,
                tooltip: "Changing database is not allowed yet"
            }, input: <Input readOnly/>,
        },
        {
            values: {
                name: 'type_id',
                label: 'Type',
                rules: [requiredRule],
                initialValue: tenant.data.type,
            }, input: <Selection disabled options={tenant_types}/>,
        },
        tenant.data.type === 2 && {
            values: {
                name: 'selection_type_ids',
                label: 'Selection types',
                rules: [requiredRule],
                initialValue: tenant.data.selection_type_ids,
            }, input: <MultipleSelection options={selection_types}/>,
        },
        {
            values: {
                name: 'has_registration',
                label: 'Has registration',
                rules: [requiredRule],
                valuePropName: "checked",
                initialValue: tenant.data.has_registration,
            }, input: <Switch/>,
        },
        {
            values: {
                name: 'is_active',
                label: 'Is active',
                rules: [requiredRule],
                valuePropName: "checked",
                initialValue: tenant.data.is_active,
            }, input: <Switch/>,
        },
    ].filter(Boolean)

    const updateTenantHandler = body => {
        Inertia.put(route('admin.tenants.update', tenant.data.id), {
            ...body,
            id: tenant.data.id
        })
    }

    // RENDER
    return (
        <ResourceContainer
            title={tenant.data.name}
            actions={<SubmitAction form={formName} label={"Save"}/>}
            back={<BackToTenantsLink/>}
        >
            <ItemsForm
                name={formName}
                layout={"vertical"}
                items={items}
                onFinish={updateTenantHandler}
            />
        </ResourceContainer>
    )

}

Show.layout = page => <AuthLayout children={page}/>

export default Show
