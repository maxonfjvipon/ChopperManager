import React, {useEffect, useState} from 'react';
import {Button, Col, Form, Input, message, Popconfirm, Row, Space, Table, Tooltip} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-react";
import {Authenticated} from "../../Shared/Layout/Authenticated";
import {BoxFlexEnd} from "../../Shared/Box/BoxFlexEnd";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";
import {DeleteOutlined, EditOutlined} from "@ant-design/icons";
import {SecondaryButton} from "../../Shared/Buttons/SecondaryButton";
import Modal from "antd/es/modal/Modal";
import {useInputRules} from "../../Hooks/input-rules.hook";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";

const Index = () => {
    // STATE
    const [isModalVisible, setIsModalVisible] = useState(false)

    // HOOKS
    const {rules} = useInputRules()

    // CONSTS
    const {projects} = usePage().props

    const columns = [
        {title: 'Дата создания', dataIndex: 'created_at', key: 'created_at'},
        {
            title: 'Наименование',
            dataIndex: 'name',
            key: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            width: '80%'
        },
        {title: 'Количество подборов', dataIndex: 'selections_count', key: 'selections_count'},
        {
            title: '', dataIndex: 'actions', key: 'actions', render: (_, record) => {
                return (
                    <Space size={'small'}>
                        <Tooltip placement="topRight" title={"Просмотреть"}>
                            <Button
                                onClick={showProjectHandler(record.id)}
                                icon={<EditOutlined/>}
                            />
                        </Tooltip>
                        <Tooltip placement="topRight" title={"Удалить"}>
                            <Popconfirm
                                title={'Вы точно хотите удалить проект?'}
                                onConfirm={deleteProjectHandler(record.id)}
                                okText={'Да'}
                                cancelText={'Нет'}
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
        Inertia.delete(route('projects.destroy', id))
    }

    const showProjectHandler = id => () => {
        Inertia.get(route('projects.show', id))
    }

    const createNewProjectHandler = body => {
        setIsModalVisible(false)
        Inertia.post(route('projects.store', body))
    }

    return (
        <>
            <Row justify="space-around" gutter={[0, 10]}>
                <Col xs={24}>
                    <BoxFlexEnd>
                        <PrimaryButton onClick={() => {
                            setIsModalVisible(true)
                        }}>
                            Новый проект
                        </PrimaryButton>
                    </BoxFlexEnd>
                </Col>
                <Col xs={24}>
                    <Table
                        size="small"
                        columns={columns}
                        dataSource={projects.map(project => {
                            return {
                                key: project.id,
                                ...project,
                            }
                        })}
                        onRow={(record, _) => {
                            return {
                                onDoubleClick: showProjectHandler(record.id)
                            }
                        }}
                    />
                </Col>
                <Col span={24}>
                    <SecondaryButton onClick={() => {
                        Inertia.get(route('selections.dashboard', -1))
                    }}>
                        Произвести подбор без возможности сохранения
                    </SecondaryButton>
                </Col>
            </Row>
            <Modal
                title="Введите наименование нового проекта"
                visible={isModalVisible}
                footer={[
                    <Button key="cancel" onClick={() => {
                        setIsModalVisible(false)
                    }}>
                        Отмена
                    </Button>,
                    <Button key="create" type="primary" htmlType="submit" form="new-proj-form">
                        Создать
                    </Button>,
                ]}
                onCancel={() => {
                    setIsModalVisible(false)
                }}
            >
                <Form name="new-proj-form" onFinish={createNewProjectHandler} layout="vertical">
                    <Form.Item name='name' rules={[rules.required]} label="Наименование">
                        <Input placeholder="Введите наименование..."/>
                    </Form.Item>
                </Form>
            </Modal>
        </>
    )
}

Index.layout = page => <Authenticated children={page} title={"Проекты"} backTo={true}
                                      breadcrumbs={useBreadcrumbs().projects}/>

export default Index
