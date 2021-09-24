import React from 'react';
import {Button, Popconfirm, Space, Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {DeleteOutlined, EditOutlined} from "@ant-design/icons";
import {SecondaryButton} from "../../Shared/Buttons/SecondaryButton";
import Lang from "../../../translation/lang";
import {Container} from "../../Shared/ResourcePanel/Index/Container";
import route from "ziggy-js/src/js";
import {TTable} from "../../Shared/ResourcePanel/Index/Table/TTable";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";
import {useTransRoutes} from "../../Hooks/routes.hook";

const Index = () => {
    // HOOKS
    const {projects} = usePage().props
    const {tRoute} = useTransRoutes()

    // CONSTS
    const columns = [
        {title: Lang.get('pages.projects.index.table.created_at'), dataIndex: 'created_at'},
        {
            title: Lang.get('pages.projects.index.table.name'),
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            width: '80%'
        },
        {title: Lang.get('pages.projects.index.table.count'), dataIndex: 'selections_count'},
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <Space size={'small'}>
                        <Tooltip placement="topRight" title={Lang.get('tooltips.view')}>
                            <Button
                                onClick={showProjectHandler(record.id)}
                                icon={<EditOutlined/>}
                            />
                        </Tooltip>
                        <Tooltip placement="topRight" title={Lang.get('tooltips.delete')}>
                            <Popconfirm
                                title={Lang.get('pages.projects.index.table.delete')}
                                onConfirm={deleteProjectHandler(record.id)}
                                okText={Lang.get('tooltips.popconfirm.yes')}
                                cancelText={Lang.get('tooltips.popconfirm.no')}
                            >
                                <Button icon={<DeleteOutlined/>}/>
                            </Popconfirm>
                        </Tooltip>
                    </Space>
                )
            }
        },
    ]

    // HANDLERS
    const deleteProjectHandler = id => () => {
        Inertia.delete(tRoute('projects.destroy', id))
    }

    const showProjectHandler = id => () => {
        Inertia.get(tRoute('projects.show', id))
    }

    // RENDER
    return (
        <Container
            title={Lang.get('pages.projects.title')}
            mainActionRoute={route('projects.create')}
            mainActionButtonLabel={Lang.get('pages.projects.index.button')}
            buttons={[<SecondaryButton onClick={() => {
                Inertia.get(tRoute('selections.dashboard', -1))
            }}>
                {Lang.get('pages.projects.index.selection')}
            </SecondaryButton>]}
        >
            <TTable
                columns={columns}
                dataSource={projects}
                showHandler={showProjectHandler}
            />
        </Container>
    )
}

Index.layout = page => <AuthLayout children={page}/>

export default Index
