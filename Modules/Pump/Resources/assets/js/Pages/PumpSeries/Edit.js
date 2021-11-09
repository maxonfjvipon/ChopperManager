import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToSeriesLink} from "../../../../../../../resources/js/src/Shared/Resource/BackLinks/BackToSeriesLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {AuthLayout} from "../../../../../../../resources/js/src/Shared/Layout/AuthLayout";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";
import {usePage} from "@inertiajs/inertia-react";
import Lang from '../../../../../../../resources/js/translation/lang'
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";

const Create = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
    const {pump_series_props, series} = usePage().props
    const {has} = usePermissions()
    const {brands, categories, power_adjustments, applications, types} = pump_series_props.data

    // console.log(usePage().props)

    const formName = 'create-series-form'
    const items = [
        {
            values: {
                name: 'brand_id',
                label: Lang.get('pages.pump_series.edit.form.brand'),
                rules: [rules.required],
                initialValue: series.data.brand_id,
            }, input: <Selection options={brands}/>,
        }, {
            values: {
                name: 'name',
                label: Lang.get('pages.pump_series.edit.form.name'),
                rules: [rules.required],
                initialValue: series.data.name,
            }, input: <Input/>,
        }, {
            values: {
                name: 'power_adjustment_id',
                label: Lang.get('pages.pump_series.edit.form.power_adjustment'),
                rules: [rules.required],
                initialValue: series.data.power_adjustment_id,
            }, input: <Selection options={power_adjustments}/>,
        }, {
            values: {
                name: 'category_id',
                label: Lang.get('pages.pump_series.edit.form.category'),
                rules: [rules.required],
                initialValue: series.data.category_id,
            }, input: <Selection options={categories}/>,
        }, {
            values: {
                name: 'types',
                label: Lang.get('pages.pump_series.edit.form.types'),
                initialValue: series.data.types,
            }, input: <MultipleSelection options={types}/>,
        }, {
            values: {
                name: 'applications',
                label: Lang.get('pages.pump_series.edit.form.applications'),
                initialValue: series.data.applications,
            }, input: <MultipleSelection options={applications}/>,
        }
    ]

    // HANDLERS
    const createProjectHandler = body => {
        Inertia.put(tRoute('pump_series.update', series.data.id), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.pump_series.edit.title')}
            actions={has('series_edit') && <SubmitAction label={Lang.get('pages.pump_series.edit.button')} form={formName}/>}
            back={has('series_access', 'brand_access') && <BackToSeriesLink/>}
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

Create.layout = page => <AuthLayout children={page}/>

export default Create
