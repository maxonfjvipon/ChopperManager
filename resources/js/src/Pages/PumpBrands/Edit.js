import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../Hooks/input-rules.hook";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {ItemsForm} from "../../Shared/ItemsForm";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {usePage} from "@inertiajs/inertia-react";
import {SubmitAction} from "../../Shared/Resource/Actions/SubmitAction";
import {ResourceContainer} from "../../Shared/Resource/Containers/ResourceContainer";
import {BackToSeriesLink} from "../../Shared/Resource/BackLinks/BackToSeriesLink";
import {usePermissions} from "../../Hooks/permissions.hook";

const Edit = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
    const {has} = usePermissions()
    const {brand} = usePage().props

    const formName = 'edit-brand-form'
    const items = [
        {
            values: {
                name: 'name',
                label: Lang.get('pages.pump_brands.edit.form.name'),
                rules: [rules.required],
                initialValue: brand.data.name,
            }, input: <Input/>,
        }
    ]

    // HANDLERS
    const updateBrandHandler = body => {
        Inertia.put(tRoute('pump_brands.update', brand.data.id), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.pump_brands.edit.title')}
            actions={has('brand_edit') && <SubmitAction label={Lang.get('pages.pump_brands.edit.button')} form={formName}/>}
            back={has('brand_access', 'series_access') && <BackToSeriesLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={updateBrandHandler}
            />
        </ResourceContainer>
    )
}

Edit.layout = page => <AuthLayout children={page}/>

export default Edit
