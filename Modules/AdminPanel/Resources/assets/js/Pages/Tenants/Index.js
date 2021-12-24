import React from 'react'
import {Link, usePage} from "@inertiajs/inertia-react";
import {Switch, Tag} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
// import {Toggle} from "../../../../../../../resources/js/src/Shared/ResourcePanel/Index/Table/ColumnsActions/Toggle";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {PrimaryAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/PrimaryAction";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {AuthLayout} from "../../Shared/Layout/AuthLayout";

const Index = () => {
    const {tenants} = usePage().props

    // console.log(tenants)

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
        {
            title: 'Has registration', dataIndex: 'has_registration', render: (_, record) =>
                <Tag color={record.has_registration ? "success" : 'error'}>
                    {record.has_registration ? "Has registration" : "No registration"}
                </Tag>
        },
        {title: 'Created at', dataIndex: 'created_at'},
        {title: 'Updated at', dataIndex: 'updated_at'},
        {
            key: 'actions', render: (_, record) => <TableActionsContainer>
                <View clickHandler={showHandler(record.id)}/>
                {/*<Toggle*/}
                {/*    clickHandler={toggleHandler(record)}*/}
                {/*    enabled={record.is_active}*/}
                {/*/>*/}
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
