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
import {usePermissions} from "../../Hooks/permissions.hook";

const Index = () => {
    // HOOKS
    const {users} = usePage().props
    const {tRoute} = useTransRoutes()
    const {has, filterPermissionsArray} = usePermissions()
    const {openRestoreNotification} = useNotifications()

    console.log(users)

    // CONSTS
    const columns = [
        {title: Lang.get('pages.users.index.table.full_name'), dataIndex: 'full_name'},
        // {title: Lang.get('pages.users.index.table.itn'), dataIndex: 'itn'},
        {
            title: Lang.get('pages.users.index.table.organization_name'),
            dataIndex: 'organization_name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            // width: '80%'
        },
        {title: Lang.get('pages.users.index.table.main_business'), dataIndex: 'main_business'},
        {title: Lang.get('pages.users.index.table.email'), dataIndex: 'email'},
        {title: Lang.get('pages.users.index.table.phone'), dataIndex: 'phone'},
        {title: Lang.get('pages.users.index.table.country'), dataIndex: 'country'},
        {title: Lang.get('pages.users.index.table.city'), dataIndex: 'city'},
        // {title: Lang.get('pages.users.index.table.postcode'), dataIndex: 'postcode'},
        {title: "Series", dataIndex: 'series'},
        {title: "Selection types", dataIndex: 'selections'},
        // {title: Lang.get('pages.users.index.table.currency'), dataIndex: 'currency'},
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {/*{has('user_show') && <View clickHandler={showProjectHandler(record.id)}/>}*/}
                        {has('user_edit') && <Edit clickHandler={editProjectHandler(record.id)}/>}
                        {has('user_delete') && <Delete
                            confirmHandler={deleteProjectHandler(record.id)}
                            sureDeleteTitle={Lang.get('pages.users.index.table.delete')}
                        />}
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const deleteProjectHandler = id => () => {
        Inertia.delete(tRoute('users.destroy', id))
        if (has('users_restore'))
            openRestoreNotification(
                Lang.get('pages.users.index.restore.title'),
                tRoute('projects.restore', id),
                Lang.get('pages.users.index.restore.button')
            )
    }

    const editProjectHandler = id => () => {
        Inertia.get(tRoute('users.edit', id))
    }

    // const showProjectHandler = id => () => {
    //     Inertia.get(tRoute('users.show', id))
    // }

    // RENDER
    return (
        <IndexContainer
            title={Lang.get('pages.users.title')}
            // actions={
            //     has('user_create') && <PrimaryAction
            //         label={Lang.get('pages.users.index.button')}
            //         route={tRoute('users.create')}
            //     />
            // }
        >
            <TTable
                columns={columns}
                dataSource={users}
                // doubleClickHandler={has('user_show') && showProjectHandler}
                expandable={{
                    expandedRowRender: record => <>
                        <p style={{margin: 0}}>{"Series: " + record.series}</p>
                        <p style={{margin: 0}}>{"Selections: " + record.selections}</p>
                    </>

                }}
            />
        </IndexContainer>
    )
}

Index.layout = page =>
    <AuthLayout children={page}/>

export default Index
