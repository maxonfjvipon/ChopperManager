import React from 'react';
import {Input, Switch} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToSeriesLink} from "../Components/BackToSeriesLink";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {usePage} from "@inertiajs/inertia-react";

export default function Create() {
    // HOOKS
    const {rules} = useInputRules()
    const {pump_series_props, series} = usePage().props
    const {brands, currencies} = pump_series_props

    const formName = 'edit-series-form'
    const items = [
        {
            values: {
                name: 'brand_id',
                label: "Бренд",
                rules: [rules.required],
                initialValue: series.brand_id
            }, input: <Selection options={brands}/>,
        }, {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
                initialValue: series.name
            }, input: <Input/>,
        },{
            values: {
                name: 'currency',
                label: "Валюта",
                rules: [rules.required],
                initialValue: series.currency
            }, input: <Selection options={currencies}/>,
        }, {
            values: {
                name: 'is_discontinued',
                label: "Снята с производства",
                initialValue: series.is_discontinued,
                valuePropName: "checked",
            }, input: <Switch/>
        }
    ]

    // HANDLERS
    const updateSeriesHandler = body => {
        Inertia.put(route('pump_series.update', series.id), body)
    }

    return (
        <ResourceContainer
            title={"Изменить серию"}
            actions={<SubmitAction label={"Изменить"} form={formName}/>}
            extra={<BackToSeriesLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={updateSeriesHandler}
            />
        </ResourceContainer>
    )
}
