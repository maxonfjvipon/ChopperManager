import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../Hooks/input-rules.hook";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {ItemsForm} from "../../Shared/ItemsForm";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {usePage} from "@inertiajs/inertia-react";
import {Container} from "../../Shared/ResourcePanel/Edit/Container";

const Edit = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()
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
        <Container
            title={Lang.get('pages.projects.edit.title')}
            updateButtonLabel={Lang.get('pages.projects.edit.button')}
            form={formName}
            backTitle={Lang.get('pages.projects.back')}
            backHref={tRoute('projects.index')}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={updateProjectHandler}
            />
        </Container>
    )
}

Edit.layout = page => <AuthLayout children={page}/>

export default Edit
