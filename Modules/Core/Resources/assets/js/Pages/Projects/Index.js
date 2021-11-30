import React, {useState} from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import Lang from "../../../../../../../resources/js/translation/lang";
import {useTransRoutes} from '../../../../../../../resources/js/src/Hooks/routes.hook'
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useNotifications} from "../../../../../../../resources/js/src/Hooks/notifications.hook";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Edit} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {Export} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Export";
import {Delete} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Delete";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";
import {SecondaryAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SecondaryAction";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {ExportProjectDrawer} from "../../Components/ExportProjectDrawer";

export default function Index() {
    // STATE
    const [exportProjectDrawerVisible, setExportProjectDrawerVisible] = useState(false)
    const [project, setProject] = useState(null)

    // HOOKS
    const tRoute = useTransRoutes()
    const {projects} = usePage().props
    const {has, filterPermissionsArray} = usePermissions()
    const {openRestoreNotification} = useNotifications()

    // CONSTS
    const columns = [
        {title: Lang.get('pages.projects.index.table.created_at'), dataIndex: 'created_at'},
        {
            title: Lang.get('pages.projects.index.table.name'),
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            width: '80%'
        },
        {title: Lang.get('pages.projects.index.table.count'), dataIndex: 'selections_count'},
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('project_show') && <View clickHandler={showProjectHandler(record.id)}/>}
                        {has('project_edit') && <Edit clickHandler={editProjectHandler(record.id)}/>}
                        {has('project_export') && <Export clickHandler={exportProjectHandler(record.id)}/>}
                        {has('project_delete') && <Delete
                            confirmHandler={deleteProjectHandler(record.id)}
                            sureDeleteTitle={Lang.get('pages.projects.index.table.delete')}
                        />}
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const deleteProjectHandler = id => () => {
        Inertia.delete(tRoute('projects.destroy', id))
        if (has('project_restore'))
            openRestoreNotification(
                Lang.get('pages.projects.index.restore.title'),
                tRoute('projects.restore', id),
                Lang.get('pages.projects.index.restore.button')
            )
    }

    const exportProjectHandler = id => () => {
        setProject(projects.find(project => project.id === id))
        setExportProjectDrawerVisible(true)
    }

    const editProjectHandler = id => () => {
        Inertia.get(tRoute('projects.edit', id))
    }

    const showProjectHandler = id => () => {
        Inertia.get(tRoute('projects.show', id))
    }

    // RENDER
    return (
        <>
            <IndexContainer
                title={Lang.get('pages.projects.title')}
                actions={filterPermissionsArray([
                    has('project_create') && <PrimaryAction
                        label={Lang.get('pages.projects.index.button')}
                        route={tRoute('projects.create')}
                    />,
                    has('selection_create_without_saving') && <SecondaryAction
                        label={Lang.get('pages.projects.index.selection')}
                        route={tRoute('selections.dashboard', -1)}
                    />
                ])}
            >
                <TTable
                    columns={columns}
                    dataSource={projects}
                    doubleClickHandler={has('project_show') && showProjectHandler}
                />
            </IndexContainer>
            <ExportProjectDrawer
                visible={exportProjectDrawerVisible}
                setVisible={setExportProjectDrawerVisible}
                project={project}
            />
        </>
    )
}