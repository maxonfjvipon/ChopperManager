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
import {UserDetailStatisticsDrawer} from "../Components/UserDetailStatisticsDrawer";
import {useHttp} from "../../../../../../resources/js/src/Hooks/http.hook";
import {Detail} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Detail";
// import {Statistics} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Statistics";

export default function Statistics() {
    // HOOKS
    const {users, filter_data, auth} = usePage().props
    const tRoute = useTransRoutes()
    const {has} = usePermissions()
    const {compareDate} = useDate()
    const {postRequest} = useHttp()

    // STATE
    const [usersToShow, setUsersToShow] = useState(users)
    const [userInfo, setUserInfo] = useState(null)
    const [userStatisticsVisible, setUserStatisticsVisible] = useState(false)

    // CONSTS
    const searchId = 'users-search-input'
    const columns = [
        {
            title: Lang.get('pages.statistics.users.table.last_login_at'),
            dataIndex: 'last_login_at',
            sorter: (a, b) => compareDate(a.last_login_at, b.last_login_at)
        },
        {
            title: Lang.get('pages.statistics.users.table.organization_name'),
            dataIndex: 'organization_name',
            sorter: (a, b) => a.organization_name.localeCompare(b.organization_name),
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: Lang.get('pages.statistics.users.table.full_name'),
            dataIndex: 'full_name',
            sorter: (a, b) => a.full_name.localeCompare(b.full_name),
        },
        {
            title: Lang.get('pages.statistics.users.table.business'),
            dataIndex: 'business',
            filters: filter_data.businesses,
            onFilter: (business, record) => record.business === business
        },
        {title: Lang.get('pages.statistics.users.table.country'), dataIndex: 'country'},
        {
            title: Lang.get('pages.statistics.users.table.city'),
            dataIndex: 'city',
            sorter: (a, b) => a.city.localeCompare(b.city),
        },
        {title: Lang.get('pages.statistics.users.table.projects_count'), dataIndex: 'projects_count'},
        {
            title: Lang.get('pages.statistics.users.table.total_projects_price') + ", " + auth.currency,
            dataIndex: 'total_projects_price',
            sorter: (a, b) => a.total_projects_price - b.total_projects_price,
        },
        {
            title: Lang.get('pages.statistics.users.table.avg_projects_price') + ", " + auth.currency,
            dataIndex: 'avg_projects_price',
            sorter: (a, b) => a.avg_projects_price - b.avg_projects_price,
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <Detail clickHandler={detailUserClickHandler(record.id)}/>
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

    const detailUserClickHandler = id => () => {
        postRequest(tRoute('users.statistics.detail', id))
            .then(data => {
                setUserInfo(data)
            })
    }

    // RENDER
    return (
        <>
            <IndexContainer
                title={Lang.get('pages.statistics.users.full_title')}
            >
                <SearchInput
                    id={searchId}
                    placeholder={Lang.get('pages.users.index.search.placeholder')}
                    searchClickHandler={searchUserClickHandler}
                />
                <TTable
                    columns={columns}
                    dataSource={usersToShow}
                    doubleClickHandler={detailUserClickHandler}
                />
            </IndexContainer>
            <UserDetailStatisticsDrawer
                user={userInfo}
                visible={userStatisticsVisible}
                setVisible={setUserStatisticsVisible}
            />
        </>
    )
}
