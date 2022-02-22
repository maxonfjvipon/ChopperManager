import React, {useState} from 'react'
import {Checkbox, Drawer, Form, message} from "antd";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {MultipleSelection} from "../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import download from "downloadjs";
import Lang from "../../../../../../resources/js/translation/lang";

export const ExportProjectDrawer = ({project, visible, setVisible}) => {
    const [loading, setLoading] = useState(false)
    const [form] = Form.useForm()
    const {rules} = useInputRules()
    const tRoute = useTransRoutes()

    const exportHandler = async () => {
        setLoading(true)
        axios.post(tRoute('projects.export', project.id), {
            ...await form.validateFields(),
        }, {
            responseType: 'blob',
        }).then(res => {
            setLoading(false)
            const content = res.headers['content-type'];
            download(res.data, project.name + ".pdf", content) // fixme: name
        }).catch(reason => {
            setLoading(false)
            message.error("Error downloading. Please contact the administrator", 7)
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
                    customValue: selection.selected_pump_name + ((selection.flow && selection.head)
                        ? (" ["
                            + selection.flow
                            + Lang.get('pages.projects.export.m3h') + ", "
                            + selection.head
                            + Lang.get('pages.projects.export.m') + ']')
                        : ''),
                    ...selection,
                }
            })} placeholder={Lang.get('pages.projects.export.choose_selections')}/>
        },
        {
            values: {
                name: 'retail_price',
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.retail_price')}</Checkbox>
        },
        {
            values: {
                name: 'personal_price',
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.personal_price')}</Checkbox>
        },
        // {
        //     values: {
        //         name: 'pump_info',
        //         rules: [rules.required],
        //         valuePropName: "checked",
        //         initialValue: true,
        //     }, input: <Checkbox>{Lang.get('pages.projects.export.print.pump_info')}</Checkbox>
        // },
        {
            values: {
                name: 'print_pump_image',
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: true,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.pump_image')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_sizes_image',
                rules: [rules.required],
                valuePropName: "checked",
                initialValue: true,
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
                valuePropName: "checked",
                initialValue: true,
            }, input: <Checkbox>{Lang.get('pages.projects.export.print.exploded_view')}</Checkbox>
        },
        {
            values: {}, input:
                <PrimaryButton loading={loading}
                               onClick={exportHandler}>{Lang.get('pages.projects.export.button')}</PrimaryButton>
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
