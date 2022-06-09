import React, {useEffect, useState} from 'react'
import Lang from "../../../../../../resources/js/translation/lang";
import {Col, DatePicker, Divider, Form, Input, Row, Table, Tooltip} from "antd";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../resources/js/src/Hooks/permissions.hook";
import {useDate} from "../../../../../../resources/js/src/Hooks/date.hook";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {View} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/View";
import {Edit} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {Inertia} from "@inertiajs/inertia";
import {Save} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Save";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {useInputRules} from "../../../../../../resources/js/src/Hooks/input-rules.hook";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import moment from "moment";
import {SecondaryButton} from "../../../../../../resources/js/src/Shared/Buttons/SecondaryButton";
import {InputNum} from "../../../../../../resources/js/src/Shared/Inputs/InputNum";



export default function Statistics() {
    // HOOKS
    const tRoute = useTransRoutes()
    const {projects, filter_data, project_statuses, delivery_statuses, auth} = usePage().props
    const {has} = usePermissions()
    const {compareDate} = useDate()
    const {reducedAntFormItemClassName, margin, fullWidth} = useStyles()

    // STATE
    const [_projects, _setProjects] = useState(projects)
    const [projectsToShow, setProjectsToShow] = useState(projects)
    const [editingKey, setEditingKey] = useState('');

    // CONSTS
    const searchId = 'project-search-input'
    const [form] = Form.useForm()
    const [filtersForm] = Form.useForm()
    const columns = [
        {
            title: Lang.get('pages.statistics.projects.table.created_at'),
            dataIndex: 'created_at',
            sorter: (a, b) => compareDate(a.created_at, b.created_at),
            defaultSortOrder: ['ascend'],
            editable: false,
        },
        {
            title: Lang.get('pages.statistics.projects.table.name'),
            dataIndex: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            sorter: (a, b) => a.name.localeCompare(b.name),
            editable: false,
        },
        {
            title: Lang.get('pages.statistics.projects.table.user_organization_name'),
            dataIndex: 'user_organization_name',
            editable: false,
            filters: filter_data.organizations,
            onFilter: (organization, record) => organization === record.user_organization_name
        },
        {
            title: Lang.get('pages.statistics.projects.table.user_full_name'),
            dataIndex: 'user_full_name',
            sorter: (a, b) => a.user_full_name.localeCompare(b.user_full_name),
            editable: false,
        },
        {
            title: Lang.get('pages.statistics.projects.table.user_business'),
            dataIndex: 'user_business',
            filters: filter_data.businesses,
            onFilter: (business, record) => business === record.user_business,
        },
        {
            title: Lang.get('pages.statistics.projects.table.country'),
            dataIndex: 'country',
            filters: filter_data.countries,
            onFilter: (country, record) => country === record.country,
        },
        {
            title: Lang.get('pages.statistics.projects.table.city'),
            dataIndex: 'city',
            sorter: (a, b) => a.city.localeCompare(b.city),
            filters: filter_data.cities,
            onFilter: (city, record) => city === record.city
        },
        {
            title: Lang.get('pages.statistics.projects.table.selections_count'),
            dataIndex: 'selections_count',
            sorter: (a, b) => a.selections_count - b.selections_count,
            editable: false,
        },
        {
            title: Lang.get('pages.statistics.projects.table.retail_price') + ", " + auth.currency,
            dataIndex: 'price',
            render: (_, record) => record.price.toLocaleString(),
            sorter: (a, b) => a.price - b.price,
            editable: false,
        },
        {
            title: Lang.get('pages.statistics.projects.table.status'),
            dataIndex: 'status_id',
            filters: filter_data.project_statuses,
            onFilter: (status_id, record) => status_id === record.status_id,
            editable: true,
            options: project_statuses,
            render: (_, record) => selection(project_statuses, record.status_id)
        },
        {
            title: Lang.get('pages.statistics.projects.table.delivery_status'),
            dataIndex: 'delivery_status_id',
            filters: filter_data.delivery_statuses,
            onFilter: (status_id, record) => record.delivery_status_id === status_id,
            editable: true,
            options: delivery_statuses,
            render: (_, record) => selection(delivery_statuses, record.delivery_status_id)
        },
        {
            title: Lang.get('pages.statistics.projects.table.comment'),
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
        let projs = [..._projects]
        const index = projs.findIndex(p => p.id === record.id)
        projs.splice(index, 1, {...projs[index], ...updated})
        Inertia.put(tRoute('projects.update', record.id), updated, {
            preserveScroll: true,
            preserveState: true,
            only: ['delivery_statuses']
        })
        _setProjects([...projs])
        setEditingKey('')
    }

    const showProjectHandler = id => () => {
        Inertia.get(tRoute('projects.show', id))
    }

    const filterProjectsHandler = async () => {
        const data = await filtersForm.validateFields()
        setProjectsToShow(_projects.filter(project => {
                if (!(!!!data.search) && data.search !== ""
                    && !(project.name + project.user_organization_name).toLowerCase().includes(data.search.toLowerCase())) {
                    return false
                }

                if (!(!!!data.selections_count || !!!data.selections_count_condition)) {
                    if (data.selections_count_condition === ">=") {
                        if (project.selections_count < data.selections_count) {
                            return false
                        }
                    } else if (project.selections_count >= data.selections_count) {
                        return false
                    }
                }

                if (!(!!!data.selections_price || !!!data.selections_price_condition)) {
                    if (data.selections_price_condition === ">=") {
                        if (project.price < data.selections_price) {
                            return false
                        }
                    } else if (project.price >= data.selections_price) {
                        return false
                    }
                }

                if (!(!!!data.created_at || !!!data.created_at[0])) {
                    if (moment(project.created_at, "DD.MM.YYYY").isBefore(data.created_at[0])) {
                        return false
                    }
                }

                if (!(!!!data.created_at || !!!data.created_at[1])) {
                    if (moment(project.created_at, "DD.MM.YYYY").isAfter(data.created_at[1])) {
                        return false
                    }
                }
                return true
            }
        ))
    }

    // EFFECTS
    useEffect(() => {
        filterProjectsHandler()
    }, [_projects])

    return (
        <IndexContainer
            title={Lang.get('pages.statistics.projects.full_title')}
        >
            <Form layout="vertical" form={filtersForm}>
                <Row gutter={10}>
                    <Col xs={4}>
                        <Form.Item className={reducedAntFormItemClassName}
                                   name="search"
                                   label={Lang.get('pages.statistics.projects.filters.search')}
                        >
                            <Input
                                allowClear
                                placeholder={Lang.get('pages.statistics.projects.filters.search')}
                                onPressEnter={filterProjectsHandler}
                            />
                        </Form.Item>
                    </Col>
                    <Col xs={3}>
                        <Form.Item className={reducedAntFormItemClassName}
                                   name='created_at'
                                   label={Lang.get('pages.statistics.projects.filters.created_at')}
                        >
                            <DatePicker.RangePicker style={fullWidth} allowEmpty={[true, true]}/>
                        </Form.Item>
                    </Col>
                    <Col xs={3}>
                        <Row gutter={[10, 0]}>
                            <Col xs={24}>
                                {Lang.get('pages.statistics.projects.filters.selections_count')}
                            </Col>
                            <Col xs={12}>
                                <Form.Item name='selections_count_condition' className={reducedAntFormItemClassName}>
                                    <Selection
                                        style={fullWidth}
                                        options={[{id: ">=", value: ">="}, {id: "<", value: "<"}]}
                                        allowClear
                                        placeholder={Lang.get('pages.statistics.projects.filters.condition')}
                                        onClear={() => {
                                            filtersForm.setFieldsValue({
                                                ...filtersForm,
                                                selections_count_condition: null,
                                                selections_count: null,
                                            })
                                        }}
                                    />
                                </Form.Item>
                            </Col>
                            <Col xs={12}>
                                <Form.Item name='selections_count' className={reducedAntFormItemClassName}>
                                    <InputNum
                                        min={0}
                                        style={fullWidth}
                                        placeholder={Lang.get('pages.statistics.projects.filters.selections_count')}
                                    />
                                </Form.Item>
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={3}>
                        <Row gutter={[10, 0]}>
                            <Col xs={24}>
                                {Lang.get('pages.statistics.projects.filters.total_selections_price')}
                            </Col>
                            <Col xs={12}>
                                <Form.Item name='selections_price_condition' className={reducedAntFormItemClassName}>
                                    <Selection
                                        style={fullWidth}
                                        options={[{id: ">=", value: ">="}, {id: "<", value: "<"}]}
                                        allowClear
                                        placeholder={Lang.get('pages.statistics.projects.filters.condition')}
                                        onClear={() => {
                                            filtersForm.setFieldsValue({
                                                ...filtersForm,
                                                selections_price_condition: null,
                                                selections_price: null,
                                            })
                                        }}
                                    />
                                </Form.Item>
                            </Col>
                            <Col xs={12}>
                                <Form.Item name='selections_price' className={reducedAntFormItemClassName}>
                                    <InputNum
                                        min={0}
                                        style={fullWidth}
                                        placeholder={Lang.get('pages.statistics.projects.filters.total_selections_price')}
                                    />
                                </Form.Item>
                            </Col>
                        </Row>
                    </Col>
                    <Col xs={2}>
                        <Form.Item name='apply' className={reducedAntFormItemClassName}>
                            <PrimaryButton style={{...fullWidth, ...margin.top(20)}} onClick={filterProjectsHandler}>
                                {Lang.get('pages.statistics.projects.filters.apply')}
                            </PrimaryButton>
                        </Form.Item>
                    </Col>
                    <Col xs={2}>
                        <Form.Item name='clear' className={reducedAntFormItemClassName}>
                            <SecondaryButton style={{...fullWidth, ...margin.top(20)}} onClick={() => {
                                filtersForm.resetFields()
                                setProjectsToShow(_projects)
                            }}>
                                {Lang.get('pages.statistics.projects.filters.reset')}
                            </SecondaryButton>
                        </Form.Item>
                    </Col>
                </Row>
            </Form>
            <Divider style={margin.all("5px 0 5px")}/>
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
