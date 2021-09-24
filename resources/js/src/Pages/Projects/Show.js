import React from 'react'
import {Link, usePage} from "@inertiajs/inertia-react";
import {Button, Input, message, Popconfirm, Space, Tooltip} from "antd";
import {BoxFlexEnd} from "../../Shared/Box/BoxFlexEnd";
import {SecondaryButton} from "../../Shared/Buttons/SecondaryButton";
import {DeleteOutlined, EditOutlined, ExportOutlined, PrinterOutlined} from "@ant-design/icons";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Inertia} from "@inertiajs/inertia";
import Lang from "../../../translation/lang";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {Container} from "../../Shared/ResourcePanel/Resource/Container";
import {ItemsForm} from "../../Shared/ItemsForm";
import {TTable} from "../../Shared/ResourcePanel/Index/Table/TTable";
import {useTransRoutes} from "../../Hooks/routes.hook";

const Show = () => {
    // HOOKS
    const {rules} = useInputRules()
    const {project} = usePage().props
    const {tRoute} = useTransRoutes()

    // CONSTS
    const formName = 'project-form'
    const items = [
        {
            values: {
                name: 'name',
                label: Lang.get('pages.projects.create.form.name'),
                rules: [rules.required],
                initialValue: project.data.name,
            }, input: <Input/>,
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
            dataIndex: 'part_num_main',
            render: (_, record) => <Link
                href={tRoute('pumps.show', record.id)}>{record.part_num_main}</Link>
        },
        {
            title: Lang.get('pages.projects.show.table.consumption'),
            dataIndex: 'consumption',
        },
        {
            title: Lang.get('pages.projects.show.table.pressure'),
            dataIndex: 'pressure',
        },
        {
            title: Lang.get('pages.projects.show.table.price'),
            dataIndex: 'price',
            sorter: (a, b) => a.price - b.price
        },
        {
            title: Lang.get('pages.projects.show.table.total_price'),
            dataIndex: 'sum_price',
            sorter: (a, b) => a.sum_price - b.sum_price
        },
        {
            title: Lang.get('pages.projects.show.table.power'),
            dataIndex: 'power',
            sorter: (a, b) => a.power - b.power
        },
        {
            title: Lang.get('pages.projects.show.table.total_power'),
            dataIndex: 'sum_power',
            sorter: (a, b) => a.sum_power - b.sum_power
        },
        {
            key: 'actions', width: '5%', render: (_, record) => {
                return (
                    <BoxFlexEnd>
                        <Space size={'small'}>
                            <Tooltip placement="topRight" title={Lang.get('tooltips.view')}>
                                <Button
                                    onClick={showSelectionHandler(record.id)}
                                    icon={<EditOutlined/>}
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
                            <Tooltip placement="topRight" title={Lang.get('tooltips.print')}>
                                <Button icon={<PrinterOutlined/>} onClick={() => {
                                    message.info(Lang.get('messages.function_development'))
                                }}/>
                            </Tooltip>
                            <Tooltip placement="topRight" title={Lang.get('tooltips.export')}>
                                <Button icon={<ExportOutlined/>} onClick={() => {
                                    message.info(Lang.get('messages.function_development'))
                                }}/>
                            </Tooltip>
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

    const updateProjectHandler = body => {
        Inertia.put(tRoute('projects.update', project.data.id), body)
    }

    const deleteSelectionHandler = id => () => {
        Inertia.delete(tRoute('selections.destroy', id))
    }

    // RENDER
    return (
        <Container
            title={project.data.name}
            buttonLabel={Lang.get('pages.projects.show.save_button')}
            form={formName}
            backTitle={Lang.get('pages.projects.back')}
            backHref={tRoute('projects.index')}
            extraButtons={[<SecondaryButton onClick={() => {
                Inertia.get(tRoute('selections.dashboard', project.data.id))
            }}>
                {Lang.get('pages.projects.show.selection')}
            </SecondaryButton>]}
        >
            <ItemsForm
                name={formName}
                layout={"vertical"}
                items={items}
                onFinish={updateProjectHandler}
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
