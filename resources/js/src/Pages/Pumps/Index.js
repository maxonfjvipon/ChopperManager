import {Authenticated} from "../../Shared/Layout/Authenticated";
import {Button, Col, Form, Input, message, Popconfirm, Row, Space, Table, Tag, Tooltip, Upload} from "antd";
import {useForm, usePage} from "@inertiajs/inertia-react";
import {BoxFlexEnd} from "../../Shared/Box/BoxFlexEnd";
import {DeleteOutlined, EditOutlined, UploadOutlined} from "@ant-design/icons";
import {Inertia} from "@inertiajs/inertia";
import React, {useEffect, useRef} from "react";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {FileUploader} from "../../Shared/Buttons/FileUploader";

const Index = () => {
    const {auth, pumps} = usePage().props

    const columns = [
        {title: 'Артикул основной', dataIndex: 'part_num_main', key: 'part_num_main', width: 120},
        {title: 'Артикул резервный', dataIndex: 'part_num_backup', key: 'part_num_backup', width: 120},
        {title: 'Артикул архивный', dataIndex: 'part_num_archive', key: 'part_num_archive', width: 120},
        {
            title: 'Производитель',
            dataIndex: 'producer',
            key: 'producer',
            width: 140,
            render: producer => producer.name,
        },
        {title: 'Серия', dataIndex: 'series', key: 'series', render: series => series.name, width: 130},
        {
            title: 'Наименование',
            dataIndex: 'name',
            key: 'name',
            width: 220
        },
        {title: 'Категория', dataIndex: 'category', key: 'category', render: category => category.name, width: 130},
        {title: 'Цена', dataIndex: 'price', key: 'price', width: 140},
        {title: 'Валюта', dataIndex: 'currency', key: 'currency', render: currency => currency.name, width: 70},
        {title: 'Масса, кг', dataIndex: 'weight', key: 'weight', width: 90},
        {title: 'Мощность', dataIndex: 'power', key: 'power', width: 95},
        {title: 'Ток, А', dataIndex: 'amperage', key: 'amperage', width: 70},
        {
            title: 'Тип соединения',
            dataIndex: 'connection_type',
            key: 'connection_type',
            render: connectionType => connectionType.name,
            width: 120
        },
        {title: 'Мин. темп. жидк, °С', dataIndex: 'min_liquid_temp', key: 'min_liquid_temp', width: 160},
        {title: 'Макс. темп. жидк, °С', dataIndex: 'max_liquid_temp', key: 'max_liquid_temp', width: 170},
        {title: 'Межосевое расстояние, мм', dataIndex: 'between_axes_dist', key: 'between_axes_dist', width: 150},
        {title: 'ДУ вход', dataIndex: 'dn_input', key: 'dn_input', render: dn => dn.value, width: 70},
        {title: 'ДУ выход', dataIndex: 'dn_output', key: 'dn_output', render: dn => dn.value, width: 70},
        {
            title: 'Встроенное регулирование',
            dataIndex: 'regulation',
            key: 'regulation',
            render: regulation => regulation.name,
            width: 140
        },
        {
            title: 'Фаза',
            dataIndex: 'phase',
            key: 'phase',
            render: phase => phase.value + "(" + phase.voltage + ")",
            width: 90
        },
        {
            title: 'Тип',
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
            title: 'Применения',
            dataIndex: 'applications',
            key: 'applications',
            width: 450,
            render: applications => (
                <Space size={[0, 5]} wrap>
                    {applications.map(application => <Tag color="blue" key={application.name}>{application.name}</Tag>)}
                </Space>
            )
        },
    ]

    return (
        <Row gutter={[0, 10]}>
            {auth.isAdmin && <Col xs={24}>
                <BoxFlexEnd>
                    <FileUploader route={route('pumps.import')} title={"Загрузить насосы"}/>
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
                        return {
                            onDoubleClick: _ => {
                                message.info("Просмотр насоса находится в разрарботке")
                            }
                        }
                    }}
                    scroll={{x: 3400, y: 600}}
                />
            </Col>
        </Row>
    )
}

Index.layout = page =>
    <Authenticated children={page} backTo={true} title="Насосы" breadcrumbs={useBreadcrumbs().pumps}/>

export default Index
