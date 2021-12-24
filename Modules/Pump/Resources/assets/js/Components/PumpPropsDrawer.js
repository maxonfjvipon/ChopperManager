import {Col, Drawer, Form, Input, List, Space, Tabs} from "antd";
import {FilePdfOutlined} from "@ant-design/icons";
import Lang from "../../../../../../resources/js/translation/lang";
import React, {useEffect, useState} from "react";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import {ItemsForm} from "../../../../../../resources/js/src/Shared/ItemsForm";
import {JustifiedRow} from "../../../../../../resources/js/src/Shared/JustifiedRow";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {AddPumpsToProjectsDrawer} from "./AddPumpToProjectsDrawer";

export const PumpPropsDrawer = ({
                                    addToProjects,
                                    title,
                                    visible,
                                    setVisible,
                                    pumpInfo,
                                    needCurve = false
                                }) => {
    const _img = ({src}) => (
        <JustifiedRow>
            <Col>
                <img style={{margin: "0 auto", maxWidth: 600}} alt='./media/no_image.jpg'
                     src={src}/>
            </Col>
        </JustifiedRow>
    )

    const [form] = Form.useForm()

    useEffect(() => {
        if (pumpInfo) {
            form.setFieldsValue(pumpInfo)
            setVisible(true)
        }
    }, [pumpInfo])

    const {reducedAntFormItemClassName} = useStyles()

    const [addToProjectsVisible, setAddToProjectsVisible] = useState(false)

    const items = [
        {
            values: {
                name: 'article_num_main',
                label: Lang.get('pages.pumps.data.article_num_main'),
                initialValue: pumpInfo?.article_num_main,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'article_num_reserve',
                label: Lang.get('pages.pumps.data.article_num_reserve'),
                initialValue: pumpInfo?.article_num_reserve,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'article_num_archive',
                label: Lang.get('pages.pumps.data.article_num_archive'),
                initialValue: pumpInfo?.article_num_archive,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'weight',
                label: Lang.get('pages.pumps.data.weight'),
                initialValue: pumpInfo?.weight,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'rated_power',
                label: Lang.get('pages.pumps.data.rated_power'),
                initialValue: pumpInfo?.rated_power,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'rated_current',
                label: Lang.get('pages.pumps.data.rated_current'),
                initialValue: pumpInfo?.rated_current,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'connection_type',
                label: Lang.get('pages.pumps.data.connection_type'),
                initialValue: pumpInfo?.connection_type,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'fluid_temp_min',
                label: Lang.get('pages.pumps.data.fluid_temp_min'),
                initialValue: pumpInfo?.fluid_temp_min,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'fluid_temp_max',
                label: Lang.get('pages.pumps.data.fluid_temp_max'),
                initialValue: pumpInfo?.fluid_temp_max,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'ptp_length',
                label: Lang.get('pages.pumps.data.ptp_length'),
                initialValue: pumpInfo?.ptp_length,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'dn_suction',
                label: Lang.get('pages.pumps.data.dn_suction'),
                initialValue: pumpInfo?.dn_suction,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'dn_pressure',
                label: Lang.get('pages.pumps.data.dn_pressure'),
                initialValue: pumpInfo?.dn_pressure,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'category',
                label: Lang.get('pages.pumps.data.category'),
                initialValue: pumpInfo?.category,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'connection',
                label: Lang.get('pages.pumps.data.connection'),
                initialValue: pumpInfo?.connection,
                className: reducedAntFormItemClassName,
            }, input: <Input
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'types',
                label: Lang.get('pages.pumps.data.types'),
                initialValue: pumpInfo?.types,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'applications',
                label: Lang.get('pages.pumps.data.applications'),
                initialValue: pumpInfo?.applications,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                bordered={false}
                readOnly
            />
        },
        {
            values: {
                name: 'description',
                label: Lang.get('pages.pumps.data.description'),
                initialValue: pumpInfo?.description,
                className: reducedAntFormItemClassName,
            }, input: <Input.TextArea
                autoSize
                bordered={false}
                readOnly
            />
        },
    ]

    return (
        <Drawer
            width={needCurve ? 800 : 650}
            placement="right"
            title={title ? pumpInfo?.full_name : Lang.get('pages.selections.single_pump.graphic.info')}
            visible={visible}
            onClose={() => {
                setVisible(false)
            }}
            afterVisibleChange={visible => {
                if (visible && needCurve)
                    document.getElementById('for-graphic').innerHTML = pumpInfo.svg
            }}
            extra={addToProjects && <Space>
                <PrimaryButton onClick={() => {
                    setAddToProjectsVisible(true)
                }}>
                    {Lang.get('pages.pumps.add_to_projects.button')}
                </PrimaryButton>
            </Space>}
        >
            <Tabs type="card" defaultActiveKey="curve">
                {needCurve && <Tabs.TabPane
                    tab={Lang.get('pages.selections.single_pump.pump_info.curves')}
                    key="curve"
                >
                    <div id="for-graphic"/>
                </Tabs.TabPane>}
                <Tabs.TabPane tab={Lang.get('pages.selections.single_pump.pump_info.props')} key="props">
                    <ItemsForm
                        form={form}
                        layout="horizontal"
                        labelSpan={{xs: 6}}
                        items={items}
                    />
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single_pump.pump_info.model_picture')} key="model_pic">
                    <_img src={pumpInfo?.images.pump}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single_pump.pump_info.sizes_picture')} key="size_pic">
                    <_img src={pumpInfo?.images.sizes}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single_pump.pump_info.electric_diagram')} key="electric_pic">
                    <_img src={pumpInfo?.images.electric_diagram}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single_pump.pump_info.exploded_view')} key="expl_pic">
                    <_img src={pumpInfo?.images.cross_sectional_drawing}/>
                </Tabs.TabPane>
                <Tabs.TabPane tab={Lang.get('pages.selections.single_pump.pump_info.files')} key="files">
                    <List
                        size="large"
                        itemLayout="horizontal"
                        dataSource={pumpInfo?.files}
                        renderItem={file => (
                            <List.Item>
                                <List.Item.Meta
                                    avatar={<FilePdfOutlined/>}
                                    title={<a download href={file.link}>{file.name}</a>}
                                />
                            </List.Item>
                        )}
                    />
                </Tabs.TabPane>
            </Tabs>
            {addToProjects && <AddPumpsToProjectsDrawer
                pumpInfo={pumpInfo}
                visible={addToProjectsVisible}
                setVisible={setAddToProjectsVisible}
            />}
        </Drawer>
    )
}
