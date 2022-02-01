import React, {useEffect, useMemo} from 'react'
import {Spin, Table} from "antd";
import Lang from '../../../../../../resources/js/translation/lang'
import {usePage} from "@inertiajs/inertia-react";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";

export const SelectedPumpsTable = ({selectedPumps, setStationToShow, loading}) => {
    const {locales, auth} = usePage().props

    const columns = [
        {
            title: Lang.get('pages.selections.single_pump.table.name'),
            dataIndex: 'name',
            key: 'name',
            width: '15%'
        },
        {
            title: Lang.get('pages.selections.single_pump.table.part_num'),
            dataIndex: 'article_num',
            key: 'article_num',
            width: 110,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.retail_price') + ", " + auth.currency,
            dataIndex: 'retail_price',
            key: 'retail_price',
            render: (_, record) => record.retail_price.toLocaleString(),
            sorter: (a, b) => a.retail_price - b.retail_price,
            width: 135,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.discounted_price') + ", " + auth.currency,
            dataIndex: 'discounted_price',
            key: 'discounted_price',
            render: (_, record) => record.discounted_price.toLocaleString(),
            sorter: (a, b) => a.discounted_price - b.discounted_price,
            width: 140,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.total_retail_price') + ", " + auth.currency,
            dataIndex: 'retail_price_total',
            key: 'retail_price_total',
            render: (_, record) => record.retail_price_total.toLocaleString(),
            sorter: (a, b) => a.retail_price_total - b.retail_price_total,
            width: 140,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.total_discounted_price') + ", " + auth.currency,
            dataIndex: 'discounted_price_total',
            key: 'discounted_price_total',
            render: (_, record) => record.discounted_price_total.toLocaleString(),
            sorter: (a, b) => a.discounted_price_total - b.discounted_price_total,
            defaultSortOrder: 'ascend',
            width: 170,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.dn_input'),
            dataIndex: 'dn_suction',
            key: 'dn_suction',
            width: 120,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.dn_output'),
            dataIndex: 'dn_pressure',
            key: 'dn_pressure',
            width: 120,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.power'),
            dataIndex: 'rated_power',
            key: 'rated_power',
            sorter: (a, b) => a.rated_power - b.rated_power,
            width: 80,
        },
        {
            title: Lang.get('pages.selections.single_pump.table.total_power'),
            dataIndex: 'power_total',
            key: 'power_total',
            sorter: (a, b) => a.power_total - b.power_total
        },
        {
            title: Lang.get('pages.selections.single_pump.table.ptp_length'),
            dataIndex: 'ptp_length',
            key: 'ptp_length',
            sorter: (a, b) => a.ptp_length - b.ptp_length
        },
    ]

    return useMemo(() => (
        <TTable
            columns={columns}
            dataSource={selectedPumps}
            loading={loading}
            clickHandler={setStationToShow}
            clickRecord
            pagination={{defaultPageSize: 500, pageSizeOptions: [10, 20, 50, 100, 500, 1000]}}
            scroll={{x: 1550, y: "48vh"}}
        />
    ), [selectedPumps, locales, loading])

}
