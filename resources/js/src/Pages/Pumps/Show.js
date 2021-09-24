import React, {useEffect} from "react";
import {usePage} from "@inertiajs/inertia-react";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {ItemsForm} from "../../Shared/ItemsForm";
import {Card, Col, Input, Row} from "antd";
import {useStyles} from "../../Hooks/styles.hook";
import {
    VictoryChart,
    VictoryLine,
    VictoryScatter,
    VictoryVoronoiContainer,
    VictoryTooltip,
    VictoryAxis
} from 'victory'
import Lang from '../../../translation/lang'

const Show = () => {
    const {pump} = usePage().props
    const {reducedAntFormItemClassName, fullWidth} = useStyles()

    useEffect(() => {
        // console.log(pump)
    }, [pump])

    const items = [
        {
            values: {
                name: 'part_num_main',
                label: Lang.get('pages.pumps.data.part_num_main'),
                initialValue: pump.data.part_num_main,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'part_num_backup',
                label: Lang.get('pages.pumps.data.part_num_backup'),
                initialValue: pump.data.part_num_backup,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'part_num_archive',
                label: Lang.get('pages.pumps.data.part_num_archive'),
                initialValue: pump.data.part_num_archive,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'price',
                label: Lang.get('pages.pumps.data.price'),
                initialValue: pump.data.price,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'currency',
                label: Lang.get('pages.pumps.data.currency'),
                initialValue: pump.data.currency,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'weight',
                label: Lang.get('pages.pumps.data.weight'),
                initialValue: pump.data.weight,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'power',
                label: Lang.get('pages.pumps.data.power'),
                initialValue: pump.data.power,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'amperage',
                label: Lang.get('pages.pumps.data.amperage'),
                initialValue: pump.data.amperage,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'connection_type',
                label: Lang.get('pages.pumps.data.connection_type'),
                initialValue: pump.data.connection_type,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'min_liquid_temp',
                label: Lang.get('pages.pumps.data.min_fluid_temp'),
                initialValue: pump.data.min_liquid_temp,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'max_liquid_temp',
                label: Lang.get('pages.pumps.data.max_fluid_temp'),
                initialValue: pump.data.max_liquid_temp,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'between_axes_dist',
                label: Lang.get('pages.pumps.data.between_axes_dist'),
                initialValue: pump.data.between_axes_dist,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'dn_input',
                label: Lang.get('pages.pumps.data.dn_input'),
                initialValue: pump.data.dn_input,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'dn_output',
                label: Lang.get('pages.pumps.data.dn_output'),
                initialValue: pump.data.dn_output,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'category',
                label: Lang.get('pages.pumps.data.category'),
                initialValue: pump.data.category,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'regulation',
                label: Lang.get('pages.pumps.data.regulation'),
                initialValue: pump.data.regulation,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
    ]

    return (
        <Row justify="space-around" gutter={[10, 10]}>
            <Col md={24} lg={15} xl={7} xxl={5}>
                <Card
                    style={{...fullWidth, borderRadius: 10}}
                    title={`${pump.data.brand} ${pump.data.series} ${pump.data.name}`}
                >
                    <ItemsForm layout="horizontal" labelSpan={15} items={items}/>
                </Card>
            </Col>
            <Col md={24} lg={15} xl={17} xxl={19}>
                <Card
                    style={{...fullWidth, borderRadius: 10}}
                    title={Lang.get('pages.pumps.hydraulic_perf')}
                >
                    <Row justify="space-around">
                        <Col xs={18}>
                            <VictoryChart
                                width={1000}
                                height={529}
                                // responsive={false}
                                domain={{y: [0, pump.data.performance.y_max]}}
                                // standalone={false}
                                containerComponent={<VictoryVoronoiContainer
                                    responsive={false}
                                />}
                            >
                                <VictoryAxis
                                    orientation="bottom"
                                    label={Lang.get('pages.pumps.consumption')}
                                />
                                <VictoryAxis dependentAxis
                                             label={Lang.get('pages.pumps.pressure')}
                                />
                                <VictoryLine
                                    interpolation="linear" data={pump.data.performance.line_data}
                                    style={{data: {stroke: "blue"}}}
                                />
                                <VictoryScatter
                                    data={pump.data.performance.line_data}
                                    size={5}
                                    style={{
                                        data: {fill: "blue"},
                                        labels: {fill: "blue"}
                                    }}
                                    labels={({datum}) => {
                                        return "Q: " + datum.x + ", H: " + datum.y
                                    }}
                                    labelComponent={<VictoryTooltip/>}
                                />
                            </VictoryChart>
                        </Col>
                    </Row>
                </Card>
            </Col>
        </Row>
    )
}

Show.layout = page => <Common
    children={page}
    backTo={true}
    title={Lang.get('pages.pumps.title')}
    breadcrumbs={useBreadcrumbs().pumps}

/>

export default Show
