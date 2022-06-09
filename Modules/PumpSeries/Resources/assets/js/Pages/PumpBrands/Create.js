import React from 'react';
import {Input, Switch} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToSeriesLink} from "../../Components/BackToSeriesLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";

export default function Create() {
    // HOOKS
    const {rules} = useInputRules()
    const {countries} = usePage().props

    const formName = 'create-brand-form'
    const items = [
        {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
            }, input: <Input/>,
        },
        {
            values: {
                name: 'country',
                label: "Страна",
                rules: [rules.required],
            }, input: <Selection options={countries} placeholder="Страна"/>
        }
    ]

    // HANDLERS
    const createBrandHandler = body => {
        Inertia.post(route('pump_brands.store'), body)
    }

    return (
        <ResourceContainer
            title="Создать бренд"
            actions={<SubmitAction label={"Создать"} form={formName}/>}
            extra={<BackToSeriesLink/>}
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
