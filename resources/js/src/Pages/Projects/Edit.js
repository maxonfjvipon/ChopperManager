import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../Hooks/input-rules.hook";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {ItemsForm} from "../../Shared/ItemsForm";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {usePage} from "@inertiajs/inertia-react";
import {SubmitAction} from "../../Shared/Resource/Actions/SubmitAction";
import {ResourceContainer} from "../../Shared/Resource/Containers/ResourceContainer";
import {BackToProjectsLink} from "../../Shared/Resource/BackLinks/BackToProjectsLink";
import {usePermissions} from "../../Hooks/permissions.hook";

const Edit = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
    const {has} = usePermissions()
    const {project} = usePage().props

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

Edit.layout = page => <AuthLayout children={page}/>

export default Edit
