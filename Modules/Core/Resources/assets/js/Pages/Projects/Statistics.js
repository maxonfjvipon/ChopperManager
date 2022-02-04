import React, {useEffect, useState} from 'react'
import Lang from "../../../../../../../resources/js/translation/lang";
import {SearchInput} from "../../../../../../../resources/js/src/Shared/SearchInput";
import {Form, Input, Table, Tooltip} from "antd";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useDate} from "../../../../../../../resources/js/src/Hooks/date.hook";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Edit} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {Inertia} from "@inertiajs/inertia";
import {Save} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Save";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";

const EditableCell = ({
                          editing,
                          dataIndex,
                          title,
                          record,
                          index,
                          children,
                          options,
                          ...restProps
                      }) => {
    const inputNode = dataIndex === 'comment'
        ? <Input.TextArea autoSize={{maxRows: 6}}/>
        : <Selection options={options}/>;
    const {rules} = useInputRules()
    return (
        <td {...restProps}>
            {editing ? (
                <Form.Item
                    name={dataIndex}
                    style={{margin: 0}}
                    rules={dataIndex !== 'comment' && [rules.required]}
                >
                    {inputNode}
                </Form.Item>
            ) : (
                children
            )}
        </td>
    );
};

export default function Statistics() {
    // HOOKS
    const tRoute = useTransRoutes()
    const {projects, filter_data, project_statuses, delivery_statuses} = usePage().props
    const {has, filterPermissionsArray} = usePermissions()
    const {compareDate} = useDate()

    // STATE
    const [projectsToShow, setProjectsToShow] = useState(projects)
    const [editingKey, setEditingKey] = useState('');

    // CONSTS
    const searchId = 'project-search-input'
    const [form] = Form.useForm()
    const columns = [
        {
            title: Lang.get('pages.projects.statistics.table.created_at'),
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
            defaultSortOrder: ['ascend'],
            editable: false,
        },
        {
            title: Lang.get('pages.projects.statistics.table.client'),
            dataIndex: 'user',
            sorter: (a, b) => a.user.localeCompare(b.name),
            editable: false,
        },
        {
            title: Lang.get('pages.projects.statistics.table.name'),
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            sorter: (a, b) => a.name.localeCompare(b.name),
            editable: false,
        },
        {
            title: Lang.get('pages.projects.statistics.table.selections_count'),
            dataIndex: 'selections_count',
            sorter: (a, b) => a.selections_count - b.selections_count,
            editable: false,
        },
        {
            title: Lang.get('pages.projects.statistics.table.retail_price'),
            dataIndex: 'price',
            sorter: (a, b) => a.price - b.price,
            editable: false,
        },
        {
            title: Lang.get('pages.projects.statistics.table.status'),
            dataIndex: 'status_id',
            filters: filter_data.project_statuses,
            onFilter: (status_id, record) => status_id === record.status_id,
            editable: true,
            options: project_statuses,
            render: (_, record) => selection(project_statuses, record.status_id)
        },
        {
            title: Lang.get('pages.projects.statistics.table.delivery_status'),
            dataIndex: 'delivery_status_id',
            filters: filter_data.delivery_statuses,
            onFilter: (status_id, record) => record.delivery_status_id === status_id,
            editable: true,
            options: delivery_statuses,
            render: (_, record) => selection(delivery_statuses, record.delivery_status_id)
        },
        {
            title: Lang.get('pages.projects.statistics.table.comment'),
            dataIndex: 'comment',
            editable: true,
            render: (_, record) => <Input.TextArea
                readOnly
                bordered={false}
                value={record.comment}
                autoSize={{maxRows: 3}}
            />
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                const editing = isEditing(record)
                return (
                    <TableActionsContainer>
                        {(has('project_show')
                            && !editing) && <View clickHandler={showProjectHandler(record.id)}/>}
                        {has('project_edit') && editing
                            ? <Save clickHandler={saveProjectHandler(record)}/>
                            : <Edit clickHandler={editProjectHandler(record)}/>
                        }
                    </TableActionsContainer>
                )
            }
        },
    ]

    // HANDLERS
    const mergedColumns = columns.map((col) => {
        if (!col.editable) {
            return col;
        }
        return {
            ...col,
            onCell: (record) => ({
                record,
                dataIndex: col.dataIndex,
                title: col.title,
                editing: isEditing(record),
                options: col.options
            }),
        };
    });

    const selection = (options, value) => <Selection
        options={options}
        value={value}
        open={false}
        bordered={false}
        showArrow={false}
        showSearch={false}
        allowClear={false}
    />

    const isEditing = (record) => record.key === editingKey

    const editProjectHandler = record => () => {
        form.setFieldsValue({...record})
        setEditingKey(record.key)
    }

    const saveProjectHandler = record => async () => {
        const updated = await form.validateFields()
        Inertia.put(tRoute('projects.update', record.id), updated, {
            preserveScroll: true,
            preserveState: true,
            only: ['projects']
        })
        setEditingKey('')
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
            <Form form={form}>
                <Table
                    components={{
                        body: {
                            cell: EditableCell,
                        },
                    }}
                    rowClassName="editable-row"
                    dataSource={projectsToShow}
                    columns={mergedColumns}
                    size="small"
                />
            </Form>
        </IndexContainer>
    )
}
