import React from 'react';
import {usePage} from "@inertiajs/inertia-react";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {ExcelFileUploader} from "../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import {ImportErrorBagDrawer} from "../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";

export default function Chassis() {
    // HOOKS
    const {chassis, filter_data} = usePage().props

    // CONSTS
    const columns = [
        {
            title: 'Артикул',
            dataIndex: 'article',
        },
        {
            title: "Количество насосов",
            dataIndex: 'pumps_count',
            filters: filter_data.pumps_count,
            onFilter: (count, record) => record.pumps_count === count
        },
        {
            title: 'Допустимая масса насосов, кг',
            dataIndex: 'pumps_weight',
            sorter: (a, b) => a.pumps_weight - b.pumps_weight
        },
        {
            title: "Масса, кг",
            dataIndex: 'weight',
            sorter: (a, b) => a.weight - b.weight,
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
            <ImportErrorBagDrawer title="Ошибки загрузки рам"/>
            <IndexContainer
                title={"Рамы"}
                actions={[<ExcelFileUploader
                    route={route('chassis.import')}
                    title="Загрузить рамы"
                />]}
            >
                <TTable
                    columns={columns}
                    dataSource={chassis}
                    rowKey={record => record.id}
                    pagination={{pageSizeOptions: [10, 25, 50, 100]}}
                />
            </IndexContainer>
        </>
    )
}
