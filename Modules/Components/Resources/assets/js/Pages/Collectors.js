import React from 'react';
import {usePage} from "@inertiajs/inertia-react";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {ExcelFileUploader} from "../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import {Tabs} from "antd";
import {ImportErrorBagDrawer} from "../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";

export default function Collectors() {
    // HOOKS
    const {collectors, filter_data} = usePage().props

    console.log(collectors)

    // CONSTS
    const columns = [
        {
            title: 'ДУ общий',
            dataIndex: 'dn_common',
            filters: filter_data.dns,
            onFilter: (dn, record) => record.dn_common === dn,
        },
        {
            title: 'ДУ патрубков',
            dataIndex: 'dn_pipes',
            filters: filter_data.dns,
            onFilter: (dn, record) => record.dn_pipes === dn,
        },
        {
            title: 'Количество патрубков',
            dataIndex: 'pipes_count',
            filters: filter_data.pipes_count,
            onFilter: (count, record) => record.pipes_count === count
        },
        {
            title: "Длина",
            dataIndex: 'length',
            sorter: (a, b) => a.length - b.length,
        },
        {
            title: "Длина патрубков",
            dataIndex: 'pipes_length',
            sorter: (a, b) => a.pipes_length - b.pipes_length,
        },
        {
            title: "Масса",
            dataIndex: 'weight',
            sorter: (a, b) => a.weight - b.weight,
        },
        {
            title: 'Тип соединения',
            dataIndex: 'connection_type',
            filters: filter_data.connection_types,
            onFilter: (type, record) => record.connection_type === type
        },
        {
            title: 'Материал',
            dataIndex: 'material',
            filters: filter_data.materials,
            onFilter: (material, record) => record.material === material
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
        }
    ]

    // RENDER
    return (
        <>
            <ImportErrorBagDrawer title="Ошибки загрузки коллекторов"/>
            <IndexContainer
                title={"Коллекторы"}
                actions={[<ExcelFileUploader
                    route={route('collectors.import')}
                    title="Загрузить коллекторы"
                />]}
            >
                <Tabs
                    type="card"
                    centered
                    defaultActiveKey={collectors[0].collector_type}
                >
                    {collectors.map(collector => (
                        <Tabs.TabPane tab={collector.collector_type} key={collector.collector_type}>
                            <TTable
                                columns={columns}
                                dataSource={collector.items}
                                rowKey={record => record.id}
                                pagination={{pageSizeOptions: [10, 25, 50, 100]}}
                            />
                        </Tabs.TabPane>
                    ))}
                </Tabs>
            </IndexContainer>
        </>
    )
}
