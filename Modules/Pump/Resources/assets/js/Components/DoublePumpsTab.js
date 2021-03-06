import Lang from "../../../../../../resources/js/translation/lang";
import React, {useState} from "react";
import {usePage} from "@inertiajs/inertia-react";
import {PumpsTab} from "./PumpsTab";
import {Tag} from "antd";

export const DoublePumpsTab = ({setPumpInfo}) => {
    const {filter_data} = usePage().props
    const [brandsToShow, setBrandsToShow] = useState([])
    const [seriesToShow, setSeriesToShow] = useState([])
    const [pumps, setPumps] = useState([])

    const columns = [
        {
            title: Lang.get('pages.pumps.data.article_num_main'),
            dataIndex: 'article_num_main',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.article_num_archive'),
            dataIndex: 'article_num_archive',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.is_discontinued'),
            dataIndex: 'is_discontinued',
            width: 70,
            render: (_, record) => {
                return record.is_discontinued
                    ? <Tag color="orange">{Lang.get('tooltips.popconfirm.no')}</Tag>
                    : <Tag color="green">{Lang.get('tooltips.popconfirm.yes')}</Tag>
            }
        },
        {
            title: Lang.get('pages.pumps.data.brand'),
            dataIndex: 'brand',
            width: 100,
            filters: brandsToShow,
            onFilter: (brand, record) => record.brand === brand
        },
        {
            title: Lang.get('pages.pumps.data.series'),
            dataIndex: 'series',
            width: 100,
            filters: seriesToShow,
            onFilter: (series, record) => record.series === series
        },
        {
            title: Lang.get('pages.pumps.data.name'),
            dataIndex: 'name',
            width: 150
        },
        {
            title: Lang.get('pages.pumps.data.price'),
            dataIndex: 'price',
            width: 70,
            sorter: {compare: (a, b) => a.price - b.price}
        },
        {
            title: Lang.get('pages.pumps.data.currency'),
            dataIndex: 'currency',
            key: 'currency',
            width: 70
        },
        {
            title: Lang.get('pages.pumps.data.weight'),
            dataIndex: 'weight',
            key: 'weight',
            width: 90,
            sorter: {compare: (a, b) => a.weight - b.weight}
        },
        {
            title: Lang.get('pages.pumps.data.rated_power'),
            dataIndex: 'rated_power',
            width: 95,
            sorter: {compare: (a, b) => a.rated_power - b.rated_power}
        },
        {
            title: Lang.get('pages.pumps.data.rated_current'),
            dataIndex: 'rated_current',
            width: 100,
            sorter: {compare: (a, b) => a.rated_current - b.rated_current}
        },
        {
            title: Lang.get('pages.pumps.data.connection_type'),
            dataIndex: 'connection_type',
            width: 120,
            filters: filter_data.connection_types,
            onFilter: (connection_type, record) => record.connection_type === connection_type
        },
        {
            title: Lang.get('pages.pumps.data.fluid_temp_min'),
            dataIndex: 'fluid_temp_min',
            width: 110,
            sorter: {
                compare: (a, b) => a.fluid_temp_min - b.fluid_temp_min
            },
        },
        {
            title: Lang.get('pages.pumps.data.fluid_temp_max'),
            dataIndex: 'fluid_temp_max',
            width: 110,
            sorter: {
                compare: (a, b) => a.fluid_temp_max - b.fluid_temp_max
            },
        },
        {
            title: Lang.get('pages.pumps.data.ptp_length'),
            dataIndex: 'ptp_length',
            width: 150,
            sorter: {
                compare: (a, b) => a.ptp_length - b.ptp_length
            },

        },
        {
            title: Lang.get('pages.pumps.data.dn_suction'),
            dataIndex: 'dn_suction',
            width: 110,
            sorter: {
                compare: (a, b) => a.dn_suction - b.dn_suction
            },
            filters: filter_data.dns,
            onFilter: (dn_suction, record) => record.dn_suction === dn_suction
        },
        {
            title: Lang.get('pages.pumps.data.dn_pressure'),
            dataIndex: 'dn_pressure',
            width: 110,
            sorter: {
                compare: (a, b) => a.dn_pressure - b.dn_pressure
            },
            filters: filter_data.dns,
            onFilter: (dn_pressure, record) => record.dn_pressure === dn_pressure
        },
        {
            title: Lang.get('pages.pumps.data.power_adjustment'),
            dataIndex: 'power_adjustment',
            width: 140,
            filters: filter_data.power_adjustments,
            onFilter: (power_adjustment, record) => record.power_adjustment === power_adjustment
        },
        {
            title: Lang.get('pages.pumps.data.connection'),
            dataIndex: 'mains_connection',
            width: 90,
            filters: filter_data.mains_connections,
            onFilter: (mains_connection, record) => record.mains_connection === mains_connection
        },
        {
            title: Lang.get('pages.pumps.data.types'),
            dataIndex: 'types',
            width: 360,
            filters: filter_data.types,
            onFilter: (type, record) => record.types.split(', ').includes(type)
        },
        {
            title: Lang.get('pages.pumps.data.applications'),
            dataIndex: 'applications',
            width: 450,
            filters: filter_data.applications,
            onFilter: (application, record) => record.applications.split(', ').includes(application)
        },
    ]

    return (
        <PumpsTab
            setPumpInfo={setPumpInfo}
            columns={columns}
            pumpable_type='double_pump'
            setSeriesToShow={setSeriesToShow}
            setBrandsToShow={setBrandsToShow}
            pumps={pumps}
            setPumps={setPumps}
            filter_data={filter_data}
            onChange={(pagination, filters, sorter, extra) => {
                if (extra.action === "filter") { // TODO: make better
                    setSeriesToShow(filter_data.series
                        .filter(series => pumps.findIndex(pump => (filters.brand != null ? filters.brand.includes(pump.brand) : true)
                            && pump.series === series.value) !== -1)
                    )
                }
            }}
        />
    )
}
