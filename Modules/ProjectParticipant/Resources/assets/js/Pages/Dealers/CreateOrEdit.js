import React from 'react'
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";
import {Button, Divider, Form, Input, Select, Space, Switch, Table} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {BackToDealersLink} from "../../Components/BackToDealersLink";
import {useLabels} from "../../../../../../../resources/js/src/Hooks/labels.hook";
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {useStyles} from "../../../../../../../resources/js/src/Hooks/styles.hook";
import {PrimaryButton} from "../../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";

export default function CreateOrEdit() {
    // HOOKS
    const {rules} = useInputRules()
    const {filter_data, dealer} = usePage().props
    const {labels} = useLabels()

    // CONSTS
    const formName = 'dealer-form'
    const items = [
        {
            values: {
                name: 'name',
                label: labels.name,
                rules: [rules.required, rules.max(255)],
                initialValue: dealer?.name,
            }, input: <Input placeholder={labels.name}/>
        },
        {
            values: {
                name: 'area_id',
                label: labels.area,
                rules: [rules.required],
                initialValue: dealer?.area_id,
            }, input: <Selection placeholder={labels.area} options={filter_data.areas}/>
        },
        {
            values: {
                name: 'itn',
                label: labels.itn,
                rules: rules.itn,
                initialValue: dealer?.itn,
            }, input: <Input placeholder={labels.itn}/>
        },
        {
            values: {
                name: 'phone',
                label: labels.phone,
                rules: [rules.phone],
                initialValue: dealer?.phone,
            }, input: <Input placeholder={labels.phone}/>,
        },
        {
            values: {
                name: 'email',
                label: labels.email,
                rules: [rules.email],
                initialValue: dealer?.email,
            }, input: <Input placeholder={labels.email}/>
        },
        {
            values: {
                name: 'available_series_ids',
                label: labels.available_series,
                initialValue: dealer?.available_series_ids,
            }, input: <MultipleSelection placeholder={labels.available_series} options={filter_data.series}/>
        }
    ]

    // HANDLERS
    const createOrUpdateDealerHandler = body => {
        if (dealer) {
            Inertia.post(route('dealers.update', dealer.id), body)
        } else {
            Inertia.post(route('dealers.store'), body)
        }
    }

    // RENDER
    return (
        <ResourceContainer
            title={dealer ? "Изменить дилера" : "Создать дилера"}
            actions={<SubmitAction label={dealer ? "Изменить" : "Создать"} form={formName}/>}
            extra={<BackToDealersLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createOrUpdateDealerHandler}
            />
            <Divider orientation="left">
                Наценки
            </Divider>
            <PrimaryButton>
                Add a row
            </PrimaryButton>
        </ResourceContainer>
    )
}
