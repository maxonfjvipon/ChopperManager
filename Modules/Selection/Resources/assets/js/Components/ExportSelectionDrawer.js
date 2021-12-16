import React, {useState} from 'react'
import {Checkbox, Drawer, Form, Input, List, Tabs} from "antd";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import download from "downloadjs";
import Lang from "../../../../../../resources/js/translation/lang";

export const ExportSelectionDrawer = ({selection_id, visible, setVisible}) => {
    const [loading, setLoading] = useState(false)

    const tRoute = useTransRoutes()
    const [form] = Form.useForm()

    const exportHandler = async () => {
        setLoading(true)
        axios.post(tRoute('selections.export', selection_id), {
            ...await form.validateFields(),
        }, {
            responseType: 'blob',
        }).then(res => {
            setLoading(false)
            const content = res.headers['content-type'];
            download(res.data, "download.pdf", content) // fixme: name
        })
    }

    const items = [
        {
            values: {
                name: 'print_pump_image',
                // label: 'Print pump image',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.selections.single_pump.export.print.pump_image')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_sizes_image',
                // label: 'Print pump sizes image',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.selections.single_pump.export.print.sizes_image')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_electric_diagram_image',
                // label: 'Print pump electric diagram',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.selections.single_pump.export.print.electric_diagram')}</Checkbox>
        },
        {
            values: {
                name: 'print_pump_cross_sectional_drawing_image',
                // label: 'Print pump cross sectional drawing',
                valuePropName: "checked",
                initialValue: true,
                // className: reducedAntFormItemClassName,
            }, input: <Checkbox>{Lang.get('pages.selections.single_pump.export.print.exploded_view')}</Checkbox>
        },
        {
            values: {}, input:
                <PrimaryButton loading={loading} onClick={exportHandler}>{Lang.get('pages.selections.single_pump.export.button')}</PrimaryButton>
        }
    ]

    return (
        <Drawer
            width={300}
            placement="right"
            title={Lang.get('pages.selections.single_pump.export.title')}
            visible={visible}
            closable={false}
            onClose={() => {
                setVisible(false)
                form.resetFields()
            }}
        >
            <ItemsForm form={form} onFinish={exportHandler} items={items}/>
        </Drawer>
    )
}
