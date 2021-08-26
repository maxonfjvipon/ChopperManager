import React, {useEffect} from "react";
import {usePage} from "@inertiajs/inertia-react";
import {Authenticated} from "../../Shared/Layout/Authenticated";
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

const Show = () => {
    const {pump} = usePage().props
    const {reducedAntFormItemClassName, fullWidth} = useStyles()

    useEffect(() => {
        console.log(pump)
    }, [pump])

    const items = [
        {
            values: {
                name: 'part_num_main',
                label: 'Артикул основной',
                initialValue: pump.data.part_num_main,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'part_num_backup',
                label: 'Артикул резервный',
                initialValue: pump.data.part_num_backup,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'part_num_archive',
                label: 'Артикул архивный',
                initialValue: pump.data.part_num_archive,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'price',
                label: 'Цена',
                initialValue: pump.data.price,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'currency',
                label: 'Валюта',
                initialValue: pump.data.currency,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'weight',
                label: 'Масса',
                initialValue: pump.data.weight,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'power',
                label: 'Мощность',
                initialValue: pump.data.power,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'amperage',
                label: 'Потребляемый ток',
                initialValue: pump.data.amperage,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'connection_type',
                label: 'Тип соединения',
                initialValue: pump.data.connection_type,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'min_liquid_temp',
                label: 'Мин.темп.жидкости',
                initialValue: pump.data.min_liquid_temp,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'max_liquid_temp',
                label: 'Макс.темп.жидкости',
                initialValue: pump.data.max_liquid_temp,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'between_axes_dist',
                label: 'Межосевое расстояние',
                initialValue: pump.data.between_axes_dist,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'dn_input',
                label: 'ДУ входа',
                initialValue: pump.data.dn_input,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'dn_output',
                label: 'ДУ выхода',
                initialValue: pump.data.dn_output,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'category',
                label: 'Категория',
                initialValue: pump.data.category,
                className: reducedAntFormItemClassName,
            }, input: <Input bordered={false} readOnly/>
        },
        {
            values: {
                name: 'regulation',
                label: 'Встроенное регулирование',
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
                    title={`${pump.data.producer} ${pump.data.series} ${pump.data.name}`}
                >
                    <ItemsForm layout="horizontal" labelSpan={15} items={items}/>
                </Card>
            </Col>
            <Col md={24} lg={15} xl={17} xxl={19}>
                <Card
                    style={{...fullWidth, borderRadius: 10}}
                    title={"Гидравлическая характеристика"}
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
                                    label="Расход, м³/час"
                                />
                                <VictoryAxis dependentAxis
                                             label="Напор, м"
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

Show.layout = page => <Authenticated
    children={page}
    backTo={true}
    title={"Насосы"}
    breadcrumbs={useBreadcrumbs().pumps}

/>

export default Show
