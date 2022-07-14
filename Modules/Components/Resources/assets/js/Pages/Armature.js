import React from 'react';
import {usePage} from "@inertiajs/inertia-react";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {ExcelFileUploader} from "../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import {useDate} from "../../../../../../resources/js/src/Hooks/date.hook";

export default function Armature() {
    // HOOKS
    const {armature, filter_data} = usePage().props
    const {compareDate} = useDate()

    // CONSTS
    const columns = [
        {
            title: 'Тип арматуры',
            dataIndex: 'type',
            filters: filter_data.armature_types,
            onFilter: (type, record) => record.type === type
        },
        {
            title: 'Тип соединения',
            dataIndex: 'connection_type',
            filters: filter_data.connection_types,
            onFilter: (type, record) => record.connection_type === type
        },
        {
            title: 'ДУ',
            dataIndex: 'dn',
            filters: filter_data.dns,
            onFilter: (dn, record) => record.dn === dn,
        },
        {
            title: "Длина",
            dataIndex: 'length',
            sorter: (a, b) => a.length - b.length,
        },
        {
            title: "Масса",
            dataIndex: 'weight',
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
            sorter: (a, b) => compareDate(a.price_updated_at, b.price_updated_at),
        }
    ]

    // RENDER
    return (
        <IndexContainer
            title={"Арматура"}
            actions={[<ExcelFileUploader
                route={route('armature.import')}
                title="Загрузить арматуру"
            />]}
        >
            <TTable
                columns={columns}
                dataSource={armature}
                rowKey={record => record.id}
                pagination={{pageSizeOptions: [10, 25, 50, 100]}}
            />
        </IndexContainer>
    )
}
