import React from 'react'
import {usePage} from "@inertiajs/inertia-react";
import {Input, Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {useDate} from "../../../../../../../resources/js/src/Hooks/date.hook";
import {useStyles} from "../../../../../../../resources/js/src/Hooks/styles.hook";
import {useLabels} from "../../../../../../../resources/js/src/Hooks/labels.hook";
import {BackToDealersLink} from "../../Components/BackToDealersLink";
import {BackLink} from "../../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";

export default function Show() {
    // HOOKS
    const {contractor, auth, filter_data} = usePage().props
    const {filteredBoolArray} = usePermissions()
    const {compareDate} = useDate()
    const {reducedBottomAntFormItemClassName} = useStyles()
    const {labels} = useLabels()

    // CONSTS
    const formName = 'contractor-form'
    const items = [
        {
            values: {
                name: 'name',
                label: labels.name,
                initialValue: contractor.name,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        }, {
            values: {
                name: 'area',
                label: labels.area,
                initialValue: contractor.area,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        },
        {
            values: {
                name: 'itn',
                label: labels.itn,
                initialValue: contractor.itn,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        },
    ]

    const columns = filteredBoolArray([
        {
            title: "Дата создания",
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
        }, {
            title: "Дата обновления",
            dataIndex: 'updated_at',
            sorter: (a, b) => compareDate(a.updated_at, b.updated_at),
            defaultSortOrder: 'ascend',
        }, {
            title: "Наименование",
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        }, {
            title: 'Область',
            dataIndex: 'area',
            width: 180, render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            filters: filter_data.areas,
            onFilter: (area, record) => record.area === area
        }, {
            title: 'Статус',
            dataIndex: 'status',
            width: 120,
            filters: filter_data.statuses,
            onFilter: (status, record) => record.status === status
        }, {
            title: 'Заказчик',
            dataIndex: 'customer',
        }, {
            title: 'Монтажник',
            dataIndex: 'installer',
        }, {
            title: 'Проектировщик',
            dataIndex: 'designer',
        }, auth.is_admin && {
            title: 'Пользователь',
            dataIndex: 'user',
        }, {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showProjectHandler(record.id)}/>
                    </TableActionsContainer>
                )
            }
        },
    ])

    // HANDLERS
    const showProjectHandler = id => () => {
        Inertia.get(route('projects.show', id))
    }

    // RENDER
    return (
        <>
            <ResourceContainer
                title={contractor.name}
                extra={<BackLink href={route('contractors.index')} title="Назад к контрагентам"/>}
            >
                <ItemsForm
                    name={formName}
                    layout={"vertical"}
                    items={items}
                />
                <TTable
                    columns={columns}
                    dataSource={contractor?.projects}
                    doubleClickHandler={showProjectHandler}
                    clickRecord
                />
            </ResourceContainer>
        </>
    )
}
