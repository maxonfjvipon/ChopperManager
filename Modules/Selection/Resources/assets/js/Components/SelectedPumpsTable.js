import React, {useMemo, useState} from 'react'
import {Button,  Tooltip} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {PlusOutlined} from "@ant-design/icons";

export const SelectedPumpsTable = ({selectedPumps, setStationToShow, loading, addStationHandler, dependencies = []}) => {
    const {locales} = usePage().props

    const columns = [
        {
            title: "Наименование",
            dataIndex: 'name',
        },
        {
            title: "Артикул насоса",
            dataIndex: 'pump_article',
        },
        {
            title: "Артикул системы управления",
            dataIndex: 'control_system_article',
        },
        {
            title: "Себестоимость, ₽",
            dataIndex: 'cost_price',
            render: (_, record) =>
                <Tooltip overlayStyle={{ whiteSpace: 'pre-line' }} placement="topRight" title={record.cost_structure}>
                    {record.cost_price.toLocaleString()}
                </Tooltip>,
            sorter: (a, b) => a.cost_price - b.cost_price,
            defaultSortOrder: 'ascend'
        },
        {
            title: "Р, кВт",
            dataIndex: 'power',
            render: (_, record) => record.power.toLocaleString(),
            sorter: (a, b) => a.power - b.power,
        },
        {
            title: "Р итого, кВт",
            dataIndex: 'total_power',
            render: (_, record) => record.total_power.toLocaleString(),
            sorter: (a, b) => a.total_power - b.total_power
        },
        {
            key: 'action', width: '1%', render: (_, record) => {
                return !record.bad && (
                    <TableActionsContainer>
                        <Tooltip placement="topRight" title="Добавить">
                            <Button
                                onClick={addStationHandler(record)}
                                icon={<PlusOutlined />}
                            />
                        </Tooltip>
                    </TableActionsContainer>
                )
            }
        }
    ]

    return useMemo(() => (
        <TTable
            columns={columns}
            dataSource={selectedPumps}
            loading={loading}
            clickHandler={setStationToShow}
            clickRecord
            pagination={{defaultPageSize: 20, pageSizeOptions: [10, 20, 50, 100, 500, 1000]}}
            // scroll={{y: "40vh"}}
        />
    ), [selectedPumps, locales, loading, ...dependencies])

}
