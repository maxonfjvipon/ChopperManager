import React, {useState} from 'react'
import {Checkbox, Drawer, Form, Input, List, message, Tabs} from "antd";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import download from 'downloadjs'
import Lang from "../../../../../../resources/js/translation/lang";

export const ExportAtOnceSelectionDrawer = ({stationToShow, visible, setVisible, pumpableType}) => {
    const [loading, setLoading] = useState(false)

    const tRoute = useTransRoutes()
    const [form] = Form.useForm()

    const setBody = (body) => {
        switch (body.pumpable_type) {
            case "single_pump":
                body = {
                    ...body,
                    reserve_pumps_count: stationToShow?.pumps_count - stationToShow?.main_pumps_count,
                    pumps_count: stationToShow?.pumps_count,
                }
                break
            case "double_pump":
                body = {
                    ...body,
                    dp_work_scheme_id: stationToShow?.dp_work_scheme_id,
                }
                break
        }
        return body
    }

    const exportHandler = async () => {
        setLoading(true)
        let body = {
            ...await form.validateFields(),
            selected_pump_name: stationToShow?.name,
            pump_id: stationToShow?.pump_id,
            flow: stationToShow?.flow,
            head: stationToShow?.head,
            fluid_temperature: stationToShow?.fluid_temperature,
            pumpable_type: pumpableType(),
        }
        body = setBody(body)
        axios.post(tRoute('selections.export.at_once'), body, {
            responseType: 'blob',
        }).then(res => {
            setLoading(false)
            const content = res.headers['content-type'];
            download(res.data, stationToShow.name + ".pdf", content)
        }).catch(reason => {
            setLoading(false)
            message.error("Error downloading. Please contact the administrator", 7)
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
                <PrimaryButton loading={loading}
                               onClick={exportHandler}>{Lang.get('pages.selections.single_pump.export.button')}</PrimaryButton>
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
            <ItemsForm form={form} items={items}/>
        </Drawer>
    )
}
