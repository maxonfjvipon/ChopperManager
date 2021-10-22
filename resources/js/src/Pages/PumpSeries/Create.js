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
import {Selection} from "../../Shared/Inputs/Selection";
import {usePage} from "@inertiajs/inertia-react";
import {MultipleSelection} from "../../Shared/Inputs/MultipleSelection";

const Create = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
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
        }
    ]

    // HANDLERS
    const createProjectHandler = body => {
        Inertia.post(tRoute('pump_series.store'), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.pump_series.create.title')}
            actions={<SubmitAction label={Lang.get('pages.pump_series.create.button')} form={formName}/>}
            back={<BackToSeriesLink/>}
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
