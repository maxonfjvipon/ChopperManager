import React from 'react';
import {Tooltip} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {AuthLayout} from "../../../../../../../resources/js/src/Shared/Layout/AuthLayout";
import Lang from "../../../../../../../resources/js/translation/lang";

const Index = () => {
    // HOOKS
    const {users} = usePage().props

    // console.log(users)

    // CONSTS
    const columns = [
        {title: Lang.get('pages.users.index.table.created_at'), dataIndex: 'created_at'},
        {
            title: Lang.get('pages.users.index.table.organization_name'),
            dataIndex: 'organization_name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
        },
        {
            title: Lang.get('pages.users.index.table.main_business'),
            dataIndex: 'business',
            render: (_, record) => record.business.name,
        },
        {title: Lang.get('pages.users.index.table.email'), dataIndex: 'email'},
        {
            title: Lang.get('pages.users.index.table.full_name'), dataIndex: 'full_name',
            render: (_, record) => `${record.first_name} ${record.middle_name}`
        },
        {title: Lang.get('pages.users.index.table.country'), dataIndex: 'country', render: (_, record) => record.country.name},
        {title: Lang.get('pages.users.index.table.city'), dataIndex: 'city'},
        {title: Lang.get('pages.users.index.table.postcode'), dataIndex: 'postcode'},
    ]

    // RENDER
    return (
        <IndexContainer title={Lang.get('pages.users.title')}>
            <TTable
                columns={columns}
                dataSource={users}
            />
        </IndexContainer>
    )
}

Index.layout = page => <AuthLayout children={page}/>

export default Index
