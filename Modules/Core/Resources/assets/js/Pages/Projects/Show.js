import React, {useState} from 'react'
import {Link, usePage} from "@inertiajs/inertia-react";
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../../../../../resources/js/translation/lang";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useNotifications} from "../../../../../../../resources/js/src/Hooks/notifications.hook";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {Export} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Export";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Delete} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Delete";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {PrimaryAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";
import {BackToProjectsLink} from "../../Components/BackToProjectsLink";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {ExportProjectDrawer} from "../../Components/ExportProjectDrawer";
import {ExportSelectionDrawer} from "../../../../../../Selection/Resources/assets/js/Components/ExportSelectionDrawer";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";

export default function Show() {
    // STATE
    const [exportDrawerVisible, setExportDrawerVisible] = useState(false)
    const [exportProjectVisible, setExportProjectVisible] = useState(false)
    const [exportableId, setExportableId] = useState(0)

    // HOOKS
    const tRoute = useTransRoutes()
    const {project, auth} = usePage().props
    const {has, filterPermissionsArray} = usePermissions()
    const {openRestoreNotification} = useNotifications()

    // CONSTS
    const formName = 'project-form'
    const items = [
        {
            values: {
                name: 'name',
                label: Lang.get('pages.projects.create.form.name'),
                initialValue: project.data.name,
            }, input: <Input readOnly/>,
        }
    ]

    const columns = [
        {
            title: Lang.get('pages.projects.show.table.created_at'),
            dataIndex: 'created_at',
            sorter: (a, b) => new Date(a.created_at) - new Date(b.created_at),
            defaultSortOrder: 'ascend'
        },
        {
            title: Lang.get('pages.projects.show.table.selected_pump'),
            dataIndex: 'selected_pump_name',
        },
        {
            title: Lang.get('pages.projects.show.table.part_num'),
            dataIndex: 'article_num',
            render: (_, record) => <Link
                href={tRoute('pumps.show', record.pump_id)}>{record.article_num}</Link>
        },
        {
            title: Lang.get('pages.projects.show.table.consumption'),
            dataIndex: 'flow',
        },
        {
            title: Lang.get('pages.projects.show.table.pressure'),
            dataIndex: 'head',
        },
        {
            title: Lang.get('pages.projects.show.table.price') + ", " + auth.currency,
            dataIndex: 'discounted_price',
            render: (_, record) => record.discounted_price.toLocaleString(),
            sorter: (a, b) => a.discounted_price - b.discounted_price
        },
        {
            title: Lang.get('pages.projects.show.table.total_price') + ", " + auth.currency,
            dataIndex: 'total_discounted_price',
            render: (_, record) => record.total_discounted_price.toLocaleString(),
            sorter: (a, b) => a.total_discounted_price - b.total_discounted_price
        },
        {
            title: Lang.get('pages.projects.show.table.power'),
            dataIndex: 'rated_power',
            sorter: (a, b) => a.rated_power - b.rated_power
        },
        {
            title: Lang.get('pages.projects.show.table.total_power'),
            dataIndex: 'total_rated_power',
            sorter: (a, b) => a.total_rated_power - b.total_rated_power
        },
        {
            key: 'actions', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('selection_show') && <View clickHandler={showSelectionHandler(record.id)}/>}
                        {has('selection_export') && <Export clickHandler={exportSelectionHandler(record.id)}/>}
                        {has('selection_delete') && <Delete
                            sureDeleteTitle={Lang.get('pages.projects.show.table.delete')}
                            confirmHandler={deleteSelectionHandler(record.id)}
                        />}
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const showSelectionHandler = id => () => {
        Inertia.get(tRoute('sp_selections.show', id))
    }

    const deleteSelectionHandler = id => () => {
        Inertia.delete(tRoute('sp_selections.destroy', id))
        if (has('selection_restore'))
            openRestoreNotification(
                Lang.get('pages.projects.show.restore.title'),
                tRoute('sp_selections.restore', id),
                Lang.get('pages.projects.show.restore.button'),
            )
    }

    const exportSelectionHandler = id => () => {
        setExportableId(id)
        setExportDrawerVisible(true)
    }

    // RENDER
    return (
        <>
            <ResourceContainer
                title={project.data.name}
                actions={has('selection_create') && <PrimaryAction
                    label={Lang.get('pages.projects.show.selection')}
                    route={tRoute('selections.dashboard', project.data.id)}
                />}
                extra={filterPermissionsArray([
                    has('project_export') &&
                    <a onClick={event => {
                        event.preventDefault()
                        setExportProjectVisible(true)
                    }}>{Lang.get('pages.projects.export.title')}</a>,
                    <BackToProjectsLink/>
                ])}
            >
                <ItemsForm
                    name={formName}
                    layout={"vertical"}
                    items={items}
                />
                {has('selection_access') && <TTable
                    columns={columns}
                    dataSource={project.data?.selections}
                    doubleClickHandler={has('selection_show') && showSelectionHandler}
                />}
            </ResourceContainer>
            <ExportSelectionDrawer selection_id={exportableId} visible={exportDrawerVisible} setVisible={setExportDrawerVisible}/>
            <ExportProjectDrawer project={project.data} visible={exportProjectVisible} setVisible={setExportProjectVisible}/>
        </>
    )
}
