import React, {useState} from 'react';
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
import {SearchInput} from "../../../../../../resources/js/src/Shared/SearchInput";
import {useDate} from "../../../../../../resources/js/src/Hooks/date.hook";

export default function Index() {
    // HOOKS
    const {users, filter_data} = usePage().props
    const tRoute = useTransRoutes()
    const {has} = usePermissions()
    const {compareDate} = useDate()

    // STATE
    const [usersToShow, setUsersToShow] = useState(users)

    // CONSTS
    const searchId = 'users-search-input'
    const columns = [
        {
            title: Lang.get('pages.users.index.table.last_login_at'),
            dataIndex: 'last_login_at',
            sorter: (a, b) => compareDate(a.last_login_at, b.last_login_at)
        },
        {
            title: Lang.get('pages.users.index.table.organization_name'),
            dataIndex: 'organization_name',
            sorter: (a, b) => a.organization_name.localeCompare(b.organization_name),
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: Lang.get('pages.users.index.table.full_name'),
            dataIndex: 'full_name',
            sorter: (a, b) => a.full_name.localeCompare(b.full_name),
        },
        {
            title: Lang.get('pages.users.index.table.business'),
            dataIndex: 'business',
            filters: filter_data.businesses,
            onFilter: (business, record) => record.business === business
        },
        {title: Lang.get('pages.users.index.table.country'), dataIndex: 'country'},
        {
            title: Lang.get('pages.users.index.table.city'),
            dataIndex: 'city',
            sorter: (a, b) => a.city.localeCompare(b.city),
        },
        {title: Lang.get('pages.users.index.table.projects_count'), dataIndex: 'projects_count'},
        {
            title: Lang.get('pages.users.index.table.projects_price'),
            dataIndex: 'projects_price',
            sorter: (a, b) => a.projects_price - b.projects_price,
        },
        {
            title: Lang.get('pages.users.index.table.avg_projects_price'),
            dataIndex: 'avg_projects_price',
            sorter: (a, b) => a.avg_projects_price - b.avg_projects_price,
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('user_edit') && <Edit clickHandler={editUserHandler(record.id)}/>}
                    </TableActionsContainer>
                )
            }
        },
    ]

    const searchUserClickHandler = () => {
        const value = document.getElementById(searchId).value.toLowerCase()
        if (value === "") {
            setUsersToShow(users)
        } else {
            setUsersToShow(users.filter(user => (user.full_name + user.organization_name)
                .toLowerCase()
                .includes(value))
            )
        }
    }

    const editUserHandler = id => () => {
        Inertia.get(tRoute('users.edit', id))
    }

    // RENDER
    return (
        <IndexContainer
            actions={<PrimaryAction label={Lang.get('pages.users.create.title')} route={tRoute('users.create')}/>}
            title={Lang.get('pages.users.title')}
        >
            <SearchInput
                id={searchId}
                placeholder={Lang.get('pages.users.index.search.placeholder')}
                searchClickHandler={searchUserClickHandler}
            />
            <TTable
                columns={columns}
                dataSource={usersToShow}
                doubleClickHandler={has('user_edit') && editUserHandler}
            />
        </IndexContainer>
    )
}
