import {Authenticated} from "../../Shared/Layout/Authenticated";
import {Button, Col, Form, Input, message, Popconfirm, Row, Space, Table, Tag, Tooltip, Upload} from "antd";
import {useForm, usePage} from "@inertiajs/inertia-react";
import {BoxFlexEnd} from "../../Shared/Box/BoxFlexEnd";
import {DeleteOutlined, EditOutlined, UploadOutlined} from "@ant-design/icons";
import {Inertia} from "@inertiajs/inertia";
import React, {useEffect, useRef} from "react";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {FileUploader} from "../../Shared/Buttons/FileUploader";
import Lang from "../../../translation/lang";
import {useLang} from "../../Hooks/lang.hook";
import {Common} from "../../Shared/Layout/Common";

const Index = () => {
    const {auth, pumps} = usePage().props
    const Lang = useLang()


    const columns = [
        {
            title: Lang.get('pages.pumps.data.part_num_main'),
            dataIndex: 'part_num_main',
            key: 'part_num_main',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.part_num_backup'),
            dataIndex: 'part_num_backup',
            key: 'part_num_backup',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.part_num_archive'),
            dataIndex: 'part_num_archive',
            key: 'part_num_archive',
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.producer'),
            dataIndex: 'producer',
            key: 'producer',
            width: 140,
            render: producer => producer.name,
        },
        {
            title: Lang.get('pages.pumps.data.series'),
            dataIndex: 'series',
            key: 'series',
            render: series => series.name,
            width: 130
        },
        {
            title: Lang.get('pages.pumps.data.name'),
            dataIndex: 'name',
            key: 'name',
            width: 220
        },
        {
            title: Lang.get('pages.pumps.data.category'),
            dataIndex: 'category',
            key: 'category',
            render: category => category.name,
            width: 130
        },
        {title: Lang.get('pages.pumps.data.price'), dataIndex: 'price', key: 'price', width: 140},
        {
            title: Lang.get('pages.pumps.data.currency'),
            dataIndex: 'currency',
            key: 'currency',
            render: currency => currency.name,
            width: 70
        },
        {title: Lang.get('pages.pumps.data.weight'), dataIndex: 'weight', key: 'weight', width: 90},
        {title: Lang.get('pages.pumps.data.power'), dataIndex: 'power', key: 'power', width: 95},
        {title: Lang.get('pages.pumps.data.amperage'), dataIndex: 'amperage', key: 'amperage', width: 70},
        {
            title: Lang.get('pages.pumps.data.connection_type'),
            dataIndex: 'connection_type',
            key: 'connection_type',
            render: connectionType => connectionType.name,
            width: 120
        },
        {
            title: Lang.get('pages.pumps.data.min_fluid_temp'),
            dataIndex: 'min_liquid_temp',
            key: 'min_liquid_temp',
            width: 160
        },
        {
            title: Lang.get('pages.pumps.data.max_fluid_temp'),
            dataIndex: 'max_liquid_temp',
            key: 'max_liquid_temp',
            width: 170
        },
        {
            title: Lang.get('pages.pumps.data.between_axes_dist'),
            dataIndex: 'between_axes_dist',
            key: 'between_axes_dist',
            width: 150
        },
        {
            title: Lang.get('pages.pumps.data.dn_input'),
            dataIndex: 'dn_input',
            key: 'dn_input',
            render: dn => dn.value,
            width: 70
        },
        {
            title: Lang.get('pages.pumps.data.dn_output'),
            dataIndex: 'dn_output',
            key: 'dn_output',
            render: dn => dn.value,
            width: 70
        },
        {
            title: Lang.get('pages.pumps.data.regulation'),
            dataIndex: 'regulation',
            key: 'regulation',
            render: regulation => regulation.name,
            width: 140
        },
        {
            title: Lang.get('pages.pumps.data.phase'),
            dataIndex: 'phase',
            key: 'phase',
            render: phase => phase.value + "(" + phase.voltage + ")",
            width: 90
        },
        {
            title: Lang.get('pages.pumps.data.types'),
            dataIndex: 'types',
            key: 'types',
            width: 360,
            render: types => (
                <Space size={[0, 5]} wrap>
                    {types.map(type => <Tag color="green" key={type.name}>{type.name}</Tag>)}
                </Space>
            )
        },
        {
            title: Lang.get('pages.pumps.data.applications'),
            dataIndex: 'applications',
            key: 'applications',
            width: 450,
            render: applications => (
                <Space size={[0, 5]} wrap>
                    {applications.map(application => <Tag color="blue" key={application.name}>{application.name}</Tag>)}
                </Space>
            )
        },
        {
            title: '', dataIndex: 'actions', key: 'actions', width: 50, render: (_, record) => {
                return (
                    <Tooltip placement="topRight" title={Lang.get('tooltips.view')}>
                        <Button
                            onClick={showPumpClickHandler(record.id)}
                            icon={<EditOutlined/>}
                        />
                    </Tooltip>
                )
            }
        }
    ]

    const showPumpClickHandler = id => () => {
        Inertia.get(route('pumps.show', id))
    }

    return (
        <Row gutter={[0, 10]}>
            {auth.isAdmin && <Col xs={24}>
                <BoxFlexEnd>
                    <FileUploader route={route('pumps.import')} title={Lang.get('pages.pumps.upload')}/>
                </BoxFlexEnd>
            </Col>}
            <Col xs={24}>
                <Table
                    size="middle"
                    columns={columns}
                    dataSource={pumps.map(pump => {
                        return {key: pump.id, ...pump}
                    })}
                    onRow={(record, _) => {
                        return {onDoubleClick: showPumpClickHandler(record.id)}
                    }}
                    scroll={{x: 3700, y: 800}}
                />
            </Col>
        </Row>
    )
}

Index.layout = page =>
    <Common children={page} backTo={true} title={Lang.get('pages.pumps.title')} breadcrumbs={useBreadcrumbs().pumps}/>

export default Index
