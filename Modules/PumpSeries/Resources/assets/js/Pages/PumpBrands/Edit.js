import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToSeriesLink} from "../../Components/BackToSeriesLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";

export default function Edit() {
    // HOOKS
    const {rules} = useInputRules()
    const {brand, countries} = usePage().props

    const formName = 'edit-brand-form'
    const items = [
        {
            values: {
                name: 'name',
                label: "Наименование",
                rules: [rules.required],
                initialValue: brand.name,
            }, input: <Input/>,
        }, {
            values: {
                name: 'country',
                label: "Страна",
                rules: [rules.required],
                initialValue: brand.country,
            }, input: <Selection options={countries} placeholder="Страна"/>
        }
    ]

    // HANDLERS
    const updateBrandHandler = body => {
        Inertia.put(route('pump_brands.update', brand.id), body)
    }

    return (
        <ResourceContainer
            title={"Изменить бренд"}
            actions={<SubmitAction label="Изменить" form={formName}/>}
            extra={<BackToSeriesLink/>}
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
