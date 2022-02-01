import React, {useEffect, useState} from 'react'
import Lang from "../../../../../../../resources/js/translation/lang";
import {SearchInput} from "../../../../../../../resources/js/src/Shared/SearchInput";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {Tooltip} from "antd";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useDate} from "../../../../../../../resources/js/src/Hooks/date.hook";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Edit} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {Inertia} from "@inertiajs/inertia";

export default function Statistics() {
    // HOOKS
    const tRoute = useTransRoutes()
    const {projects, project_statuses, delivery_statuses} = usePage().props
    const {has, filterPermissionsArray} = usePermissions()
    const {compareDate} = useDate()

    // STATE
    const [projectsToShow, setProjectsToShow] = useState(projects)

    // CONSTS
    const searchId = 'project-search-input'
    const columns = [
        {
            title: Lang.get('pages.projects.statistics.table.created_at'),
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
            defaultSortOrder: ['ascend']
        },
        {
            title: Lang.get('pages.projects.statistics.table.client'),
            dataIndex: 'user',
            sorter: (a, b) => a.user.localeCompare(b.name)
        },
        {
            title: Lang.get('pages.projects.statistics.table.name'),
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            width: '80%',
            sorter: (a, b) => a.name.localeCompare(b.name)
        },
        {
            title: Lang.get('pages.projects.statistics.table.selections_count'),
            dataIndex: 'selections_count',
            sorter: (a, b) => a.selections_count - b.selections_count
        },
        {
            title: Lang.get('pages.projects.statistics.table.retail_price'),
            dataIndex: 'price',
            sorter: (a, b) => a.price - b.price
        },
        {
            title: Lang.get('pages.projects.statistics.table.status'),
            dataIndex: 'status',
            filters: project_statuses,
            onFilter: (status, record) => record.status === status
        },
        {
            title: Lang.get('pages.projects.statistics.table.delivery_status'),
            dataIndex: 'delivery_status',
            filters: delivery_statuses,
            onFilter: (status, record) => record.status === status
        },
        {
            title: Lang.get('pages.projects.statistics.table.comment'),
            dataIndex: 'comment',
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {has('project_show') && <View clickHandler={showProjectHandler(record.id)}/>}
                        {has('project_edit') && <Edit clickHandler={editProjectHandler(record.id)}/>}
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const editProjectHandler = id => () => {
        Inertia.get(tRoute('projects.statistics.edit', id))
    }

    const showProjectHandler = id => () => {
        Inertia.get(tRoute('projects.show', id))
    }

    const searchProjectClickHandler = () => {
        const value = document.getElementById(searchId).value.toLowerCase()
        if (value === "") {
            setProjectsToShow(projects)
        } else {
            setProjectsToShow(projects.filter(project => project.name.toLowerCase().includes(value)))
        }
    }

    // EFFECTS
    useEffect(() => {
        setProjectsToShow(projects)
    }, [projects])

    return (
        <IndexContainer
            title={Lang.get('pages.projects.statistics.title')}
        >
            <SearchInput
                id={searchId}
                placeholder={Lang.get('pages.projects.index.search.placeholder')}
                searchClickHandler={searchProjectClickHandler}
            />
            <TTable
                columns={columns}
                dataSource={projectsToShow}
                doubleClickHandler={has('project_show') && showProjectHandler}
            />
        </IndexContainer>
    )
}
