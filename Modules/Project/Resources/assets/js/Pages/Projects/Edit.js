import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../../../../../resources/js/translation/lang";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {usePage} from "@inertiajs/inertia-react";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToProjectsLink} from "../../Components/BackToProjectsLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";

export default function Edit() {
    // HOOKS
    const tRoute = useTransRoutes()
    const {rules} = useInputRules()
    const {has} = usePermissions()
    const {project} = usePage().props

    // CONSTS
    const formName = 'edit-project-form'
    const items = [
        {
            values: {
                name: 'name',
                label: Lang.get('pages.projects.edit.form.name'),
                rules: [rules.required],
                initialValue: project.data.name,
            }, input: <Input/>,
        }
    ]

    // HANDLERS
    const updateProjectHandler = body => {
        Inertia.put(tRoute('projects.update', project.data.id), body)
    }

    // RENDER
    return (
        <ResourceContainer
            title={Lang.get('pages.projects.edit.title')}
            actions={has('project_edit') && <SubmitAction label={Lang.get('pages.projects.edit.button')} form={formName}/>}
            extra={has('project_access') && <BackToProjectsLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={updateProjectHandler}
            />
        </ResourceContainer>
    )
}
