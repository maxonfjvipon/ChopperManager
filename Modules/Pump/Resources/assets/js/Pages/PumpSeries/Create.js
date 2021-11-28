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
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {usePage} from "@inertiajs/inertia-react";
import Lang from '../../../../../../../resources/js/translation/lang'


export default function Create() {
    // HOOKS
    const {rules} = useInputRules()
    const tRoute = useTransRoutes()
    const {has} = usePermissions()
    const {brands, categories, power_adjustments, applications, types} = usePage().props.pump_series_props.data

    const formName = 'create-series-form'
    const items = [
        {
            values: {
                name: 'brand_id',
                label: Lang.get('pages.pump_series.create.form.brand'),
                rules: [rules.required],
            }, input: <Selection options={brands}/>,
        }, {
            values: {
                name: 'name',
                label: Lang.get('pages.pump_series.create.form.name'),
                rules: [rules.required],
            }, input: <Input/>,
        }, {
            values: {
                name: 'power_adjustment_id',
                label: Lang.get('pages.pump_series.create.form.power_adjustment'),
                rules: [rules.required],
            }, input: <Selection options={power_adjustments}/>,
        }, {
            values: {
                name: 'category_id',
                label: Lang.get('pages.pump_series.create.form.category'),
                rules: [rules.required],
            }, input: <Selection options={categories}/>,
        }, {
            values: {
                name: 'types',
                label: Lang.get('pages.pump_series.create.form.types'),
            }, input: <MultipleSelection options={types}/>,
        }, {
            values: {
                name: 'applications',
                label: Lang.get('pages.pump_series.create.form.applications'),
            }, input: <MultipleSelection options={applications}/>,
        }, {
            values: {
                name: 'image',
                label: Lang.get('pages.pump_series.edit.form.image_path'),
            }, input: <Input/>,
        }
    ]

    // HANDLERS
    const createProjectHandler = body => {
        Inertia.post(tRoute('pump_series.store'), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.pump_series.create.title')}
            actions={has('series_create') && <SubmitAction label={Lang.get('pages.pump_series.create.button')} form={formName}/>}
            extra={has('series_access', 'brand_access') && <BackToSeriesLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createProjectHandler}
            />
        </ResourceContainer>
    )
}
