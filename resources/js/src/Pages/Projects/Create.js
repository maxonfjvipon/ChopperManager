import React from 'react';
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {useInputRules} from "../../Hooks/input-rules.hook";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {Container} from "../../Shared/ResourcePanel/Resource/Container";
import {ItemsForm} from "../../Shared/ItemsForm";
import {useTransRoutes} from "../../Hooks/routes.hook";

const Create = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {tRoute} = useTransRoutes()

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
        <Container
            title={Lang.get('pages.projects.create.title')}
            buttonLabel={Lang.get('pages.projects.create.button')}
            form={formName}
            backTitle={Lang.get('pages.projects.back')}
            backHref={tRoute('projects.index')}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createProjectHandler}
            />
        </Container>
    )
}

Create.layout = page => <AuthLayout children={page}/>

export default Create
