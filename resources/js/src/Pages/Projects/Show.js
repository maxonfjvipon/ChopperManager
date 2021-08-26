import React, {useEffect} from 'react'
import {Authenticated} from "../../Shared/Layout/Authenticated";
import {Link, usePage} from "@inertiajs/inertia-react";
import {Button, Col, Form, Input, message, Popconfirm, Row, Space, Table, Tooltip} from "antd";
import {BoxFlexEnd} from "../../Shared/Box/BoxFlexEnd";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {SecondaryButton} from "../../Shared/Buttons/SecondaryButton";
import {DeleteOutlined, EditOutlined, ExportOutlined, PrinterOutlined} from "@ant-design/icons";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {Inertia} from "@inertiajs/inertia";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {useStyles} from "../../Hooks/styles.hook";

const Show = () => {
    const {rules} = useInputRules()
    const {project} = usePage().props

    // useEffect(() => {
    //     console.log(project)
    // }, [project])


    const {reducedBottomAntFormItemClassName} = useStyles()

    const formName = 'project-name-form'
    const columns = [
        {
            title: 'Дата подбора',
            dataIndex: 'created_at',
            key: 'created_at',
            sorter: (a, b) => new Date(a.created_at) - new Date(b.created_at),
            defaultSortOrder: 'ascend'
        },
        {title: 'Подобранный насос', dataIndex: 'selected_pump_name', key: 'selected_pump_name'},
        {
            title: 'Артикул', dataIndex: 'part_num_main', key: 'pump_part_num_main', render: (_, record) => {
                console.log(record)
                return (
                    <Link href={route('pumps.show', record.id)}>{record.part_num_main}</Link>
                )
            }
        },
        {title: 'Напор, м', dataIndex: 'pressure', key: 'pressure'},
        {title: 'Расход, м3/ч', dataIndex: 'consumption', key: 'consumption'},
        {title: 'Цена за 1', dataIndex: 'price', key: 'price', sorter: (a, b) => a.price - b.price},
        {title: 'Цена ИТОГО', dataIndex: 'sum_price', key: 'priceSum', sorter: (a, b) => a.sum_price - b.sum_price},
        {title: 'P одного', dataIndex: 'power', key: 'power', sorter: (a, b) => a.power - b.power},
        {title: 'P ИТОГО', dataIndex: 'sum_power', key: 'powerSum', sorter: (a, b) => a.sum_power - b.sum_power},
        {
            title: '', dataIndex: 'actions', key: 'actions', width: '5%', render: (_, record) => {
                return (
                    <BoxFlexEnd>
                        <Space size={'small'}>
                            <Tooltip placement="topRight" title={"Просмотреть"}>
                                <Button
                                    onClick={showSelectionHandler(record.id)}
                                    icon={<EditOutlined/>}
                                />
                            </Tooltip>
                            <Tooltip placement="topRight" title={"Удалить"}>
                                <Popconfirm
                                    title={'Вы точно хотите удалить подбор?'}
                                    onConfirm={deleteSelectionHandler(record.id)}
                                    okText={'Да'}
                                    cancelText={'Нет'}
                                >
                                    <Button icon={<DeleteOutlined/>}/>
                                </Popconfirm>
                            </Tooltip>
                            <Tooltip placement="topRight" title="Печать">
                                <Button icon={<PrinterOutlined/>} onClick={() => {
                                    message.info('Функция в разработке')
                                }}/>
                            </Tooltip>
                            <Tooltip placement="topRight" title="Экспорт">
                                <Button icon={<ExportOutlined/>} onClick={() => {
                                    message.info('Функция в разработке')
                                }}/>
                            </Tooltip>
                        </Space>
                    </BoxFlexEnd>
                )
            }
        },
    ]

    const showSelectionHandler = id => () => {
        console.log('show', id)
        Inertia.get(route('selections.show', id))
    }

    const updateProjectHandler = body => {
        Inertia.put(route('projects.update', project.data.id), body)
    }

    const deleteSelectionHandler = id => () => {
        Inertia.delete(route('selections.destroy', id))
    }

    return (
        <Row justify="space-around" gutter={[0, 10]}>
            <Col xs={24}>
                <Form name={formName} layout="vertical" onFinish={updateProjectHandler}>
                    <Form.Item
                        rules={[rules.required]}
                        label="Наименование"
                        name="name"
                        initialValue={project.data?.name}
                        className={reducedBottomAntFormItemClassName}
                    >
                        <Input/>
                    </Form.Item>
                </Form>
            </Col>
            <Col xs={24}>
                <BoxFlexEnd>
                    <PrimaryButton form={formName} htmlType="submit">
                        Сохранить изменения
                    </PrimaryButton>
                </BoxFlexEnd>
            </Col>
            <Col xs={24}>
                <Table scroll={{x: 1600}} size="small" columns={columns}
                       dataSource={project.data?.selections.map(selection => {
                           return {
                               key: selection.id,
                               ...selection
                           }
                       })}
                       onRow={(record, _) => {
                           return {
                               onDoubleClick: showSelectionHandler(record.id)
                           }
                       }}
                />
            </Col>
            <Col xs={24}>
                <SecondaryButton onClick={() => {
                    Inertia.get(route('selections.dashboard', project.data.id))
                }}>
                    Произвести подбор
                </SecondaryButton>
            </Col>
        </Row>
    )
}

Show.layout = page => <Authenticated children={page} backTo={true} title={"Проекты"}
                                     breadcrumbs={useBreadcrumbs().projects}
/>

export default Show
