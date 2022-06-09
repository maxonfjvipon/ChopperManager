import React from 'react';
import {usePage} from "@inertiajs/inertia-react";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {ExcelFileUploader} from "../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import {Tabs} from "antd";
import {ImportErrorBagDrawer} from "../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";

export default function ControlSystems() {
    // HOOKS
    const {control_systems, filter_data} = usePage().props

    console.log(filter_data)

    // CONSTS
    const columns = [
        [
            {
                title: 'Мощность ПЧ',
                dataIndex: 'power',
                sorter: (a, b) => a.power - b.power,
            },
            {
                title: 'Количество насосов',
                dataIndex: 'pumps_count',
                filters: filter_data.pumps_counts,
                onFilter: (count, record) => record.pumps_count === count
            },
            {
                title: 'Масса',
                dataIndex: 'weight',
                sorter: (a, b) => a.weight - b.weight
            },
            {
                title: "Цена",
                dataIndex: 'price',
                sorter: (a, b) => a.price - b.price,
            },
            {
                title: "Валюта",
                dataIndex: 'currency',
            },
            {
                title: "Дата актуализации цены",
                dataIndex: 'price_updated_at',
            },
            {
                title: 'Описание',
                dataIndex: 'description',
            }
        ],
        [
            {
                title: 'Мощность ПЧ',
                dataIndex: 'power',
                sorter: (a, b) => a.power - b.power,
            },
            {
                title: 'Количество насосов',
                dataIndex: 'pumps_count',
                filters: filter_data.pumps_counts,
                onFilter: (count, record) => record.pumps_count === count
            },
            {
                title: 'Количество задвижек',
                dataIndex: 'gate_valves_count',
                filters: filter_data.gate_valves_counts,
                onFilter: (count, record) => record.gate_valves_count === count
            },
            {
                title: 'АВР',
                dataIndex: 'avr',
                filters: filter_data.yes_no,
                onFilter: (value, record) => record.avr === value,
            },
            {
                title: 'ККВ',
                dataIndex: 'kkv',
                filters: filter_data.yes_no,
                onFilter: (value, record) => record.kkv === value,
            },
            {
                title: 'Уличное исполение',
                dataIndex: 'on_street',
                filters: filter_data.yes_no,
                onFilter: (value, record) => record.on_street === value,
            },
            {
                title: 'Под жокей',
                dataIndex: 'has_jockey',
                filters: filter_data.yes_no,
                onFilter: (value, record) => record.has_jockey === value,
            },
            {
                title: 'Монтаж',
                dataIndex: 'montage_type',
                filters: filter_data.montage_types,
                onFilter: (type, record) => record.montage_type === type,
            },
            {
                title: 'Масса',
                dataIndex: 'weight',
                sorter: (a, b) => a.weight - b.weight
            },
            {
                title: "Цена",
                dataIndex: 'price',
                sorter: (a, b) => a.price - b.price,
            },
            {
                title: "Валюта",
                dataIndex: 'currency',
            },
            {
                title: "Дата актуализации цены",
                dataIndex: 'price_updated_at',
            },
            {
                title: 'Описание',
                dataIndex: 'description',
            }
        ]
    ]

    // RENDER
    return (
        <>
            <ImportErrorBagDrawer title="Ошибки загрузки систем управления"/>
            <IndexContainer
                title={"Системы управления"}
                actions={[<ExcelFileUploader
                    route={route('control_systems.import')}
                    title="Загрузить системы управления"
                />]}
            >
                <Tabs
                    type="card"
                    centered
                    defaultActiveKey={control_systems[0].station_type}
                >
                    {[0, 1].map(index => (
                        <Tabs.TabPane tab={control_systems[index].station_type} key={control_systems[index].station_type}>
                            <Tabs
                                type="card"
                                tabPosition="left"
                                defaultActiveKey={control_systems[index].items[0]?.control_system_type || "default"}
                            >
                                {control_systems[index].items.map(csItem => (
                                    <Tabs.TabPane key={csItem.control_system_type} tab={csItem.control_system_type}>
                                        <TTable
                                            columns={columns[index]}
                                            dataSource={csItem.items}
                                            rowKey={record => record.id}
                                            pagination={{pageSizeOptions: [10, 25, 50, 100]}}
                                        />
                                    </Tabs.TabPane>
                                ))}
                            </Tabs>
                        </Tabs.TabPane>
                    ))}
                < /Tabs>
            </IndexContainer>
        </>
    )
}
