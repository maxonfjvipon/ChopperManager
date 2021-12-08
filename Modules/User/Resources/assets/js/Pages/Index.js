import React from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {Edit} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import Lang from "../../../../../../resources/js/translation/lang";
import {PrimaryAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";

export default function Index() {
    // HOOKS
    const {users} = usePage().props
    const tRoute = useTransRoutes()
    const {has} = usePermissions()

    // CONSTS
    const columns = [
        {title: Lang.get('pages.users.index.table.created_at'), dataIndex: 'created_at'},
        {title: Lang.get('pages.users.index.table.email'), dataIndex: 'email'},
        {
            title: Lang.get('pages.users.index.table.organization_name'),
            dataIndex: 'organization_name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: Lang.get('pages.users.index.table.full_name'), dataIndex: 'full_name',
            render: (_, record) => `${record.first_name} ${record.middle_name}`
        },
        {title: Lang.get('pages.users.index.table.city'), dataIndex: 'city'},
        {title: Lang.get('pages.users.index.table.country'), dataIndex: 'country', render: (_, record) => record.country.name},
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('user_edit') && <Edit clickHandler={editUserHandler(record.id)}/>}
                        {/*{has('user_delete') && <Delete*/}
                        {/*    confirmHandler={deleteUserHandler(record.id)}*/}
                        {/*    sureDeleteTitle={Lang.get('pages.users.index.table.delete')}*/}
                        {/*/>}*/}
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    // const deleteUserHandler = id => () => {
    //     Inertia.delete(tRoute('user.destroy', id))
    //     if (has('user_restore'))
    //         openRestoreNotification(
    //             Lang.get('pages.users.index.restore.title'),
    //             tRoute('users.restore', id),
    //             Lang.get('pages.users.index.restore.button')
    //         )
    // }

    const editUserHandler = id => () => {
        Inertia.get(tRoute('users.edit', id))
    }

    // RENDER
    return (
        <IndexContainer
            actions={<PrimaryAction label={Lang.get('pages.users.create.title')} route={tRoute('users.create')}/>}
            title={Lang.get('pages.users.title')}
        >
            <TTable
                columns={columns}
                dataSource={users}
                doubleClickHandler={has('user_edit') && editUserHandler}
            />
        </IndexContainer>
    )
}
