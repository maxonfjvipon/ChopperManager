import React, {useEffect, useState} from 'react';
import {Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import AuthLayout from "../../Shared/Layout/AuthLayout";
import {TTable} from "../../Shared/Resource/Table/TTable";
import {SearchInput} from "../../Shared/SearchInput";
import {IndexContainer} from "../../Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../Shared/Resource/Actions/PrimaryAction";
import {TableActionsContainer} from "../../Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../Shared/Resource/Table/Actions/View";
import {Edit} from "../../Shared/Resource/Table/Actions/Edit";

export default function Index() {
    // HOOKS
    const {projects} = usePage().props

    // STATE
    const [projectsToShow, setProjectsToShow] = useState(projects)

    // CONSTS
    const searchId = 'project-search-input'

    // CONSTS
    const columns = [
        {title: "Дата создания", dataIndex: 'created_at'},
        {
            title: "Наименование",
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: 'Область',
            dataIndex: 'area',
            width: 180, render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>
        },
        {
            title: 'Статус',
            dataIndex: 'status',
            width: 120
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showProjectHandler(record.id)}/>
                        <Edit clickHandler={editProjectHandler(record.id)}/>
                    </TableActionsContainer>
                )
            }
        },
    ]

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
            setProjectsToShow(projects.filter(project => project.name.toLowerCase().includes(value)))
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
                placeholder="Поиск по наименованию"
                searchClickHandler={searchProjectClickHandler}
            />
            <TTable
                columns={columns}
                dataSource={projectsToShow}
                doubleClickHandler={showProjectHandler}
            />
        </IndexContainer>
    )
}
