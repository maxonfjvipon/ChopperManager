import React, {useMemo} from 'react'
import {Table} from "antd";
import Lang from '../../../../../../resources/js/translation/lang'
import {usePage} from "@inertiajs/inertia-react";

export const SelectedPumpsTable = ({selectedPumps, setStationToShow}) => {
    const {locales, auth} = usePage().props

    const columns = [
        {
            title: Lang.get('pages.selections.single.table.name'),
            dataIndex: 'name',
            key: 'name',
            width: '15%'
        },
        {
            title: Lang.get('pages.selections.single.table.part_num'),
            dataIndex: 'articleNum',
            key: 'articleNum',
            width: 110,
        },
        {
            title: Lang.get('pages.selections.single.table.retail_price') + ", " + auth.currency,
            dataIndex: 'retailPrice',
            key: 'retailPrice',
            render: (_, record) => record.retailPrice.toLocaleString(),
            sorter: (a, b) => a.retailPrice - b.retailPrice,
            width: 135,
        },
        {
            title: Lang.get('pages.selections.single.table.discounted_price') + ", " + auth.currency,
            dataIndex: 'discountedPrice',
            key: 'discountedPrice',
            render: (_, record) => record.discountedPrice.toLocaleString(),
            sorter: (a, b) => a.discountedPrice - b.discountedPrice,
            width: 140,
        },
        {
            title: Lang.get('pages.selections.single.table.total_retail_price') + ", " + auth.currency,
            dataIndex: 'retailPriceSum',
            key: 'retailPriceSum',
            render: (_, record) => record.retailPriceSum.toLocaleString(),
            sorter: (a, b) => a.retailPriceSum - b.retailPriceSum,
            width: 140,
        },
        {
            title: Lang.get('pages.selections.single.table.total_discounted_price') + ", " + auth.currency,
            dataIndex: 'discountedPriceSum',
            key: 'discountedPriceSum',
            render: (_, record) => record.discountedPriceSum.toLocaleString(),
            sorter: (a, b) => a.discountedPriceSum - b.discountedPriceSum,
            defaultSortOrder: 'ascend',
            width: 170,
        },
        {
            title: Lang.get('pages.selections.single.table.dn_input'),
            dataIndex: 'dnSuction',
            key: 'dnSuction',
            width: 120,
        },
        {
            title: Lang.get('pages.selections.single.table.dn_output'),
            dataIndex: 'dnPressure',
            key: 'dnPressure',
            width: 120,
        },
        {
            title: Lang.get('pages.selections.single.table.power'),
            dataIndex: 'rated_power',
            key: 'rated_power',
            sorter: (a, b) => a.rated_power - b.rated_power,
            width: 80,
        },
        {
            title: Lang.get('pages.selections.single.table.total_power'),
            dataIndex: 'powerSum',
            key: 'powerSum',
            sorter: (a, b) => a.powerSum - b.powerSum
        },
        {
            title: Lang.get('pages.selections.single.table.ptp_length'),
            dataIndex: 'ptpLength',
            key: 'ptpLength',
            sorter: (a, b) => a.ptpLength - b.ptpLength
        },
    ]

    return useMemo(() => (
        <Table
            size="small"
            columns={columns}
            dataSource={selectedPumps}
            onRow={(record, _) => {
                return {
                    onClick: _ => {
                        setStationToShow(record);
                    }
                }
            }}
            pagination={{defaultPageSize: 500, pageSizeOptions: [10, 20, 50, 100, 500, 1000]}}
            scroll={{x: 1550, y: 300}}
        />
    ), [selectedPumps, locales])

}
