import React, {useEffect, useState} from 'react'
import {
    Checkbox,
    Col,
    Form,
    InputNumber,
    message,
    Radio,
    Row,
    Space,
    Tree,
    Typography,
    notification, Divider
} from "antd";
import {RequiredFormItem} from "../../../Shared/RequiredFormItem";
import {MultipleSelection} from "../../../Shared/Inputs/MultipleSelection";
import {useStyles} from "../../../Hooks/styles.hook";
import {Selection} from "../../../Shared/Inputs/Selection";
import {useCheck} from "../../../Hooks/check.hook";
import {useHttp} from "../../../Hooks/http.hook";
import {useGraphic} from "../../../Hooks/graphic.hook";
import {usePage} from "@inertiajs/inertia-react";
import {useForm} from "antd/es/form/Form";
import {useDebounce} from "../../../Hooks/debounce.hook";
import {Inertia} from "@inertiajs/inertia";
import {BoxFlexEnd} from "../../../Shared/Box/BoxFlexEnd";
import {SecondaryButton} from "../../../Shared/Buttons/SecondaryButton";
import {PrimaryButton} from "../../../Shared/Buttons/PrimaryButton";
import Lang from '../../../../translation/lang'
import {SelectedPumpsTable} from "../Components/SelectedPumpsTable";
import {AuthLayout} from "../../../Shared/Layout/AuthLayout";
import {Container} from "../../../Shared/ResourcePanel/Index/Container";
import {useTransRoutes} from "../../../Hooks/routes.hook";

const ConditionSelectionFormItem = ({options, initialValue = null, name, disabled}) => {
    const {fullWidth} = useStyles()
    return (
        <Form.Item name={name} initialValue={initialValue}>
            <Selection
                style={fullWidth}
                placeholder={Lang.get('pages.selections.single.condition')}
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


const Index = () => {
    // STATE
    const [showBrandsList, setShowBrandsList] = useState(false)
    const [brandsSeriesList, setBrandsSeriesList] = useState([])
    const [brandsSeriesListValues, setBrandsSeriesListValues] = useState([])
    const [brandsSeriesTree, setBrandsSeriesTree] = useState([])
    const [selectedPumps, setSelectedPumps] = useState([])

    // HOOKS
    const {PSHCDiagram, setStationToShow, stationToShow, setWorkingPoint} = useGraphic()
    const {isArrayEmpty} = useCheck()
    const {postRequest, loading} = useHttp()
    const {selection, project_id, selection_props} = usePage().props

    const {
        brands,
        brandsWithSeries,
        dns,
        limitConditions,
        mainsConnections,
        powerAdjustments,
        applications,
        types,
        defaults,
        connectionTypes
    } = selection_props.data

    const [brandsValue, setBrandsValue] = useState(selection?.data.pump_brands || defaults.brands)
    const [powerAdjustmentValue, setPowerAdjustmentValue] = useState(selection?.data.pump_regulations || defaults.powerAdjustments)
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
        return {value, label: value}
    })
    const fieldsDisabled = (isArrayEmpty(brandsValue))
    const [selectionForm] = useForm()
    const [fullSelectionForm] = useForm()
    const {tRoute} = useTransRoutes()

    const fullSelectionFormName = "full-selection-form"
    const selectionFormName = "selection-form"

    // FILTERED PRODUCERS WITH SERIES BY brandsValue // DONE
    const filteredBrandsWithSeries = () => {
        return brandsWithSeries.filter(brand => {
            return brandsValue.indexOf(brand.id) !== -1
        })
    }

    // STYLES
    const {fullWidth, marginBottomTen, margin, reducedAntFormItemClassName} = useStyles()
    const nextBelowStyle = {...fullWidth, ...marginBottomTen}

    // TEMPERATURE CHANGE HANDLER
    const temperatureChangeHandler = () => value => {
        setTemperatureValue(value)
    }

    const checkValueIncludesSeriesParams = (value, params) => value.some(_value => !params.map(param => param.id).includes(_value))

    // PRODUCERS SERIES LIST VALUES CHECKED HANDLER
    const brandsSeriesListValuesCheckedHandler = values => {
        // const checked = values.find(value => !brandsSeriesListValues.includes(value))
        //
        // if (checked !== undefined) {
        //     for (let producer of filteredBrandsWithSeries()) {
        //         let index = producer.series.findIndex(series => series.id === checked)
        //         // console.log(index, producer.series[index].types, producer.series[index])
        //         if (index !== -1) {
        //             let array = []
        //
        //             // check types
        //             if (checkValueIncludesSeriesParams(typesValue, producer.series[index].types)) {
        //                 array.push('типам')
        //             }
        //
        //             // check applications
        //             if (checkValueIncludesSeriesParams(applicationsValue, producer.series[index].applications)) {
        //                 array.push('применениям')
        //             }
        //
        //             // check regulation
        //             if (checkValueIncludesSeriesParams(powerAdjustmentValue, producer.series[index].powerAdjustments)) {
        //                 array.push("регулированиям")
        //             }
        //
        //             if (array.length > 0) {
        //                 notification.info({
        //                     message: Lang.get('messages.selections.notification.attention'),
        //                     description: 'В выбранной серии ' + producer.series[index].name + ' отсутствуют насосы, соответствующие выбранным '
        //                         + array.join(', '),
        //                     placement: 'topLeft',
        //                     duration: 5
        //                 })
        //             }
        //             break
        //         }
        //     }
        // }

        // console.log(checked)
        setBrandsSeriesListValues(values)
    }

    // CHECK PRODUCERS SERIES LIST AND TREE CHANGE // DONE
    useEffect(() => {
        const _brandsSeriesList = [];
        const _brandsSeriesTree = []
        if (brandsValue.length > 0) {
            filteredBrandsWithSeries().forEach(brand => {
                let children = []
                brand.series.forEach(series => {
                    _brandsSeriesList.push({
                        label: brand.name + " " + series.name,
                        value: series.id
                    })
                    children.push({
                        title: series.name,
                        key: series.id,
                    })
                })
                _brandsSeriesTree.push({
                    title: brand.name,
                    key: brand.name,
                    children
                })
            })
        }
        setBrandsSeriesList(_brandsSeriesList)
        setBrandsSeriesTree(_brandsSeriesTree)
    }, [brandsValue, brandsWithSeries])

    // FILTER BRANDS HANDLER
    useEffect(() => {
        if (!isArrayEmpty(brandsSeriesList)) {
            const _producersSeriesListValues = []
            const _producersSeriesList = []
            const _producersSeriesTree = []
            const temperatureWasChanged = debouncedTemperature !== prevTemperatureValue
            filteredBrandsWithSeries().forEach(brand => {
                let children = []
                brand.series.forEach(series => {
                    const brandsSeries = brand.name + " " + series.name
                    let hasType = typesValue.length <= 0
                    let hasApplication = applicationsValue.length <= 0
                    let hasPowerAdjustment = powerAdjustmentValue <= 0
                    let hasTemp = series.temp_max >= debouncedTemperature && series.temp_min <= debouncedTemperature
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
                    if (!hasPowerAdjustment) {
                        if (powerAdjustmentValue.includes(series.power_adjustment?.id)) {
                            hasPowerAdjustment = true
                        }
                    }
                    if (hasType && hasPowerAdjustment && hasTemp && hasApplication) {
                        _producersSeriesListValues.push(series.id)
                    }
                    if (temperatureWasChanged) {
                        _producersSeriesList.push({
                            label: <Typography
                                style={{color: hasTemp ? 'black' : 'red'}}>{brandsSeries}</Typography>,
                            value: series.id,
                            disabled: !hasTemp

                        })
                        children.push({
                            title: <Typography
                                style={{color: hasTemp ? 'black' : 'red'}}>{series.name}</Typography>,
                            key: series.id,
                            disabled: !hasTemp
                        })
                    }
                })
                if (temperatureWasChanged) {
                    _producersSeriesTree.push({
                        title: brand.name,
                        key: brand.name,
                        children
                    })
                }
            })

            if (temperatureWasChanged) {
                setBrandsSeriesList(_producersSeriesList)
                setBrandsSeriesTree(_producersSeriesTree)
                setPrevTemperatureValue(debouncedTemperature)
            }

            // what checkboxes are checked
            setBrandsSeriesListValues(_producersSeriesListValues)
        }
    }, [typesValue, applicationsValue, powerAdjustmentValue, debouncedTemperature, brandsSeriesList])

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
        Inertia.post(selection
            ? tRoute('selections.update', selection.data.id)
            : tRoute('selections.store'), body,
            {
                preserveScroll: true
            }
        )
    }

    // MAKE SELECTION HANDLER
    const makeSelectionHandler = async body => {
        if (isArrayEmpty(brandsSeriesListValues)) {
            message.warning(Lang.get('messages.selections.no_series_selected'))
            return
        }
        setStationToShow(null)
        setWorkingPoint(null)
        body = {
            ...body,
            series_ids: brandsSeriesListValues,
        }
        try {
            // Inertia.post(tRoute('selections.select'), body)
            const data = await postRequest(tRoute('selections.select'), body, true)
            // console.log(data)
            setSelectedPumps(data.selected_pumps)
            setWorkingPoint(data.working_point)
        } catch {
        }
    }

    return (
        <>
            <Container
                title={selection
                    ? selection.data.selected_pump_name
                    : Lang.get('pages.selections.single.title_new')}
                backTitle={Lang.get('pages.selections.back_to_dashboard')}
                backHref={tRoute('selections.dashboard', project_id)}
            >
                <Row justify="space-around" gutter={[10, 10]}>
                    <Col xs={2}>
                        <Checkbox
                            checked={showBrandsList}
                            onChange={e => {
                                setShowBrandsList(e.target.checked)
                            }}
                        >
                            {Lang.get('pages.selections.single.grouping')}
                        </Checkbox>
                        <Divider style={margin.all("5px 0 5px")}/>
                        {showBrandsList && <Tree
                            defaultExpandAll
                            checkable
                            treeData={brandsSeriesTree}
                            checkedKeys={brandsSeriesListValues}
                            onCheck={brandsSeriesListValuesCheckedHandler}

                        />}
                        {!showBrandsList && <Checkbox.Group
                            options={brandsSeriesList}
                            value={brandsSeriesListValues}
                            onChange={brandsSeriesListValuesCheckedHandler}
                        />}
                    </Col>
                    <Col xs={22}>
                        <Row gutter={[10, 10]}>
                            <Col xs={24}>
                                <Form
                                    name={fullSelectionFormName}
                                    form={fullSelectionForm}
                                    onFinish={addSelectionToProjectClickHandler}
                                    layout="vertical"
                                >
                                    <Row gutter={10}>
                                        <Col xs={4}>
                                            {/* BRANDS */}
                                            <RequiredFormItem
                                                className={reducedAntFormItemClassName}
                                                name="pump_brand_ids"
                                                initialValue={brandsValue}
                                                label={Lang.get('pages.selections.single.brands')}
                                            >
                                                <MultipleSelection
                                                    placeholder={Lang.get('pages.selections.single.brands')}
                                                    style={fullWidth}
                                                    options={brands}
                                                    onChange={values => {
                                                        setBrandsValue(values)
                                                    }}
                                                />
                                            </RequiredFormItem>
                                        </Col>
                                        <Col xs={7}>
                                            {/* TYPES */}
                                            <Form.Item
                                                className={reducedAntFormItemClassName}
                                                name="pump_type_ids"
                                                label={Lang.get('pages.selections.single.types.label')}
                                                initialValue={selection?.data.pump_types}
                                                tooltip={Lang.get('pages.selections.single.types.tooltip')}
                                            >
                                                <MultipleSelection
                                                    placeholder={Lang.get('pages.selections.single.types.label')}
                                                    disabled={fieldsDisabled}
                                                    style={fullWidth}
                                                    options={types}
                                                    onChange={values => {
                                                        setTypesValue(values)
                                                    }}
                                                />
                                            </Form.Item>
                                        </Col>
                                        <Col xs={7}>
                                            {/* APPLICATIONS */}
                                            <Form.Item
                                                className={reducedAntFormItemClassName}
                                                name="pump_application_ids"
                                                label={Lang.get('pages.selections.single.applications.label')}
                                                initialValue={selection?.data.pump_applications}
                                                tooltip={Lang.get('pages.selections.single.applications.tooltip')}
                                            >
                                                <MultipleSelection
                                                    placeholder={Lang.get('pages.selections.single.applications.label')}
                                                    disabled={fieldsDisabled}
                                                    style={fullWidth}
                                                    options={applications}
                                                    onChange={values => {
                                                        setApplicationsValue(values)
                                                    }}
                                                />
                                            </Form.Item>
                                        </Col>
                                        <Col xs={3}>
                                            {/* TEMPERATURE */}
                                            <RequiredFormItem
                                                className={reducedAntFormItemClassName}
                                                label={Lang.get('pages.selections.single.fluid_temp')}
                                                name="fluid_temperature"
                                                initialValue={temperatureValue}
                                                style={margin.bottom(10)}
                                            >
                                                <InputNumber
                                                    disabled={fieldsDisabled}
                                                    style={fullWidth}
                                                    min={0}
                                                    max={200}
                                                    onChange={temperatureChangeHandler()}
                                                />
                                            </RequiredFormItem>
                                        </Col>
                                        <Col xs={3}>
                                            {/* REGULATION */}
                                            <Form.Item
                                                className={reducedAntFormItemClassName}
                                                label={Lang.get('pages.selections.single.power_adjustments')}
                                                name="power_adjustment_ids"
                                                initialValue={powerAdjustmentValue}
                                                style={marginBottomTen}
                                            >
                                                <MultipleSelection
                                                    placeholder={Lang.get('pages.selections.single.power_adjustments')}
                                                    disabled={fieldsDisabled}
                                                    style={{...fullWidth, marginTop: 0}}
                                                    options={powerAdjustments}
                                                    onChange={values => {
                                                        setPowerAdjustmentValue(values)
                                                    }}
                                                />
                                            </Form.Item>
                                        </Col>
                                    </Row>
                                </Form>
                            </Col>
                            <Divider style={margin.all(0)}/>
                            <Col span={24}>
                                <Form
                                    form={selectionForm}
                                    name={selectionFormName}
                                    onFinish={makeSelectionHandler}
                                    layout="vertical"
                                >
                                    <Row gutter={[10, 1]}>
                                        <Col span={3}>
                                            {/* VOLUME FLOW */}
                                            <RequiredFormItem
                                                label={Lang.get('pages.selections.single.consumption')}
                                                name="flow"
                                                initialValue={selection?.data.consumption}
                                                className={reducedAntFormItemClassName}
                                            >
                                                <InputNumber
                                                    placeholder={Lang.get('pages.selections.single.consumption')}
                                                    style={fullWidth}
                                                    min={0}
                                                    max={10000}
                                                    precision={1}
                                                />
                                            </RequiredFormItem>
                                        </Col>
                                        <Col span={3}>
                                            {/* DELIVERY HEAD */}
                                            <RequiredFormItem
                                                label={Lang.get('pages.selections.single.pressure')}
                                                name="head"
                                                initialValue={selection?.data.pressure}
                                                className={reducedAntFormItemClassName}
                                            >
                                                <InputNumber
                                                    placeholder={Lang.get('pages.selections.single.pressure')}
                                                    style={fullWidth}
                                                    min={0}
                                                    max={10000}
                                                    precision={1}
                                                />
                                            </RequiredFormItem>
                                        </Col>
                                        <Col span={3}>
                                            {/* DEVIATION */}
                                            <Form.Item
                                                label={Lang.get('pages.selections.single.limit')}
                                                name="deviation"
                                                initialValue={selection?.data.limit || 0}
                                                className={reducedAntFormItemClassName}
                                            >
                                                <InputNumber
                                                    placeholder={Lang.get('pages.selections.single.limit')}
                                                    style={fullWidth}
                                                    min={0}
                                                    max={100}
                                                    precision={1}
                                                />
                                            </Form.Item>
                                        </Col>
                                        <Col span={4}>
                                            {/* MAIN PUMPS COUNT */}
                                            <RequiredFormItem
                                                className={reducedAntFormItemClassName}
                                                label={Lang.get('pages.selections.single.main_pumps_count')}
                                                name="main_pumps_counts"
                                                initialValue={selection?.data.main_pumps_counts}
                                            >
                                                <Checkbox.Group
                                                    options={mainPumpsCountCheckboxesOptions}/>
                                            </RequiredFormItem>
                                        </Col>
                                        <Col span={4}>
                                            {/* RESERVE PUMPS COUNT */}
                                            <Form.Item
                                                required name="reserve_pumps_count"
                                                label={Lang.get('pages.selections.single.backup_pumps_count')}
                                                initialValue={selection?.data.backup_pumps_count || 0}
                                                className={reducedAntFormItemClassName}
                                            >
                                                <Radio.Group value={0}>
                                                    {[0, 1, 2, 3, 4].map(value => (
                                                        <Radio key={'bp' + value}
                                                               value={value}>{value}</Radio>
                                                    ))}
                                                </Radio.Group>
                                            </Form.Item>
                                        </Col>
                                        <Col span={4}>
                                            {/* CONNECTION TYPES */}
                                            <Form.Item
                                                label={Lang.get('pages.selections.single.connection_type')}
                                                name="connection_type_ids"
                                                className={reducedAntFormItemClassName}
                                                initialValue={selection?.data.connection_types}
                                            >
                                                <MultipleSelection
                                                    placeholder={Lang.get('pages.selections.single.connection_type')}
                                                    style={nextBelowStyle}
                                                    options={connectionTypes}
                                                />
                                            </Form.Item>
                                        </Col>
                                        <Col span={3}>
                                            {/* MAINS CONNECTION */}
                                            <Form.Item
                                                label={Lang.get('pages.selections.single.phase')}
                                                name="mains_connection_ids"
                                                className={reducedAntFormItemClassName}
                                                initialValue={selection?.data.mainsConnections}
                                            >
                                                <MultipleSelection
                                                    placeholder={Lang.get('pages.selections.single.phase')}
                                                    style={nextBelowStyle}
                                                    options={mainsConnections.map(phase => {
                                                        return {
                                                            customValue: phase.full_value,
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
                                                    {Lang.get('pages.selections.single.power_limit')}
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
                                                            placeholder={Lang.get('pages.selections.single.power')}
                                                        />
                                                    </Form.Item>
                                                </LimitCol>
                                            </LimitRow>
                                        </Col>
                                        {/* PTP LENGTH LIMIT */}
                                        <Col span={5}>
                                            <ConditionLimitCheckboxFormItem
                                                name="ptp_length_limit_checked"
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
                                                    {Lang.get('pages.selections.single.between_axes_limit')}
                                                </Checkbox>
                                            </ConditionLimitCheckboxFormItem>
                                            <LimitRow>
                                                <LimitCol>
                                                    <ConditionSelectionFormItem
                                                        options={limitConditions}
                                                        name="ptp_length_limit_condition_id"
                                                        disabled={!limitChecks.betweenAxes}
                                                        initialValue={selection?.data.between_axes_limit_condition_id}
                                                    />
                                                </LimitCol>
                                                <LimitCol>
                                                    <Form.Item
                                                        name="ptp_length_limit_value"
                                                        initialValue={selection?.data.between_axes_limit_value}
                                                    >
                                                        <InputNumber
                                                            disabled={!limitChecks.betweenAxes}
                                                            style={fullWidth}
                                                            placeholder={Lang.get('pages.selections.single.between_axes_dist')}
                                                        />
                                                    </Form.Item>
                                                </LimitCol>
                                            </LimitRow>
                                        </Col>
                                        {/* DN SUCTION */}
                                        <Col span={5}>
                                            <ConditionLimitCheckboxFormItem
                                                name="dn_suction_limit_checked"
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
                                                    {Lang.get('pages.selections.single.dn_input_limit')}
                                                </Checkbox>
                                            </ConditionLimitCheckboxFormItem>
                                            <LimitRow>
                                                <LimitCol>
                                                    <ConditionSelectionFormItem
                                                        initialValue={selection?.data.dn_input_limit_condition_id}
                                                        options={limitConditions}
                                                        name="dn_suction_limit_condition_id"
                                                        disabled={!limitChecks.dnInput}
                                                    />
                                                </LimitCol>
                                                <LimitCol>
                                                    <Form.Item
                                                        name="dn_suction_limit_id"
                                                        initialValue={selection?.data.dn_input_limit_id}
                                                    >
                                                        <Selection
                                                            placeholder={Lang.get('pages.selections.single.dn_input')}
                                                            options={dns}
                                                            disabled={!limitChecks.dnInput}
                                                            style={fullWidth}
                                                        />
                                                    </Form.Item>
                                                </LimitCol>
                                            </LimitRow>
                                        </Col>
                                        {/* DN PRESSURE LIMIT */}
                                        <Col span={5}>
                                            <ConditionLimitCheckboxFormItem
                                                name="dn_pressure_limit_checked"
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
                                                    {Lang.get('pages.selections.single.dn_output_limit')}
                                                </Checkbox>
                                            </ConditionLimitCheckboxFormItem>
                                            <LimitRow>
                                                <LimitCol>
                                                    <ConditionSelectionFormItem
                                                        initialValue={selection?.data.dn_output_limit_condition_id}
                                                        options={limitConditions}
                                                        name="dn_pressure_limit_condition_id"
                                                        disabled={!limitChecks.dnOutput}
                                                    />
                                                </LimitCol>
                                                <LimitCol>
                                                    <Form.Item
                                                        name="dn_pressure_limit_id"
                                                        initialValue={selection?.data.dn_output_limit_id}
                                                    >
                                                        <Selection
                                                            placeholder={Lang.get('pages.selections.single.dn_output')}
                                                            options={dns}
                                                            disabled={!limitChecks.dnOutput}
                                                            style={fullWidth}
                                                        />
                                                    </Form.Item>
                                                </LimitCol>
                                            </LimitRow>
                                        </Col>
                                        {/* SELECT BUTTON */}
                                        <Col span={4}>
                                            <Form.Item className={reducedAntFormItemClassName}/>
                                            <Form.Item className={reducedAntFormItemClassName}>
                                                <PrimaryButton
                                                    style={fullWidth}
                                                    htmlType="submit"
                                                    disabled={brandsValue.length === 0}
                                                    loading={loading}
                                                >
                                                    {Lang.get('pages.selections.single.select')}
                                                </PrimaryButton>
                                            </Form.Item>
                                        </Col>
                                    </Row>
                                </Form>
                            </Col>
                            {/* TABLE */}
                            <Col xs={15}>
                                <SelectedPumpsTable
                                    selectedPumps={selectedPumps}
                                    setStationToShow={setStationToShow}
                                />
                            </Col>
                            {/* GRAPHIC */}
                            <Col xs={9}>
                                <PSHCDiagram multiline/>
                            </Col>
                        </Row>
                    </Col>
                </Row>
            </Container>
            <BoxFlexEnd style={margin.top(16)}>
                {project_id !== "-1" && <Space size={10}>
                    <SecondaryButton onClick={() => {
                        Inertia.get(tRoute('projects.show', project_id))
                    }}>
                        {Lang.get('pages.selections.single.exit')}
                    </SecondaryButton>
                    <PrimaryButton
                        disabled={!stationToShow}
                        htmlType="submit"
                        form={fullSelectionFormName}
                    >
                        {!selection
                            ? Lang.get('pages.selections.single.add')
                            : Lang.get('pages.selections.single.update')
                        }
                    < /PrimaryButton>
                </Space>}
                {project_id === "-1" && <SecondaryButton onClick={() => {
                    Inertia.get(tRoute('projects.index'))
                }}>
                    {Lang.get('pages.selections.single.exit')}
                </SecondaryButton>}
            </BoxFlexEnd>
        </>
    )
}

Index.layout = page => <AuthLayout children={page}/>

export default Index

