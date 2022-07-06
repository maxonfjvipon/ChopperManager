import React, {useState} from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {usePage} from "@inertiajs/inertia-react";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToProjectsLink} from "../Components/BackToProjectsLink";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {WithContractors} from "../Components/WithContractors";
import {useLabels} from "../../../../../../resources/js/src/Hooks/labels.hook";

export default function CreateOrEdit() {
    // HOOKS
    const {rules} = useInputRules()
    const {filteredBoolArray} = usePermissions()
    const {project, auth, filter_data} = usePage().props
    const {labels} = useLabels()

    // STATE
    const [contractors, setContractors] = useState([])
    const [contractorsSearchValue, setContractorsSearchValue] = useState(null)

    // CONSTS
    const formName = 'project-form'
    const contractorPlaceholder = "Начните вводить название или ИНН организации..."
    const items = filteredBoolArray([
        {
            values: {
                name: 'name',
                label: labels.name,
                rules: [rules.required],
                initialValue: project?.name
            }, input: <Input placeholder={labels.name}/>,
        }, {
            values: {
                name: 'area_id',
                label: labels.area,
                rules: [rules.required],
                initialValue: project?.area_id
            }, input: <Selection options={filter_data.areas} placeholder={labels.area}/>,
        }, {
            values: {
                name: 'status',
                label: labels.status,
                rules: [rules.required],
                initialValue: project?.status,
            }, input: <Selection
                options={filter_data.statuses}
                placeholder={labels.status}
            />,
        },
        {
            values: {
                name: 'customer',
                label: labels.customer,
                initialValue: project?.customer
            }, input: <Selection
                options={contractors}
                placeholder={contractorPlaceholder}
                onSearch={value => setContractorsSearchValue(value)}
            />,
        },
        {
            values: {
                name: 'installer',
                label: labels.installer,
                initialValue: project?.installer
            }, input: <Selection
                options={contractors}
                placeholder={contractorPlaceholder}
                onSearch={value => setContractorsSearchValue(value)}
            />,
        },
        {
            values: {
                name: 'designer',
                label: labels.designer,
                initialValue: project?.designer
            }, input: <Selection
                options={contractors}
                placeholder={contractorPlaceholder}
                onSearch={value => setContractorsSearchValue(value)}
            />,
        },
        auth.is_admin && {
            values: {
                name: 'dealer_id',
                label: labels.dealer,
                initialValue: project?.dealer_id
            }, input: <Selection options={filter_data.dealers} placeholder={labels.dealer}/>,
        }, {
            values: {
                name: 'description',
                label: labels.description,
                initialValue: project?.description
            }, input: <Input.TextArea placeholder={labels.description}/>,
        }
    ]);

    // HANDLERS
    const createOrUpdateProjectHandler = body => {
        if (project) {
            Inertia.put(route('projects.update', project.id), body)
        } else {
            Inertia.post(route('projects.store'), body)
        }
    }

    // RENDER
    return (
        <WithContractors
            setContractors={setContractors}
            contractorsSearchValue={contractorsSearchValue}
        >
            <ResourceContainer
                title={project ? "Изменить проект" : "Создать проект"}
                actions={<SubmitAction label={project ? "Изменить" : "Создать"} form={formName}/>}
                extra={<BackToProjectsLink/>}
            >
                <ItemsForm
                    layout="vertical"
                    items={items}
                    name={formName}
                    onFinish={createOrUpdateProjectHandler}
                />
            </ResourceContainer>
        </WithContractors>
    )
}
