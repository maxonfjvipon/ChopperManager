import React, {useMemo} from 'react'
import {Table} from "antd";
import Lang from '../../../../translation/lang'

export const SelectedPumpsTable = ({selectedPumps, setStationToShow}) => {


    const selectedPumpsColumns = [
        {title: Lang.get('pages.selections.single.table.name'), dataIndex: 'name', key: 'name', width: '15%'},
        {title: Lang.get('pages.selections.single.table.part_num'), dataIndex: 'partNum', key: 'partNum'},
        {
            title: Lang.get('pages.selections.single.table.retail_price'),
            dataIndex: 'retailPrice',
            key: 'retailPrice',
            sorter: (a, b) => a.retailPrice - b.retailPrice,
        },
        {
            title: Lang.get('pages.selections.single.table.discounted_price'),
            dataIndex: 'personalPrice',
            key: 'personalPrice',
            sorter: (a, b) => a.personalPrice - b.personalPrice,
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
            dataIndex: 'personalPriceSum',
            key: 'personalPriceSum',
            sorter: (a, b) => a.personalPriceSum - b.personalPriceSum
        },
        {title: Lang.get('pages.selections.single.table.dn_input'), dataIndex: 'dnInput', key: 'dnInput'},
        {title: Lang.get('pages.selections.single.table.dn_output'), dataIndex: 'dnOutput', key: 'dnOutput'},
        {
            title: Lang.get('pages.selections.single.table.power'),
            dataIndex: 'power',
            key: 'power',
            sorter: (a, b) => a.power - b.power
        },
        {
            title: Lang.get('pages.selections.single.table.total_power'),
            dataIndex: 'powerSum',
            key: 'powerSum',
            sorter: (a, b) => a.powerSum - b.powerSum
        },
        {
            title: Lang.get('pages.selections.single.table.between_axes_dist'),
            dataIndex: 'betweenAxesDist',
            key: 'betweenAxesDist',
            sorter: (a, b) => a.betweenAxesDist - b.betweenAxesDist
        },
    ]

    return useMemo(() => (
            <Table
                size="small"
                columns={selectedPumpsColumns}
                dataSource={selectedPumps}
                onRow={(record, _) => {
                    return {
                        onClick: _ => {
                            setStationToShow(record);
                        }
                    }
                }}
                pagination={{defaultPageSize: 500, pageSizeOptions: [10, 20, 50, 100, 500, 1000]}}
                scroll={{x: 1500, y: 520}}
            />
        )
        , [selectedPumps])

}
