import React, {useState} from "react";
import Lang from "../../../../../../resources/js/translation/lang";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {Drawer, Form, InputNumber} from "antd";
import {MultipleSelection} from "../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePage} from "@inertiajs/inertia-react";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {useHttp} from "../../../../../../resources/js/src/Hooks/http.hook";

export const AddPumpsToProjectsDrawer = ({pumpInfo, visible, setVisible}) => {
    const {rules} = useInputRules()
    const {projects} = usePage().props
    const {fullWidth} = useStyles()
    const tRoute = useTransRoutes()

    const [form] = Form.useForm()
    const {postRequest} = useHttp()

    const clearModal = () => {
        form.resetFields()
        setVisible(false)
    }

    const modalItems = [
        {
            values: {
                rules: [rules.required],
                label: Lang.get('pages.pumps.add_to_projects.choose'),
                name: 'project_ids',
            }, input: <MultipleSelection
                options={projects}
                placeholder={Lang.get('pages.pumps.add_to_projects.choose')}
            />
        }, pumpInfo?.pumpable_type !== 'double_pump' && {
            values: {
                rules: [rules.required],
                label: Lang.get('pages.pumps.add_to_projects.pumps_count'),
                name: 'pumps_count',
            }, input: <InputNumber style={fullWidth} max={5} min={1}/>
        }
    ].filter(Boolean)

    return (
        <Drawer
            title={Lang.get('pages.pumps.add_to_projects.title')}
            visible={visible}
            onClose={clearModal}
            width={350}
            closable={false}
        >
            <ItemsForm
                form={form}
                layout='vertical'
                items={modalItems}
            />
            <PrimaryButton onClick={async () => {
                const body = await form.validateFields()
                postRequest(tRoute('pumps.add_to_projects', pumpInfo.id), body)
                    .then(res => {
                        clearModal()
                    }).catch(reason => {
                })
            }}>
                {Lang.get('pages.pumps.add_to_projects.ok')}
            </PrimaryButton>
        </Drawer>
    )
}
