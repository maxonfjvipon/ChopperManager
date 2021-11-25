import React from 'react'
import {Checkbox, Drawer, Form} from "antd";
import {ItemsForm} from "../../../Shared/ItemsForm";
import {useTransRoutes} from "../../../Hooks/routes.hook";
import {MultipleSelection} from "../../../Shared/Inputs/MultipleSelection";
import {PrimaryButton} from "../../../Shared/Buttons/PrimaryButton";
import {useInputRules} from "../../../Hooks/input-rules.hook";
import download from "downloadjs";
import Lang from "../../../../translation/lang";

export const ExportProjectDrawer = ({project, visible, setVisible}) => {
    const {tRoute} = useTransRoutes()
    const [form] = Form.useForm()
    const {rules} = useInputRules()

    const exportHandler = () => {
        axios.post(tRoute('projects.export', project.id), {
            ...form.getFieldsValue(true),
        }, {
            responseType: 'blob',
        }).then(res => {
            const content = res.headers['content-type'];
            download(res.data, "download.pdf", content) // fixme: name
        })
    }

    const items = [
        {
            values: {
                rules: [rules.required],
                label: Lang.get('pages.projects.export.choose_selections'),
                name: 'selection_ids',
            }, input: <MultipleSelection options={project?.selections.map(selection => {
                return {
                    customValue: selection.selected_pump_name + " ["
                        + selection.flow
                        + Lang.get('pages.projects.export.m3h') + ", "
                        + selection.head
                        + Lang.get('pages.projects.export.m') + ']',
                    ...selection,
                }
            })} placeholder={Lang.get('pages.projects.export.choose_selections')}/>
        },
        {
            values: {
                name: 'retail_price',
                rules: [rules.required],
                // label: 'Print pump image',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.retail_price')}</Checkbox>
        },
        {
            values: {
                name: 'personal price',
                rules: [rules.required],
                // label: 'Print pump image',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.personal_price')}</Checkbox>
        },
        {
            values: {
                name: 'pump_info',
                rules: [rules.required],
                // label: 'Print pump image',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.pump_info')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_image',
                rules: [rules.required],
                // label: 'Print pump image',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.pump_image')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_sizes_image',
                rules: [rules.required],
                // label: 'Print pump sizes image',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.sizes_image')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_electric_diagram_image',
                rules: [rules.required],
                // label: 'Print pump electric diagram',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.electric_diagram')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_cross_sectional_drawing_image',
                rules: [rules.required],
                // label: 'Print pump cross sectional drawing',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.exploded_view')}</Checkbox>
        },
        {
            values: {}, input:
                <PrimaryButton onClick={exportHandler}>{Lang.get('pages.projects.export.button')}</PrimaryButton>
        }
    ]

    return (
        <Drawer
            width={500}
            placement="right"
            title={Lang.get('pages.projects.export.title')}
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