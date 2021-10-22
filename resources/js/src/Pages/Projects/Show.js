import React from 'react'
import {Link, usePage} from "@inertiajs/inertia-react";
import {Input} from "antd";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {ItemsForm} from "../../Shared/ItemsForm";
import {TTable} from "../../Shared/Resource/Table/TTable";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {useNotifications} from "../../Hooks/notifications.hook";
import {ResourceContainer} from "../../Shared/Resource/Containers/ResourceContainer";
import {BackToProjectsLink} from "../../Shared/Resource/BackLinks/BackToProjectsLink";
import {PrimaryAction} from "../../Shared/Resource/Actions/PrimaryAction";
import {TableActionsContainer} from "../../Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../Shared/Resource/Table/Actions/View";
import {Delete} from "../../Shared/Resource/Table/Actions/Delete";

const Show = () => {
    // HOOKS
    const {project, auth} = usePage().props
    const {tRoute} = useTransRoutes()
    const {openRestoreNotification} = useNotifications()

    // CONSTS
    const formName = 'project-form'
    const items = [
        {
            values: {
                name: 'name',
                label: Lang.get('pages.projects.create.form.name'),
                initialValue: project.data.name,
            }, input: <Input readOnly/>,
        }
    ]

    const columns = [
        {
            title: Lang.get('pages.projects.show.table.created_at'),
            dataIndex: 'created_at',
            sorter: (a, b) => new Date(a.created_at) - new Date(b.created_at),
            defaultSortOrder: 'ascend'
        },
        {
            title: Lang.get('pages.projects.show.table.selected_pump'),
            dataIndex: 'selected_pump_name',
        },
        {
            title: Lang.get('pages.projects.show.table.part_num'),
            dataIndex: 'article_num',
            render: (_, record) => <Link
                href={tRoute('pumps.show', record.pump_id)}>{record.article_num}</Link>
        },
        {
            title: Lang.get('pages.projects.show.table.consumption'),
            dataIndex: 'flow',
        },
        {
            title: Lang.get('pages.projects.show.table.pressure'),
            dataIndex: 'head',
        },
        {
            title: Lang.get('pages.projects.show.table.price') + ", " + auth.currency,
            dataIndex: 'discounted_price',
            render: (_, record) => record.discounted_price.toLocaleString(),
            sorter: (a, b) => a.discounted_price - b.discounted_price
        },
        {
            title: Lang.get('pages.projects.show.table.total_price') + ", " + auth.currency,
            dataIndex: 'total_discounted_price',
            render: (_, record) => record.total_discounted_price.toLocaleString(),
            sorter: (a, b) => a.total_discounted_price - b.total_discounted_price
        },
        {
            title: Lang.get('pages.projects.show.table.power'),
            dataIndex: 'rated_power',
            sorter: (a, b) => a.rated_power - b.rated_power
        },
        {
            title: Lang.get('pages.projects.show.table.total_power'),
            dataIndex: 'total_rated_power',
            sorter: (a, b) => a.total_rated_power - b.total_rated_power
        },
        {
            key: 'actions', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <View clickHandler={showSelectionHandler(record.id)}/>
                        <Delete
                            sureDeleteTitle={Lang.get('pages.projects.show.table.delete')}
                            confirmHandler={deleteSelectionHandler(record.id)}
                        />
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const showSelectionHandler = id => () => {
        Inertia.get(tRoute('selections.show', id))
    }

    const deleteSelectionHandler = id => () => {
        Inertia.delete(tRoute('selections.destroy', id))
        openRestoreNotification(
            Lang.get('pages.projects.show.restore.title'),
            tRoute('selections.restore', id),
            Lang.get('pages.projects.show.restore.button'),
        )
    }

    // RENDER
    return (
        <ResourceContainer
            title={project.data.name}
            actions={<PrimaryAction
                label={Lang.get('pages.projects.show.selection')}
                route={tRoute('selections.dashboard', project.data.id)}
            />}
            back={<BackToProjectsLink/>}
        >
            <ItemsForm
                name={formName}
                layout={"vertical"}
                items={items}
            />
            <TTable
                columns={columns}
                dataSource={project.data?.selections}
                doubleClickHandler={showSelectionHandler}
            />
        </ResourceContainer>
    )
}

Show.layout = page =>
    <AuthLayout children={page}/>

export default Show
