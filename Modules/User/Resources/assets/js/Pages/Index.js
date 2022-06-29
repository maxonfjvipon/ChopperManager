import React, {useState} from 'react';
import {Tag, Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
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
    const {compareDate} = useDate()

    console.log(users, filter_data)

    // STATE
    const [usersToShow, setUsersToShow] = useState(users)

    // CONSTS
    const searchId = 'users-search-input'
    const columns = [
        {
            title: "Дата создания",
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at)
        },
        {
            title: "Роль",
            dataIndex: 'role',
            filters: filter_data.roles,
            onFilter: (role, record) => record.role === role
        },
        {
            title: "Наименование организации",
            dataIndex: 'organization_name',
            sorter: (a, b) => a.organization_name.localeCompare(b.organization_name),
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: "ФИО",
            dataIndex: 'full_name',
            sorter: (a, b) => a.full_name.localeCompare(b.full_name),
        },
        {
            title: "Телефон",
            dataIndex: 'phone'
        },
        {
            title: "Email",
            dataIndex: 'email'
        },
        {
            title: "Область",
            dataIndex: 'area',
            filters: filter_data.areas,
            onFilter: (area, record) => record.area === area
        },
        {
            title: "Активен",
            dataIndex: 'is_active',
            render: (_, record) => record.is_active
                ? <Tag color="green">
                    {Lang.get('tooltips.popconfirm.yes')}
                </Tag> : <Tag color="orange">
                    {Lang.get('tooltips.popconfirm.no')}
                </Tag>
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <Edit clickHandler={editUserHandler(record.id)}/>
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
        Inertia.get(route('users.edit', id))
    }

    // RENDER
    return (
        <>
            <IndexContainer
                actions={<PrimaryAction label="Создать пользователя" route={route('users.create')}/>}
                title="Пользователи"
            >
                <SearchInput
                    id={searchId}
                    placeholder="Поиск по ФИО и организации"
                    searchClickHandler={searchUserClickHandler}
                />
                <TTable
                    columns={columns}
                    dataSource={usersToShow}
                    doubleClickHandler={editUserHandler}
                />
            </IndexContainer>
        </>
    )
}
