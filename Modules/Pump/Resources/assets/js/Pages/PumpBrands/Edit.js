import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToSeriesLink} from "../../Components/BackToSeriesLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {usePage} from "@inertiajs/inertia-react";
import Lang from '../../../../../../../resources/js/translation/lang'

export default function Edit() {
    // HOOKS
    const {rules} = useInputRules()
    const tRoute = useTransRoutes()
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
            extra={has('brand_access', 'series_access') && <BackToSeriesLink/>}
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
