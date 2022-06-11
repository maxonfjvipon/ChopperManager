import React, {useState} from 'react'
import {usePage} from "@inertiajs/inertia-react";
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {useNotifications} from "../../../../../../resources/js/src/Hooks/notifications.hook";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {Export} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Export";
import {View} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Delete} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Delete";
import {ResourceContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {PrimaryAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";
import {BackToProjectsLink} from "../Components/BackToProjectsLink";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {ExportSelectionDrawer} from "../../../../../Selection/Resources/assets/js/Components/ExportSelectionDrawer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {useDate} from "../../../../../../resources/js/src/Hooks/date.hook";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";

export default function Show() {
    // HOOKS
    const {project, auth, filter_data} = usePage().props
    const {filteredBoolArray} = usePermissions()
    const {openRestoreNotification} = useNotifications()
    const {compareDate} = useDate()
    const {reducedBottomAntFormItemClassName} = useStyles()

    // CONSTS
    const formName = 'project-form'
    const items = filteredBoolArray([
        {
            values: {
                name: 'name',
                label: "Наименование",
                initialValue: project.name,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        }, {
            values: {
                name: 'area',
                label: "Область",
                initialValue: project.area,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        }, {
            values: {
                name: 'status',
                label: "Статус",
                initialValue: project.status,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        },
        auth.is_admin && {
            values: {
                name: 'customer',
                label: "Заказчик",
                initialValue: project.customer,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        },
        auth.is_admin && {
            values: {
                name: 'installer',
                label: "Монтажник",
                initialValue: project.installer,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        },
        auth.is_admin && {
            values: {
                name: 'designer',
                label: "Проектировщик",
                initialValue: project.designer,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        },
        auth.is_admin && {
            values: {
                name: 'dealer',
                label: "Диллер",
                initialValue: project.dealer,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input readOnly/>,
        }, {
            values: {
                name: 'description',
                label: "Описание",
                initialValue: project.description,
                className: reducedBottomAntFormItemClassName,
            }, input: <Input.TextArea readOnly/>,
        }
    ])

    const columns = [
        {
            title: "Дата создания",
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
        },
        {
            title: "Дата обновления",
            dataIndex: 'updated_at',
            sorter: (a, b) => compareDate(a.updated_at, b.updated_at),
            defaultSortOrder: 'ascend' // fixme - doesn't work
        },
        {
            title: "Тип станции",
            dataIndex: "station_type",
            filters: filter_data?.station_types,
            onFilter: (type, record) => record.station_type === type
        },
        {
            title: "Тип подбора",
            dataIndex: "type",
            filters: filter_data?.selection_types,
            onFilter: (type, record) => record.type === type
        },
        {
            title: "Расход, м³/ч",
            dataIndex: 'flow',
        },
        {
            title: "Напор, м",
            dataIndex: 'head',
        },
        {
            key: 'actions', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showSelectionHandler(record)}/>
                        <Delete
                            sureDeleteTitle="Вы уверены, что хотите удалить подбор?"
                            confirmHandler={deleteSelectionHandler(record.id)}
                        />
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const showSelectionHandler = record => () => {
        Inertia.get(route('selections.show', record.id))
    }

    const deleteSelectionHandler = id => () => {
        Inertia.delete(route('selections.destroy', id))
        openRestoreNotification(
            "Восстановить подбор?",
            route('selections.restore', id),
        )
    }

    // RENDER
    return (
        <>
            <ResourceContainer
                title={project.name}
                actions={<PrimaryAction
                    label="Гидравлический подбор"
                    route={route('selections.dashboard', project.id)}
                />}
                extra={<BackToProjectsLink/>}
            >
                <ItemsForm
                    name={formName}
                    layout={"vertical"}
                    items={items}
                />
                <TTable
                    columns={columns}
                    dataSource={project?.selections}
                    doubleClickHandler={showSelectionHandler}
                    clickRecord
                />
            </ResourceContainer>
        </>
    )
}
