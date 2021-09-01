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
import {Common} from "../../Shared/Layout/Common";
import Lang from "../../../translation/lang";
import {useLang} from "../../Hooks/lang.hook";

const Index = () => {
    // STATE
    const [isModalVisible, setIsModalVisible] = useState(false)

    // HOOKS
    const {rules} = useInputRules()
    const Lang = useLang()

    // CONSTS
    const {projects} = usePage().props

    const columns = [
        {title: Lang.get('pages.projects.index.table.created_at'), dataIndex: 'created_at', key: 'created_at'},
        {
            title: Lang.get('pages.projects.index.table.name'),
            dataIndex: 'name',
            key: 'name',
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            width: '80%'
        },
        {title: Lang.get('pages.projects.index.table.count'), dataIndex: 'selections_count', key: 'selections_count'},
        {
            title: '', dataIndex: 'actions', key: 'actions', render: (_, record) => {
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
                            {Lang.get('pages.projects.index.new.button')}
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
                        {Lang.get('pages.projects.index.selection')}
                    </SecondaryButton>
                </Col>
            </Row>
            <Modal
                title={Lang.get('pages.projects.index.new.title')}
                visible={isModalVisible}
                footer={[
                    <Button key="cancel" onClick={() => {
                        setIsModalVisible(false)
                    }}>
                        {Lang.get('pages.projects.index.new.cancel')}
                    </Button>,
                    <Button key="create" type="primary" htmlType="submit" form="new-proj-form">
                        {Lang.get('pages.projects.index.new.create')}
                    </Button>,
                ]}
                onCancel={() => {
                    setIsModalVisible(false)
                }}
            >
                <Form name="new-proj-form" onFinish={createNewProjectHandler} layout="vertical">
                    <Form.Item name='name' rules={[rules.required]} label={Lang.get('pages.projects.index.new.label')}>
                        <Input placeholder={Lang.get('pages.projects.index.new.placeholder')}/>
                    </Form.Item>
                </Form>
            </Modal>
        </>
    )
}

Index.layout = page => <Common children={page} title={Lang.get('pages.projects.title')} backTo={true}
                                      breadcrumbs={useBreadcrumbs().projects}/>

export default Index
