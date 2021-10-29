import React from 'react'
import {Link, usePage} from "@inertiajs/inertia-react";
import {PrimaryAction} from "../../../Shared/Resource/Actions/PrimaryAction";
import {TTable} from "../../../Shared/Resource/Table/TTable";
import {IndexContainer} from "../../../Shared/Resource/Containers/IndexContainer";
import {Tag} from "antd";
import {View} from "../../../Shared/Resource/Table/Actions/View";
import {Toggle} from "../../../Shared/ResourcePanel/Index/Table/ColumnsActions/Toggle";
import {Inertia} from "@inertiajs/inertia";
import {AuthLayout} from "../../../Shared/Layout/AdminPanel/AuthLayout";
import {TableActionsContainer} from "../../../Shared/Resource/Table/Actions/TableActionsContainer";

const Index = () => {
    const {tenants} = usePage().props

    const columns = [
        {title: 'ID', dataIndex: 'id'},
        {title: 'Name', dataIndex: 'name'},
        {
            title: 'Domain',
            dataIndex: 'domain',
            render: (_, record) =>
                <Link href={"http://" + record.domain + ":8000/"}>{record.domain}</Link>
        },
        {title: 'Database', dataIndex: 'database'},
        {title: 'Type', dataIndex: 'type', render: (_, record) => record.type.name},
        {
            title: 'Is active', dataIndex: 'is_active', render: (_, record) =>
                <Tag color={record.is_active ? "success" : 'error'}>
                    {record.is_active ? "Active" : "Disabled"}
                </Tag>
        },
        {title: 'Created at', dataIndex: 'created_at'},
        {title: 'Updated at', dataIndex: 'updated_at'},
        {
            key: 'actions', render: (_, record) => <TableActionsContainer>
                <View clickHandler={showHandler(record.id)}/>
                <Toggle clickHandler={toggleHandler(record)} enabled={record.is_active}/>
            </TableActionsContainer>,
            width: '1%'
        }
    ]

    const toggleHandler = tenant => () => {
        Inertia.put(route('admin.tenants.update', tenant.id), {
            ...tenant,
            is_active: !Boolean(tenant.is_active)
        }, {
            preserveScroll: true,
        })
    }

    const showHandler = id => () => {
        Inertia.get(route('admin.tenants.show', id))
    }

    return (
        <IndexContainer
            title="Tenants"
            actions={<PrimaryAction
                label="Create tenant"
                route={route('admin.tenants.create')}
            />}
        >
            <TTable
                columns={columns}
                dataSource={tenants}
            />
        </IndexContainer>
    )
}

Index.layout = page => <AuthLayout children={page}/>

export default Index
