import React from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import Lang from "../../../translation/lang";
import {TTable} from "../../Shared/Resource/Table/TTable";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {useNotifications} from "../../Hooks/notifications.hook";
import {IndexContainer} from "../../Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../Shared/Resource/Actions/PrimaryAction";
import {SecondaryAction} from "../../Shared/Resource/Actions/SecondaryAction";
import {TableActionsContainer} from "../../Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../Shared/Resource/Table/Actions/View";
import {Edit} from "../../Shared/Resource/Table/Actions/Edit";
import {Delete} from "../../Shared/Resource/Table/Actions/Delete";

const Index = () => {
    // HOOKS
    const {projects} = usePage().props
    const {tRoute} = useTransRoutes()
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
                        <View clickHandler={showProjectHandler(record.id)}/>
                        <Edit clickHandler={editProjectHandler(record.id)}/>
                        <Delete
                            confirmHandler={deleteProjectHandler(record.id)}
                            sureDeleteTitle={Lang.get('pages.projects.index.table.delete')}
                        />
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const deleteProjectHandler = id => () => {
        Inertia.delete(tRoute('projects.destroy', id))
        openRestoreNotification(
            Lang.get('pages.projects.index.restore.title'),
            tRoute('projects.restore', id),
            Lang.get('pages.projects.index.restore.button')
        )
    }

    const editProjectHandler = id => () => {
        Inertia.get(tRoute('projects.edit', id))
    }

    const showProjectHandler = id => () => {
        Inertia.get(tRoute('projects.show', id))
    }

    // RENDER
    return (
        <IndexContainer
            title={Lang.get('pages.projects.title')}
            actions={[
                <PrimaryAction
                    label={Lang.get('pages.projects.index.button')}
                    route={tRoute('projects.create')}
                />,
                <SecondaryAction
                    label={Lang.get('pages.projects.index.selection')}
                    route={tRoute('selections.dashboard', -1)}
                />
            ]}
        >
            <TTable
                columns={columns}
                dataSource={projects}
                doubleClickHandler={showProjectHandler}
            />
        </IndexContainer>
    )
}

Index.layout = page => <AuthLayout children={page}/>

export default Index
