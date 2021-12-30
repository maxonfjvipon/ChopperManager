import React, {useEffect} from 'react'
import {Drawer, Form, Input} from "antd";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import Lang from "../../../../../../resources/js/translation/lang";
import {Inertia} from "@inertiajs/inertia";

export const CloneProjectDrawer = ({project, visible, setVisible}) => {
    const [form] = Form.useForm()
    const {rules} = useInputRules()
    const tRoute = useTransRoutes()

    const cloneHandler = async () => {
        Inertia.post(tRoute('projects.clone', project.id), await form.validateFields())
        setVisible(false)
    }

    useEffect(() => {
        form.resetFields()
    }, [project])

    const items = [
        {
            values: {
                label: Lang.get('pages.projects.index.clone.save_as'),
                name: 'name',
                rules: [rules.required],
                initialValue: project?.name,
            }, input: <Input
                onPressEnter={cloneHandler}
                placeholder={Lang.get('pages.projects.index.clone.save_as')}
            />
        },
        {
            values: {}, input:
                <PrimaryButton onClick={cloneHandler}>
                    {Lang.get('pages.projects.index.clone.button')}
                </PrimaryButton>
        }
    ]

    return (
        <Drawer
            width={500}
            placement="right"
            title={Lang.get('pages.projects.index.clone.title')}
            visible={visible}
            closable={false}
            onClose={() => {
                form.resetFields()
                setVisible(false)
            }}
        >
            <ItemsForm layout="vertical" form={form} items={items}/>
        </Drawer>
    )
}
