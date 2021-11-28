import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../../../../../resources/js/translation/lang";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {BackToProjectsLink} from "../../Components/BackToProjectsLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";

export default function Create() {
    // HOOKS
    const tRoute = useTransRoutes()
    const {rules} = useInputRules()
    const {has} = usePermissions()

    // CONSTS
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

    // RENDER
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
