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
import {BackToProjectsLink} from "../../Shared/Resource/BackLinks/BackToProjectsLink";
import {usePermissions} from "../../Hooks/permissions.hook";

const Create = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
    const {has} = usePermissions()

    const formName = 'create-project-form'
    const items = [
        {
            values: {
                name: 'name',
                label: Lang.get('pages.projects.create.form.name'),
                rules: [rules.required],
            }, input: <Input/>,
        }
    ]

    // HANDLERS
    const createProjectHandler = body => {
        Inertia.post(tRoute('projects.store'), body)
    }

    return (
        <ResourceContainer
            title={Lang.get('pages.projects.create.title')}
            actions={has('project_create') && <SubmitAction label={Lang.get('pages.projects.create.button')} form={formName}/>}
            extra={has('project_access') && <BackToProjectsLink/>}
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
