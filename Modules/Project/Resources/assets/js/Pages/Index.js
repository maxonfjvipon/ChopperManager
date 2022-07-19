import React, {useEffect, useState} from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {View} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Edit} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {useDate} from "../../../../../../resources/js/src/Hooks/date.hook";
import {SearchInput} from "../../../../../../resources/js/src/Shared/SearchInput";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";

export default function Index() {
    // HOOKS
    const {projects, auth, filter_data} = usePage().props
    const {filteredBoolArray} = usePermissions()
    const {compareDate} = useDate()

    console.log(filter_data)

    // STATE
    const [projectsToShow, setProjectsToShow] = useState(projects)

    // CONSTS
    const searchId = 'project-search-input'
    const columns = filteredBoolArray([
        {
            title: "Дата создания",
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
        }, {
            title: "Дата обновления",
            dataIndex: 'updated_at',
            sorter: (a, b) => compareDate(a.updated_at, b.updated_at),
            defaultSortOrder: 'descend',
        }, {
            title: "Наименование",
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        }, {
            title: 'Область',
            dataIndex: 'area',
            width: 180, render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            filters: filter_data.areas,
            onFilter: (area, record) => record.area === area
        }, {
            title: 'Статус',
            dataIndex: 'status',
            width: 120,
            filters: filter_data.statuses,
            onFilter: (status, record) => record.status === status
        }, {
            title: 'Заказчик',
            dataIndex: 'customer',
        }, {
            title: 'Монтажник',
            dataIndex: 'installer',
        }, {
            title: 'Проектировщик',
            dataIndex: 'designer',
        }, auth.is_admin && {
            title: 'Диллер',
            dataIndex: 'dealer',
        }, auth.is_admin && {
            title: 'Пользователь',
            dataIndex: 'user',
        }, {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showProjectHandler(record.id)}/>
                        <Edit clickHandler={editProjectHandler(record.id)}/>
                    </TableActionsContainer>
                )
            }
        },
    ])

    const editProjectHandler = id => () => {
        Inertia.get(route('projects.edit', id))
    }

    const showProjectHandler = id => () => {
        Inertia.get(route('projects.show', id))
    }

    const searchProjectClickHandler = () => {
        const value = document.getElementById(searchId).value.toLowerCase()
        if (value === "") {
            setProjectsToShow(projects)
        } else {
            setProjectsToShow(projects
                .filter(project => project
                        .name
                        .toLowerCase()
                        .includes(value)
                    || project
                        .pump_stations
                        .includes(value)
                )
            )
        }
    }

    // EFFECTS
    useEffect(() => {
        setProjectsToShow(projects)
    }, [projects])

    // RENDER
    return (
        <IndexContainer
            title={"Проекты"}
            actions={<PrimaryAction
                label="Новый проект"
                route={route('projects.create')}
            />}
        >
            <SearchInput
                id={searchId}
                placeholder="Поиск по наименованию проекта и станций"
                searchClickHandler={searchProjectClickHandler}
                width={450}
            />
            <TTable
                columns={columns}
                dataSource={projectsToShow}
                doubleClickHandler={showProjectHandler}
            />
        </IndexContainer>
    )
}
