import React from 'react'
import {Link, usePage} from "@inertiajs/inertia-react";
import {Button, Input, Popconfirm, Space, Tooltip} from "antd";
import {BoxFlexEnd} from "../../Shared/Box/BoxFlexEnd";
import {DeleteOutlined, EyeOutlined} from "@ant-design/icons";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {ItemsForm} from "../../Shared/ItemsForm";
import {TTable} from "../../Shared/ResourcePanel/Index/Table/TTable";
import {useTransRoutes} from "../../Hooks/routes.hook";
import {Container} from "../../Shared/ResourcePanel/Show/Container";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {useNotifications} from "../../Hooks/notifications.hook";

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
            key: 'actions', width: '5%', render: (_, record) => {
                return (
                    <BoxFlexEnd>
                        <Space size={'small'}>
                            <Tooltip placement="topRight" title={Lang.get('tooltips.view')}>
                                <Button
                                    onClick={showSelectionHandler(record.id)}
                                    icon={<EyeOutlined/>}
                                />
                            </Tooltip>
                            <Tooltip placement="topRight" title={Lang.get('tooltips.delete')}>
                                <Popconfirm
                                    title={Lang.get('pages.projects.show.table.delete')}
                                    onConfirm={deleteSelectionHandler(record.id)}
                                    okText={Lang.get('tooltips.popconfirm.yes')}
                                    cancelText={Lang.get('tooltips.popconfirm.no')}
                                >
                                    <Button icon={<DeleteOutlined/>}/>
                                </Popconfirm>
                            </Tooltip>
                            {/*<Tooltip placement="topRight" title={Lang.get('tooltips.print')}>*/}
                            {/*    <Button icon={<PrinterOutlined/>} onClick={() => {*/}
                            {/*        message.info(Lang.get('messages.function_development'))*/}
                            {/*    }}/>*/}
                            {/*</Tooltip>*/}
                            {/*<Tooltip placement="topRight" title={Lang.get('tooltips.export')}>*/}
                            {/*    <Button icon={<ExportOutlined/>} onClick={() => {*/}
                            {/*        message.info(Lang.get('messages.function_development'))*/}
                            {/*    }}/>*/}
                            {/*</Tooltip>*/}
                        </Space>
                    </BoxFlexEnd>
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
        <Container
            title={project.data.name}
            backTitle={Lang.get('pages.projects.back')}
            backHref={tRoute('projects.index')}
            actionButtons={[<PrimaryButton onClick={() => {
                Inertia.get(tRoute('selections.dashboard', project.data.id))
            }}>
                {Lang.get('pages.projects.show.selection')}
            </PrimaryButton>]}
        >
            <ItemsForm
                name={formName}
                layout={"vertical"}
                items={items}
            />
            <TTable
                columns={columns}
                dataSource={project.data?.selections}
                showHandler={showSelectionHandler}
            />
        </Container>
    )
}

Show.layout = page => <AuthLayout children={page}/>

export default Show
