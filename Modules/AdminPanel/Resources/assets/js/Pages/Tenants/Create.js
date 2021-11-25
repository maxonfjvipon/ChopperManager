import React from "react";
import {Input, Switch} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {PrimaryAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";
import {BackToTenantsLink} from "../../Shared/BackToTenantsLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";
import {usePage} from "@inertiajs/inertia-react";
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";

const Create = () => {
    const formName = "create-tenant-form"
    const requiredRule = {
        required: true,
        message: 'Field is required'
    }

    const {tenant_types, selection_types, default_selection_types} = usePage().props

    // console.log(tenant_types, selection_types)

    const items = [
        {
            values: {
                name: 'name',
                label: 'Name',
                rules: [requiredRule],
            }, input: <Input/>,
        },
        {
            values: {
                name: 'domain',
                rules: [requiredRule],
                label: 'Domain',
            }, input: <Input/>,

        },
        {
            values: {
                name: 'database',
                label: 'Database',
                rules: [requiredRule],
            }, input: <Input/>,

        },
        {
            values: {
                name: 'type_id',
                label: 'Type',
                rules: [requiredRule],
            }, input: <Selection options={tenant_types}/>,
        },
        {
            values: {
                name: 'selection_type_ids',
                label: 'Selection types',
                rules: [requiredRule],
                initialValue: default_selection_types,
            }, input: <MultipleSelection options={selection_types}/>,
        },
        {
            values: {
                name: 'has_registration',
                label: 'Has registration',
                rules: [requiredRule],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Switch/>,
        },
        {
            values: {
                name: 'is_active',
                label: 'Is active',
                rules: [requiredRule],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Switch/>,
        },
    ]

    const createTenantHandler = body => {
        Inertia.post(route('admin.tenants.store'), {
            ...body,
        })
    }

    return (
        <ResourceContainer
            title={"Create tenant"}
            actions={<SubmitAction label={"Create"} form={formName}/>}
            extra={<BackToTenantsLink/>}
        >
            <ItemsForm
                name={formName}
                layout={"vertical"}
                items={items}
                onFinish={createTenantHandler}
            />
        </ResourceContainer>
    )
}

Create.layout = page => <AuthLayout children={page}/>

export default Create
