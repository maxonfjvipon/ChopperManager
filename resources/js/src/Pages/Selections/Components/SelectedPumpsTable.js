import React, {useMemo} from 'react'
import {Table} from "antd";
import Lang from '../../../../translation/lang'
import {usePage} from "@inertiajs/inertia-react";

export const SelectedPumpsTable = ({selectedPumps, setStationToShow}) => {
    const {locales} = usePage().props

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
            key: 'articleNum'
        },
        {
            title: Lang.get('pages.selections.single.table.retail_price'),
            dataIndex: 'retailPrice',
            key: 'retailPrice',
            sorter: (a, b) => a.retailPrice - b.retailPrice,
        },
        {
            title: Lang.get('pages.selections.single.table.discounted_price'),
            dataIndex: 'discountedPrice',
            key: 'discountedPrice',
            sorter: (a, b) => a.discountedPrice - b.discountedPrice,
            defaultSortOrder: 'ascend'
        },
        {
            title: Lang.get('pages.selections.single.table.total_retail_price'),
            dataIndex: 'retailPriceSum',
            key: 'retailPriceSum',
            sorter: (a, b) => a.retailPriceSum - b.retailPriceSum
        },
        {
            title: Lang.get('pages.selections.single.table.total_discounted_price'),
            dataIndex: 'discountedPriceSum',
            key: 'discountedPriceSum',
            sorter: (a, b) => a.discountedPriceSum - b.discountedPriceSum
        },
        {
            title: Lang.get('pages.selections.single.table.dn_input'),
            dataIndex: 'dnSuction',
            key: 'dnSuction'
        },
        {
            title: Lang.get('pages.selections.single.table.dn_output'),
            dataIndex: 'dnPressure',
            key: 'dnPressure'
        },
        {
            title: Lang.get('pages.selections.single.table.power'),
            dataIndex: 'rated_power',
            key: 'rated_power',
            sorter: (a, b) => a.rated_power - b.rated_power
        },
        {
            title: Lang.get('pages.selections.single.table.total_power'),
            dataIndex: 'powerSum',
            key: 'powerSum',
            sorter: (a, b) => a.powerSum - b.powerSum
        },
        {
            title: Lang.get('pages.selections.single.table.between_axes_dist'),
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
            scroll={{x: 1500, y: 430}}
        />
    ), [selectedPumps, locales])

}
