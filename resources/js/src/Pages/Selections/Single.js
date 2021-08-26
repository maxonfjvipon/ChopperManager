import React, {useEffect, useState} from 'react'
import {Authenticated} from "../../Shared/Layout/Authenticated";
import {Checkbox, Col, Form, InputNumber, message, Radio, Row, Space, Table, Tree, Typography} from "antd";
import {RequiredFormItem} from "../../Shared/RequiredFormItem";
import {MultipleSelection} from "../../Shared/Inputs/MultipleSelection";
import {useStyles} from "../../Hooks/styles.hook";
import {Selection} from "../../Shared/Inputs/Selection";
import {useCheck} from "../../Hooks/check.hook";
import {useHttp} from "../../Hooks/http.hook";
import {useGraphic} from "../../Hooks/graphic.hook";
import {TypographyCenter} from "../../Shared/TypographyCenter";
import {usePage} from "@inertiajs/inertia-react";
import {useForm} from "antd/es/form/Form";
import {useBreadcrumbs} from "../../Hooks/breadcrumbs.hook";
import {useDebounce} from "../../Hooks/debounce.hook";
import {Inertia} from "@inertiajs/inertia";
import {BoxFlexEnd} from "../../Shared/Box/BoxFlexEnd";
import {SecondaryButton} from "../../Shared/Buttons/SecondaryButton";
import {PrimaryButton} from "../../Shared/Buttons/PrimaryButton";

const ConditionSelectionFormItem = ({options, initialValue = null, name, disabled}) => {
    const {fullWidth} = useStyles()
    return (
        <Form.Item name={name} initialValue={initialValue}>
            <Selection
                style={fullWidth}
                placeholder="Условие"
                options={options}
                disabled={disabled || false}
            />
        </Form.Item>
    )
}

const ConditionLimitCheckboxFormItem = ({name, initialValue, children}) => {
    return (
        <Form.Item className="ant-form-item-reduced" name={name} valuePropName="checked"
                   initialValue={initialValue || false}>
            {children}
        </Form.Item>
    )
}

const LimitRow = ({children}) =>
    <Row gutter={[10, 0]}>
        {children}
    </Row>

const LimitCol = ({children}) =>
    <Col xs={12}>
        {children}
    </Col>


const Single = () => {
    // STATE
    const [showBrandsList, setShowBrandsList] = useState(false)

    const [producersSeriesList, setProducersSeriesList] = useState([])
    const [producersSeriesListValues, setProducersSeriesListValues] = useState([])

    const [producersSeriesTree, setProducersSeriesTree] = useState([])
    const [selectedPumps, setSelectedPumps] = useState([])

    // HOOKS
    const {PSHCDiagram, setStationToShow, stationToShow, setWorkingPoint} = useGraphic()
    const {isArrayEmpty} = useCheck()
    const {postRequest, loading} = useHttp()

    const page = usePage().props

    // console.log(page)

    const {
        selection,
        project_id,
        selection_props
    } = page

    const {
        producers,
        producersWithSeries,
        dns,
        limitConditions,
        phases,
        regulations,
        applications,
        types,
        defaults,
        connectionTypes
    } = selection_props.data

    const [producersValue, setProducersValue] = useState(selection?.data.pump_producers || defaults.producers)
    const [regulationValue, setRegulationValue] = useState(selection?.data.pump_regulations || defaults.regulations)
    const [typesValue, setTypesValue] = useState(selection?.data.pump_types || [])
    const [applicationsValue, setApplicationsValue] = useState(selection?.data.pump_applications || [])

    const [temperatureValue, setTemperatureValue] = useState(selection?.data.liquid_temperature || 20)
    const [prevTemperatureValue, setPrevTemperatureValue] = useState(-100)
    const debouncedTemperature = useDebounce(temperatureValue, 500)

    const [limitChecks, setLimitChecks] = useState({
        power: selection?.data.power_limit_checked || false,
        betweenAxes: selection?.data.between_axes_limit_checked || false,
        dnInput: selection?.data.dn_input_limit_checked || false,
        dnOutput: selection?.data.dn_output_limit_checked || false,
    })

    // CONSTS
    const mainPumpsCountCheckboxesOptions = [1, 2, 3, 4, 5].map(value => {
        return {
            value,
            label: value,
        }
    })
    const selectedPumpsColumns = [
        {title: 'Наименование', dataIndex: 'name', key: 'name', width: '15%'},
        {title: 'Артикул', dataIndex: 'partNum', key: 'partNum'},
        {
            title: 'Цена розничная',
            dataIndex: 'retailPrice',
            key: 'retailPrice',
            sorter: (a, b) => a.retailPrice - b.retailPrice,
        },
        {
            title: 'Цена персональная',
            dataIndex: 'personalPrice',
            key: 'personalPrice',
            sorter: (a, b) => a.personalPrice - b.personalPrice,
            defaultSortOrder: 'ascend'
        },
        {
            title: 'Сумма розничная',
            dataIndex: 'retailPriceSum',
            key: 'retailPriceSum',
            sorter: (a, b) => a.retailPriceSum - b.retailPriceSum
        },
        {
            title: 'Сумма персональная',
            dataIndex: 'personalPriceSum',
            key: 'personalPriceSum',
            sorter: (a, b) => a.personalPriceSum - b.personalPriceSum
        },
        {title: 'ДУ вход', dataIndex: 'dnInput', key: 'dnInput'},
        {title: 'ДУ выход', dataIndex: 'dnOutput', key: 'dnOutput'},
        {
            title: 'Мощность насоса',
            dataIndex: 'power',
            key: 'power',
            sorter: (a, b) => a.power - b.power
        },
        {
            title: 'Мощность насосов',
            dataIndex: 'powerSum',
            key: 'powerSum',
            sorter: (a, b) => a.powerSum - b.powerSum
        },
        {
            title: 'Межосевое расстояние',
            dataIndex: 'betweenAxesDist',
            key: 'betweenAxesDist',
            sorter: (a, b) => a.betweenAxesDist - b.betweenAxesDist
        },
    ]
    const fieldsDisabled = (isArrayEmpty(producersValue))
    const [selectionForm] = useForm()
    const [fullSelectionForm] = useForm()

    const fullSelectionFormName = "full-selection-form"
    const selectionFormName = "selection-form"

    // FILTERED PRODUCERS WITH SERIES BY producersValue // DONE
    const filteredProducersWithSeries = () => {
        return producersWithSeries.filter(producer => {
            return producersValue.indexOf(producer.id) !== -1
        })
    }

    // STYLES
    const {fullWidth, marginBottomTen, margin, reducedAntFormItemClassName} = useStyles()
    const nextBelowStyle = {...fullWidth, ...marginBottomTen}

    // TEMPERATURE CHANGE HANDLER
    const temperatureChangeHandler = () => value => {
        setTemperatureValue(value)
    }

    // PRODUCERS SERIES LIST VALUES CHECKED HANDLER
    // TODO: somehow info user that series have no types/applications if they do
    const producerSeriesListValuesCheckedHandler = values => {
        setProducersSeriesListValues(values)
    }

    // CHECK PRODUCERS SERIES LIST AND TREE CHANGE // DONE
    useEffect(() => {
        const _producersSeriesList = [];
        const _producersSeriesTree = []
        if (producersValue.length > 0) {
            filteredProducersWithSeries().forEach(producer => {
                let children = []
                producer.series.forEach(series => {
                    _producersSeriesList.push({
                        label: producer.name + " " + series.name,
                        value: series.id
                    })
                    children.push({
                        title: series.name,
                        key: series.id,
                    })
                })
                _producersSeriesTree.push({
                    title: producer.name,
                    key: producer.name,
                    children
                })
            })
        }
        setProducersSeriesList(_producersSeriesList)
        setProducersSeriesTree(_producersSeriesTree)
    }, [producersValue, producersWithSeries])

    // FILTER BRANDS HANDLER
    useEffect(() => {
        if (!isArrayEmpty(producersSeriesList)) {
            const _producersSeriesListValues = []
            const _producersSeriesList = []
            const _producersSeriesTree = []
            const temperatureWasChanged = debouncedTemperature !== prevTemperatureValue
            filteredProducersWithSeries().forEach(producer => {
                let children = []
                producer.series.forEach(series => {
                    const producerSeries = producer.name + " " + series.name
                    let hasType = typesValue.length <= 0
                    let hasApplication = applicationsValue.length <= 0
                    let hasRegulation = regulationValue <= 0
                    let hasTemp = series.temperatures?.temp_max >= debouncedTemperature && series.temperatures?.temp_min <= debouncedTemperature
                    if (!hasType) {
                        if (typesValue.every(typeValue => series.types
                            .map(type => type.id)
                            .includes(typeValue))
                        ) {
                            hasType = true
                        }
                    }
                    if (!hasApplication) {
                        if (applicationsValue.every(applicationValue => series.applications
                            .map(application => application.id)
                            .includes(applicationValue))
                        ) {
                            hasApplication = true
                        }
                    }
                    if (!hasRegulation) {
                        for (let regulation of series.regulations) {
                            if (regulationValue.includes(regulation.id)) {
                                hasRegulation = true
                                break
                            }
                        }
                    }
                    if (hasType && hasRegulation && hasTemp && hasApplication) {
                        _producersSeriesListValues.push(series.id)
                    }
                    if (temperatureWasChanged) {
                        _producersSeriesList.push({
                            label: <Typography style={{color: hasTemp ? 'black' : 'red'}}>{producerSeries}</Typography>,
                            value: series.id,
                            disabled: !hasTemp

                        })
                        children.push({
                            title: <Typography style={{color: hasTemp ? 'black' : 'red'}}>{series.name}</Typography>,
                            key: series.id,
                            disabled: !hasTemp
                        })
                    }
                })
                if (temperatureWasChanged) {
                    _producersSeriesTree.push({
                        title: producer.name,
                        key: producer.name,
                        children
                    })
                }
            })

            if (temperatureWasChanged) {
                setProducersSeriesList(_producersSeriesList)
                setProducersSeriesTree(_producersSeriesTree)
                setPrevTemperatureValue(debouncedTemperature)
            }

            // what checkboxes are checked
            setProducersSeriesListValues(_producersSeriesListValues)
        }
    }, [typesValue, applicationsValue, regulationValue, debouncedTemperature, producersSeriesList])

    // SAVE HANDLER
    const addSelectionToProjectClickHandler = async fullSelectionFormData => {
        const selectionFormData = await selectionForm.validateFields()
        const separator = "|" // FIXME: some how make it global

        const body = {
            ...selectionFormData,
            ...fullSelectionFormData,
            pump_producer_ids: fullSelectionFormData.pump_producer_ids.join(separator),
            pump_regulation_ids: fullSelectionFormData.pump_regulation_ids?.join(separator),
            pump_type_ids: fullSelectionFormData.pump_type_ids?.join(separator),
            pump_application_ids: fullSelectionFormData.pump_application_ids?.join(separator),
            main_pumps_counts: selectionFormData.main_pumps_counts.join(separator),
            connection_type_ids: selectionFormData.connection_type_ids?.join(separator),
            current_phase_ids: selectionFormData.current_phase_ids?.join(separator),
            pump_id: stationToShow.pump_id,
            selected_pump_name: stationToShow.name,
            pumps_count: stationToShow.pumps_count,
            project_id,
        }
        Inertia.post(selection ? route('selections.update', selection.data.id) : route('selections.store'), body, {
            preserveScroll: true
        })
    }

    // MAKE SELECTION HANDLER
    const makeSelectionHandler = async body => {
        if (isArrayEmpty(producersSeriesListValues)) {
            message.warning('Не выбрана ни одна серия')
            return
        }
        setStationToShow(null)
        setWorkingPoint(null)
        body = {
            ...body,
            series_ids: producersSeriesListValues,
        }
        try {
            // Inertia.post(route('selections.select'), body)
            const data = await postRequest(route('selections.select'), body, true)
            // console.log(data)
            setSelectedPumps(data.selected_pumps)
            setWorkingPoint(data.working_point)
        } catch {
        }
    }

    return (
        <Row style={{minHeight: 600}} justify="space-around" gutter={[10, 10]}>
            {/* BRANDS LIST */}
            <Col xs={2}>
                <Checkbox
                    checked={showBrandsList}
                    onChange={e => {
                        setShowBrandsList(e.target.checked)
                    }}
                >
                    Группировка по брендам
                </Checkbox>
                {showBrandsList && <Tree
                    defaultExpandAll
                    checkable
                    treeData={producersSeriesTree}
                    checkedKeys={producersSeriesListValues}
                    onCheck={producerSeriesListValuesCheckedHandler}

                />}
                {!showBrandsList && <Checkbox.Group
                    options={producersSeriesList}
                    value={producersSeriesListValues}
                    onChange={producerSeriesListValuesCheckedHandler}
                />}
            </Col>
            {/* RIGHT SIDE */}
            <Col xs={22}>
                <Row gutter={[10, 10]}>
                    <Col xs={3}>
                        <Form
                            name={fullSelectionFormName}
                            form={fullSelectionForm}
                            onFinish={addSelectionToProjectClickHandler}
                            layout="vertical"
                        >
                            {/* PRODUCERS */}
                            <RequiredFormItem
                                className={reducedAntFormItemClassName}
                                name="pump_producer_ids"
                                initialValue={producersValue}
                                label="Производитель"
                            >
                                <MultipleSelection
                                    placeholder="Производитель"
                                    style={fullWidth}
                                    options={producers}
                                    onChange={values => {
                                        setProducersValue(values)
                                    }}
                                    value={producersValue}
                                />
                            </RequiredFormItem>
                            {/* TYPES */}
                            <Form.Item
                                className={reducedAntFormItemClassName}
                                name="pump_type_ids"
                                label="Тип"
                                initialValue={selection?.data.pump_types}
                                tooltip="Для серии проверяется наличие всех выбранных типов!"
                            >
                                <MultipleSelection
                                    placeholder="Тип"
                                    disabled={fieldsDisabled}
                                    style={fullWidth}
                                    options={types}
                                    onChange={values => {
                                        setTypesValue(values)
                                    }}
                                />
                            </Form.Item>
                            {/* APPLICATIONS */}
                            <Form.Item
                                className={reducedAntFormItemClassName}
                                name="pump_application_ids"
                                label="Применение"
                                initialValue={selection?.data.pump_applications}
                                tooltip="Для серии проверяется наличие всех выбранных применений!"
                            >
                                <MultipleSelection
                                    placeholder="Применение"
                                    disabled={fieldsDisabled}
                                    style={fullWidth}
                                    options={applications}
                                    onChange={values => {
                                        setApplicationsValue(values)
                                    }}
                                />
                            </Form.Item>
                            {/* TEMPERATURE */}
                            <RequiredFormItem
                                className={reducedAntFormItemClassName}
                                label="Температура жидкости"
                                name="liquid_temperature"
                                initialValue={temperatureValue}
                                style={margin.bottom(10)}
                            >
                                <InputNumber
                                    disabled={fieldsDisabled}
                                    style={fullWidth}
                                    min={0}
                                    max={200}
                                    value={temperatureValue}
                                    onChange={temperatureChangeHandler()}
                                />
                            </RequiredFormItem>
                            {/* REGULATION */}
                            <Form.Item
                                className={reducedAntFormItemClassName}
                                label={"Встроенное регулирование"}
                                name="pump_regulation_ids"
                                initialValue={regulationValue}
                                style={marginBottomTen}
                            >
                                <MultipleSelection
                                    placeholder="Встроенное регулирование"
                                    disabled={fieldsDisabled}
                                    style={{...fullWidth, marginTop: 0}}
                                    options={regulations}
                                    value={regulationValue}
                                    onChange={values => {
                                        setRegulationValue(values)
                                    }}
                                />
                            </Form.Item>
                        </Form>
                    </Col>
                    <Col span={21}>
                        <Form
                            form={selectionForm}
                            name={selectionFormName}
                            onFinish={makeSelectionHandler}
                            layout="vertical"
                        >
                            <Row gutter={[10, 1]}>
                                <Col span={3}>
                                    <RequiredFormItem
                                        label="Напор, м"
                                        name="pressure"
                                        initialValue={selection?.data.pressure}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <InputNumber
                                            placeholder="Напор, м"
                                            style={fullWidth}
                                            min={0}
                                            max={10000}
                                            precision={1}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                <Col span={3}>
                                    <RequiredFormItem
                                        label="Расход, м3/ч"
                                        name="consumption"
                                        initialValue={selection?.data.consumption}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <InputNumber
                                            placeholder="Расход, м3/ч"
                                            style={fullWidth}
                                            min={0}
                                            max={10000}
                                            precision={1}
                                        />
                                    </RequiredFormItem>
                                </Col>
                                <Col span={3}>
                                    <Form.Item
                                        label="Допуск, %"
                                        name="limit"
                                        initialValue={selection?.data.limit || 0}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <InputNumber
                                            placeholder="Допуск, %"
                                            style={fullWidth}
                                            min={0}
                                            max={100}
                                            precision={1}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col span={4}>
                                    <RequiredFormItem
                                        className={reducedAntFormItemClassName}
                                        label="Количество основных насосов"
                                        name="main_pumps_counts"
                                        initialValue={selection?.data.main_pumps_counts}
                                    >
                                        <Checkbox.Group options={mainPumpsCountCheckboxesOptions}/>
                                    </RequiredFormItem>
                                </Col>
                                <Col span={4}>
                                    <Form.Item
                                        required name="backup_pumps_count"
                                        label="Количество резервных насосов"
                                        initialValue={selection?.data.backup_pumps_count || 0}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <Radio.Group value={0}>
                                            {[0, 1, 2, 3, 4].map(value => (
                                                <Radio key={'bp' + value} value={value}>{value}</Radio>
                                            ))}
                                        </Radio.Group>
                                    </Form.Item>
                                </Col>
                                <Col span={4}>
                                    <Form.Item
                                        label="Тип соединения"
                                        name="connection_type_ids"
                                        className={reducedAntFormItemClassName}
                                        initialValue={selection?.data.connection_types}
                                    >
                                        <MultipleSelection
                                            placeholder="Тип соединения"
                                            style={nextBelowStyle}
                                            options={connectionTypes}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col span={3}>
                                    <Form.Item
                                        label="Фазы"
                                        name="current_phase_ids"
                                        className={reducedAntFormItemClassName}
                                        initialValue={selection?.data.phases}
                                    >
                                        <MultipleSelection
                                            placeholder="Фазы"
                                            style={nextBelowStyle}
                                            options={phases.map(phase => {
                                                return {
                                                    customValue: phase.value + "(" + phase.voltage + ")",
                                                    ...phase,
                                                }
                                            })}
                                        />
                                    </Form.Item>
                                </Col>
                                {/* LIMITS */}
                                {/* POWER LIMIT */}
                                <Col span={5}>
                                    <ConditionLimitCheckboxFormItem
                                        name="power_limit_checked"
                                        initialValue={selection?.data.power_limit_checked}
                                    >
                                        <Checkbox
                                            checked={limitChecks.power}
                                            onChange={e => {
                                                setLimitChecks({
                                                    ...limitChecks,
                                                    power: e.target.checked
                                                })
                                            }}
                                        >
                                            Ограничение мощности насоса
                                        </Checkbox>
                                    </ConditionLimitCheckboxFormItem>
                                    <LimitRow>
                                        <LimitCol>
                                            <ConditionSelectionFormItem
                                                options={limitConditions}
                                                name="power_limit_condition_id"
                                                disabled={!limitChecks.power}
                                                initialValue={selection?.data.power_limit_condition_id}
                                            />
                                        </LimitCol>
                                        <LimitCol>
                                            <Form.Item
                                                name="power_limit_value"
                                                initialValue={selection?.data.power_limit_value}
                                            >
                                                <InputNumber
                                                    disabled={!limitChecks.power}
                                                    style={fullWidth}
                                                    placeholder="Мощность"
                                                />
                                            </Form.Item>
                                        </LimitCol>
                                    </LimitRow>
                                </Col>
                                <Col span={5}>
                                    <ConditionLimitCheckboxFormItem
                                        name="between_axes_limit_checked"
                                        initialValue={selection?.data.between_axes_limit_checked}
                                    >
                                        <Checkbox
                                            checked={limitChecks.betweenAxes}
                                            onChange={e => {
                                                setLimitChecks({
                                                    ...limitChecks,
                                                    betweenAxes: e.target.checked
                                                })
                                            }}
                                        >
                                            Ограничение межосевого расстояния
                                        </Checkbox>
                                    </ConditionLimitCheckboxFormItem>
                                    <LimitRow>
                                        <LimitCol>
                                            <ConditionSelectionFormItem
                                                options={limitConditions}
                                                name="between_axes_limit_condition_id"
                                                disabled={!limitChecks.betweenAxes}
                                                initialValue={selection?.data.between_axes_limit_condition_id}
                                            />
                                        </LimitCol>
                                        <LimitCol>
                                            <Form.Item
                                                name="between_axes_limit_value"
                                                initialValue={selection?.data.between_axes_limit_value}
                                            >
                                                <InputNumber
                                                    disabled={!limitChecks.betweenAxes}
                                                    style={fullWidth}
                                                    placeholder="Межосевое расстояние"
                                                />
                                            </Form.Item>
                                        </LimitCol>
                                    </LimitRow>
                                </Col>
                                <Col span={5}>
                                    <ConditionLimitCheckboxFormItem
                                        name="dn_input_limit_checked"
                                        initialValue={selection?.data.dn_input_limit_checked}
                                    >
                                        <Checkbox
                                            checked={limitChecks.dnInput}
                                            onChange={e => {
                                                setLimitChecks({
                                                    ...limitChecks,
                                                    dnInput: e.target.checked
                                                })
                                            }}
                                        >
                                            Ограничение ДУ вход
                                        </Checkbox>
                                    </ConditionLimitCheckboxFormItem>
                                    <LimitRow>
                                        <LimitCol>
                                            <ConditionSelectionFormItem
                                                initialValue={selection?.data.dn_input_limit_condition_id}
                                                options={limitConditions}
                                                name="dn_input_limit_condition_id"
                                                disabled={!limitChecks.dnInput}
                                            />
                                        </LimitCol>
                                        <LimitCol>
                                            <Form.Item
                                                name="dn_input_limit_id"
                                                initialValue={selection?.data.dn_input_limit_id}
                                            >
                                                <Selection
                                                    placeholder="ДУ вход"
                                                    options={dns}
                                                    disabled={!limitChecks.dnInput}
                                                    style={fullWidth}
                                                />
                                            </Form.Item>
                                        </LimitCol>
                                    </LimitRow>
                                </Col>
                                <Col span={5}>
                                    <ConditionLimitCheckboxFormItem
                                        name="dn_output_limit_checked"
                                        initialValue={selection?.data.dn_output_limit_checked}
                                    >
                                        <Checkbox
                                            checked={limitChecks.dnOutput}
                                            onChange={e => {
                                                setLimitChecks({
                                                    ...limitChecks,
                                                    dnOutput: e.target.checked
                                                })
                                            }}
                                        >
                                            Ограничение ДУ выход
                                        </Checkbox>
                                    </ConditionLimitCheckboxFormItem>
                                    <LimitRow>
                                        <LimitCol>
                                            <ConditionSelectionFormItem
                                                initialValue={selection?.data.dn_output_limit_condition_id}
                                                options={limitConditions}
                                                name="dn_output_limit_condition_id"
                                                disabled={!limitChecks.dnOutput}
                                            />
                                        </LimitCol>
                                        <LimitCol>
                                            <Form.Item
                                                name="dn_output_limit_id"
                                                initialValue={selection?.data.dn_output_limit_id}
                                            >
                                                <Selection
                                                    placeholder="ДУ выход"
                                                    options={dns}
                                                    disabled={!limitChecks.dnOutput}
                                                    style={fullWidth}
                                                />
                                            </Form.Item>
                                        </LimitCol>
                                    </LimitRow>
                                </Col>
                                <Col span={4}>
                                    <PrimaryButton
                                        style={{...fullWidth, ...margin.top(33)}}
                                        htmlType="submit"
                                        disabled={producersValue.length === 0}
                                        loading={loading}
                                    >
                                        Подобрать
                                    </PrimaryButton>
                                </Col>
                                {selection && <Col xs={24}>
                                    <Typography.Title level={3}>
                                        {"Подобранный насос: " + selection.data.selected_pump_name}
                                    </Typography.Title>
                                </Col>}
                            </Row>
                        </Form>
                    </Col>
                    {/* TABLE */}
                    <Col xs={15}>
                        <Table
                            size="small"
                            columns={selectedPumpsColumns}
                            dataSource={selectedPumps}
                            onRow={(record, _) => {
                                return {
                                    onClick: _ => {
                                        setStationToShow(record);
                                    }
                                }
                            }}
                            pagination={{defaultPageSize: 500, pageSizeOptions: [10, 20, 50, 100, 500, 1000]}}
                            scroll={{x: 2000, y: 450}}
                        />
                    </Col>
                    {/* GRAPHIC */}
                    <Col xs={9}>
                        <TypographyCenter>
                            {stationToShow && stationToShow.name}
                        </TypographyCenter>
                        <PSHCDiagram multiline/>
                    </Col>
                    <Col xs={24}>
                        <BoxFlexEnd>
                            {project_id !== "-1" && <Space size={10}>
                                <SecondaryButton onClick={() => {
                                    Inertia.get(route('projects.show', project_id))
                                }}>
                                    Выйти
                                </SecondaryButton>
                                <PrimaryButton
                                    disabled={!stationToShow}
                                    htmlType="submit"
                                    form={fullSelectionFormName}
                                >
                                    {!selection ? "Добавить в проект" : "Обновить подбор"}
                                </PrimaryButton>
                            </Space>}
                            {project_id === "-1" && <SecondaryButton onClick={() => {
                                Inertia.get(route('projects.index'))
                            }}>
                                Выйти
                            </SecondaryButton>}
                        </BoxFlexEnd>
                    </Col>
                </Row>
            </Col>
        </Row>
    )
}

Single.layout = page => <Authenticated
    title={window.location.href.includes("show") ? "Просмотр подбора" : "Подбор насоса"}
    backTo={true}
    children={page}
    breadcrumbs={useBreadcrumbs().selections}
/>

export default Single

