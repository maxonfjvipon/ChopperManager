import React from 'react';
import {Input} from "antd";
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
    const {brands, currencies} = usePage().props.pump_series_props

    const formName = 'create-series-form'
    const items = [
        {
            values: {
                name: 'brand_id',
                label: "Бренд",
                rules: [rules.required],
            }, input: <Selection options={brands}/>,
        }, {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
            }, input: <Input/>,
        }, {
            values: {
                name: 'currency',
                label: "Валюта",
                rules: [rules.required],
            }, input: <Selection options={currencies}/>,
        }
    ]

    // HANDLERS
    const createProjectHandler = body => {
        Inertia.post(route('pump_series.store'), body)
    }

    return (
        <ResourceContainer
            title="Создать серию"
            actions={<SubmitAction label={"Создать"} form={formName}/>}
            extra={<BackToSeriesLink/>}
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
