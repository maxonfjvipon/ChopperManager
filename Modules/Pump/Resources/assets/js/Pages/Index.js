import React, {useEffect, useState} from "react";
import {ImportErrorBagDrawer} from "../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {ExcelFileUploader} from "../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import Lang from '../../../../../../resources/js/translation/lang'
import {Tag} from "antd";
import {SearchInput} from "../../../../../../resources/js/src/Shared/SearchInput";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {usePage} from "@inertiajs/inertia-react";
import {useHttp} from "../../../../../../resources/js/src/Hooks/http.hook";
import {PumpPropsDrawer} from "../Components/PumpPropsDrawer";

export default function Index() {

    // HOOKS
    const {filter_data} = usePage().props
    const {postRequest, loading} = useHttp()

    // STATE
    const [pumpInfoVisible, setPumpInfoVisible] = useState(false)
    const [pumpInfo, setPumpInfo] = useState(null)
    const [pumps, setPumps] = useState([])
    const [filterData, setFilterData] = useState(filter_data)
    const [pagination, setPagination] = useState({
        total: 0,
        pageSizeOptions: [10, 20, 50],
    })

    // CONSTS
    const searchFieldId = "search-pumps"
    const columns = [
        {
            title: "Артикул",
            dataIndex: 'article',
            width: 120
        },
        {
            title: "Действует",
            dataIndex: 'is_discontinued',
            width: 70,
            render: (_, record) => {
                return record.is_discontinued
                    ? <Tag color="orange">{Lang.get('tooltips.popconfirm.no')}</Tag>
                    : <Tag color="green">{Lang.get('tooltips.popconfirm.yes')}</Tag>
            }
        },
        {
            title: "Бренд",
            dataIndex: 'brand',
            width: 100,
            filters: filterData.brands,
        },
        {
            title: "Серия",
            dataIndex: 'series',
            width: 100,
            filters: filterData.series,
        },
        {
            title: "Наименование",
            dataIndex: 'name',
            width: 150
        },
        {
            title: "Цена",
            dataIndex: 'price',
            width: 70,
            sorter: true,
            render: (_, record) => record.price.toLocaleString(),
        },
        {
            title: "Валюта",
            dataIndex: 'currency',
            key: 'currency',
            width: 70
        },
        {
            title: "Дата актуализации цены",
            dataIndex: 'price_updated_at',
            key: 'price_updated_at',
            width: 120
        }, {
            title: "Размеры, мм",
            dataIndex: 'size',
            key: 'sizes',
            width: 100
        },
        {
            title: "Масса, кг",
            dataIndex: 'weight',
            render: (_, record) => record.weight.toLocaleString(),
            key: 'weight',
            width: 90,
            sorter: true,
        },
        {
            title: "Мощность, кВт",
            dataIndex: 'power',
            render: (_, record) => record.power.toLocaleString(),
            width: 95,
            sorter: true,
        },
        {
            title: "Ток, А",
            dataIndex: 'current',
            render: (_, record) => record.current.toLocaleString(),
            width: 100,
            sorter: true,
        },
        {
            title: "Тип соединения",
            dataIndex: 'connection_type',
            width: 120,
            filters: filterData.connection_types,
        },
        {
            title: "Ориентация",
            dataIndex: 'orientation',
            width: 120,
            filters: filterData.pump_orientations,
        },
        {
            title: "ДУ вход",
            dataIndex: 'dn_suction',
            width: 110,
            filters: filterData.dns,
            sorter: true,
        },
        {
            title: "ДУ выход",
            dataIndex: 'dn_pressure',
            width: 110,
            filters: filterData.dns,
            sorter: true,
        },
        {
            title: "Переход на коллектор",
            dataIndex: 'collector_switch',
            width: 140,
            filters: filterData.collector_switches,
        },
        {
            title: "Высота всаса, мм",
            dataIndex: 'suction_height',
            width: 110,
            sorter: true,
        },
        {
            title: "Монтажная длина",
            dataIndex: 'ptp_length',
            width: 150,
            sorter: true,
        },
        {
            key: 'actions', width: "1%", render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showPumpClickHandler(record)}/>
                    </TableActionsContainer>
                )
            }
        }
    ]

    const loadPumps = (params) => {
        postRequest(route('pumps.load'), {
            sortField: params?.sorter?.field,
            sortOrder: params?.sorter?.order,
            pagination: params?.pagination,
            search: params?.search,
            ...params?.filters,
        }).then(res => {
            setPumps(res.pumps.items)
            setFilterData({
                ...filter_data,
                ...res.filter_data,
            })
            setPagination({
                ...pagination,
                total: res.pumps.total
            })
        })
    }

    // HANDLERS
    const showPumpClickHandler = pump => () => {
        postRequest(route('pumps.show', pump.id), {
            need_curves: true,
        }).then(data => {
            setPumpInfo({
                // ...pumps.find(_pump => pump.id === _pump.id),
                // is_discontinued: pump.is_discontinued ? "Нет" : "Да",
                ...pump,
                ...data.pump,
            })
        })
    }

    const searchClickHandler = () => loadPumps({
        search: document.getElementById(searchFieldId).value.toLowerCase()
    })

    // EFFECTS
    useEffect(() => loadPumps(), [])

    // RENDER
    return (
        <>
            <ImportErrorBagDrawer title={Lang.get('pages.pumps.errors_title')}/>
            <IndexContainer
                title={Lang.get('pages.pumps.title')}
                actions={[<ExcelFileUploader
                    route={route('pumps.import')}
                    title="Загрузить насосы"
                />]}
            >
                <SearchInput
                    id={searchFieldId}
                    placeholder={"Поиск насосов по артикулу и наименованию"}
                    disabled={loading}
                    searchClickHandler={searchClickHandler}
                />
                <TTable
                    onChange={(pagination, filters, sorter, extra) => {
                        loadPumps({pagination, filters, sorter})
                    }}
                    rowKey={record => record.id}
                    columns={columns}
                    pagination={pagination}
                    dataSource={pumps}
                    clickRecord
                    doubleClickHandler={showPumpClickHandler}
                    scroll={{x: 4000, y: "70vh"}}
                    loading={loading}
                />
            </IndexContainer>
            <PumpPropsDrawer
                title
                needCurve
                pumpInfo={pumpInfo}
                visible={pumpInfoVisible}
                setVisible={setPumpInfoVisible}
            />
        </>
    )
}
