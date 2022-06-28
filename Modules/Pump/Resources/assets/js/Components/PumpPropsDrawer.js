import {Col, Drawer, Form, Input, List, Space, Tabs} from "antd";
import {FilePdfOutlined} from "@ant-design/icons";
import Lang from "../../../../../../resources/js/translation/lang";
import React, {useEffect, useState} from "react";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {JustifiedRow} from "../../../../../../resources/js/src/Shared/JustifiedRow";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {AddPumpsToProjectsDrawer} from "./AddPumpToProjectsDrawer";

export const PumpPropsDrawer = ({
                                    visible,
                                    setVisible,
                                    pumpInfo,
                                    needCurve = false
                                }) => {
    const [form] = Form.useForm()

    useEffect(() => {
        if (pumpInfo) {
            form.setFieldsValue({
                ...pumpInfo,
                is_discontinued: pumpInfo?.is_discontinued ? "Нет" : "Да",
            })
            setVisible(true)
        }
    }, [pumpInfo])

    const {reducedAntFormItemClassName} = useStyles()

    const items = [
        {
            values: {
                name: 'article',
                label: "Артикул",
                // initialValue: pumpInfo?.article,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'is_discontinued',
                label: "Действует",
                // initialValue: pumpInfo?.is_discontinued ? "Нет" : "Да" || "Нет",
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'price',
                label: "Цена",
                // initialValue: pumpInfo?.price.toLocaleString(),
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'currency',
                label: "Валюта",
                // initialValue: pumpInfo?.currency,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'price_updated_at',
                label: "Дата актуализации цены",
                // initialValue: pumpInfo?.price_updated_at,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'size',
                label: "Размеры, мм",
                // initialValue: pumpInfo?.size,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'weight',
                label: "Масса, кг",
                // initialValue: pumpInfo?.weight.toLocaleString(),
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'power',
                label: "Мощность, кВт",
                // initialValue: pumpInfo?.power.toLocaleString(),
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'current',
                label: "Ток, А",
                // initialValue: pumpInfo?.current.toLocaleString(),
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'connection_type',
                label: "Тип соединения",
                // initialValue: pumpInfo?.connection_type,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'dn_suction',
                label: Lang.get('pages.pumps.data.dn_suction'),
                // initialValue: pumpInfo?.dn_suction,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'dn_pressure',
                label: Lang.get('pages.pumps.data.dn_pressure'),
                // initialValue: pumpInfo?.dn_pressure,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'collector_switch',
                label: "Переход на коллектор",
                // initialValue: pumpInfo?.collector_switch,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'suction_height',
                label: "Высота всаса, мм",
                // initialValue: pumpInfo?.suction_height,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'ptp_length',
                label: "Монтажная длина",
                // initialValue: pumpInfo?.ptp_length,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
    ]

    return (
        <Drawer
            width={needCurve ? 800 : 650}
            placement="right"
            title={`${pumpInfo?.brand} ${pumpInfo?.series} ${pumpInfo?.name}`}
            visible={visible}
            onClose={() => {
                setVisible(false)
                document.getElementById('pump_curves').innerHTML = ""
            }}
            afterVisibleChange={visible => {
                if (visible && needCurve) {
                    document.getElementById('pump_curves').innerHTML = pumpInfo?.curves
                }
            }}
        >
            <ItemsForm
                form={form}
                layout="horizontal"
                labelSpan={{xs: 6}}
                items={items}
            />
            {needCurve && <div id="pump_curves"/>}
        </Drawer>
    )
}
