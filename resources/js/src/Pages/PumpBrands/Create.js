import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../Hooks/input-rules.hook";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {ItemsForm} from "../../Shared/ItemsForm";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {ResourceContainer} from "../../Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../Shared/Resource/Actions/SubmitAction";
import {BackToSeriesLink} from "../../Shared/Resource/BackLinks/BackToSeriesLink";
import {usePermissions} from "../../Hooks/permissions.hook";

const Create = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {has} = usePermissions()
    const {tRoute} = useTransRoutes()

    const formName = 'create-brand-form'
    const items = [
        {
            values: {
                name: 'name',
                label: Lang.get('pages.pump_brands.create.form.name'),
                rules: [rules.required],
            }, input: <Input/>,
        }
    ]

    // HANDLERS
    const createBrandHandler = body => {
        Inertia.post(tRoute('pump_brands.store'), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.pump_brands.create.title')}
            actions={has('brand_create') && <SubmitAction label={Lang.get('pages.pump_brands.create.button')} form={formName}/>}
            back={has('brand_access', 'series_access') && <BackToSeriesLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createBrandHandler}
            />
        </ResourceContainer>
    )
}

Create.layout = page => <AuthLayout children={page}/>

export default Create
