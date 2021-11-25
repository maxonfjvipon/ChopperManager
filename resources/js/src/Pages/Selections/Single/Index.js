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
    Divider, notification
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
import {RoundedCard} from "../../../Shared/Cards/RoundedCard";
import {CContainer} from "../../../Shared/ResourcePanel/Index/CContainer";

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
    const [addLoading, setAddLoading] = useState(false)
    const [brandsSeriesList, setBrandsSeriesList] = useState([])
    const [brandsSeriesListValues, setBrandsSeriesListValues] = useState([])
    const [brandsSeriesTree, setBrandsSeriesTree] = useState([])
    const [selectedPumps, setSelectedPumps] = useState([])

    // HOOKS
    const {PSHCDiagram, setStationToShow, stationToShow, setWorkingPoint, setDefaultSystemPerformance} = useGraphic()
    const {isArrayEmpty, prepareRequestBody} = useCheck()
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
    const [powerAdjustmentValue, setPowerAdjustmentValue] = useState(selection?.data.power_adjustments || defaults.powerAdjustments)
    const [typesValue, setTypesValue] = useState(selection?.data.pump_types || [])
    const [applicationsValue, setApplicationsValue] = useState(selection?.data.pump_applications || [])

    const [temperatureValue, setTemperatureValue] = useState(selection?.data.fluid_temperature || null)
    const [prevTemperatureValue, setPrevTemperatureValue] = useState(-100)
    const debouncedTemperature = useDebounce(temperatureValue, 500)

    const [limitChecks, setLimitChecks] = useState({
        power: selection?.data.power_limit_checked || false,
        ptpLength: selection?.data.ptp_length_limit_checked || false,
        dnSuction: selection?.data.dn_suction_limit_checked || false,
        dnPressure: selection?.data.dn_pressure_limit_checked || false,
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
    const {fullWidth, marginBottomTen, margin, reducedAntFormItemClassName, color} = useStyles()
    const nextBelowStyle = {...fullWidth, ...marginBottomTen}

    // TEMPERATURE CHANGE HANDLER
    const temperatureChangeHandler = () => value => {
        setTemperatureValue(value)
    }

    const checkValueIncludesSeriesParams = (value, params) => value.some(_value => !params.map(param => param.id).includes(_value))

    // PRODUCERS SERIES LIST VALUES CHECKED HANDLER
    const brandsSeriesListValuesCheckedHandler = values => {
        // let clicked = values.filter(value => !brandsSeriesListValues.includes(value))
        // if (clicked.length === 0) { // uncheck
        //     clicked = brandsSeriesListValues.filter(value => !values.includes(value))
        //     if (clicked.length === 0) {
        //         return
        //     }
        //     setCheckedBrandsSeriesListValues(checkedBrandsSeriesListValues.filter(value => !clicked.includes(value)))
        // } else { // check
        let checked = values.find(value => !brandsSeriesListValues.includes(value))
        // const _checkedBrandsSeriesListValues = [...checkedBrandsSeriesListValues, ...clicked.filter(value => !checkedBrandsSeriesListValues.includes(value))]
        // setCheckedBrandsSeriesListValues(_checkedBrandsSeriesListValues)
        filteredBrandsWithSeries().forEach(brand => {
            const index = brand.series.findIndex(series => series.id === checked)
            if (index !== -1) {
                let array = []
                if (checkValueIncludesSeriesParams(typesValue, brand.series[index].types)) {
                    array.push(Lang.get('messages.selections.notification.description.types'))
                }
                if (checkValueIncludesSeriesParams(applicationsValue, brand.series[index].applications)) {
                    array.push(Lang.get('messages.selections.notification.description.applications'))
                }
                if (!powerAdjustmentValue.includes(brand.series[index].power_adjustment.id)) {
                    array.push(Lang.get('messages.selections.notification.description.power_adjustment'))
                }
                if (array.length > 0) {
                    notification.info({
                        message: Lang.get('messages.selections.notification.attention'),
                        description:
                            Lang.get('messages.selections.notification.description.1') + " " +
                            brand.series[index].name + " " +
                            Lang.get('messages.selections.notification.description.2') + " "
                            + array.join(', '),
                        placement: 'topLeft',
                        duration: 5
                    })
                }
            }
        })
        // }


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

    useEffect(() => {
        if (selection) {
            setStationToShow(selection?.data.to_show)
            setWorkingPoint({x: selection?.data.flow, y: selection?.data.head})
        }
    }, [selection])

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
            const hasTypes = typesValue.length <= 0
            const hasApplications = applications.length <= 0
            const hasPowerAdjustments = powerAdjustmentValue <= 0
            filteredBrandsWithSeries().forEach(brand => {
                let children = []
                brand.series.forEach(series => {
                    const brandsSeries = brand.name + " " + series.name
                    let hasType = hasTypes
                    let hasApplication = hasApplications
                    let hasPowerAdjustment = hasPowerAdjustments
                    let hasTemp = debouncedTemperature == null || (series.temp_max >= debouncedTemperature && series.temp_min <= debouncedTemperature)
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
                    // checkedBrandsSeriesListValues - checked by user
                    if (hasType && hasPowerAdjustment && hasTemp && hasApplication) {
                        _producersSeriesListValues.push(series.id)
                    }
                    if (temperatureWasChanged) {
                        const colorStyle = color(hasTemp ? 'black' : 'red')
                        _producersSeriesList.push({
                            label: <span
                                style={colorStyle}>{brandsSeries}</span>,
                            value: series.id,
                            disabled: !hasTemp
                        })
                        children.push({
                            title: <span
                                style={colorStyle}>{series.name}</span>,
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
    const addSelectionToProjectClickHandler = async () => {
        setAddLoading(true)
        const selectionFormData = await fullSelectionForm.validateFields()
        const separator = "," // FIXME: some how make it global

        const body = {
            ...selectionFormData,
            // ...fullSelectionFormData,
            pump_brand_ids: selectionFormData.pump_brand_ids.join(separator),
            power_adjustment_ids: selectionFormData.power_adjustment_ids?.join(separator),
            pump_type_ids: selectionFormData.pump_type_ids?.join(separator),
            pump_application_ids: selectionFormData.pump_application_ids?.join(separator),
            main_pumps_counts: selectionFormData.main_pumps_counts.join(separator),
            connection_type_ids: selectionFormData.connection_type_ids?.join(separator),
            mains_connection_ids: selectionFormData.mains_connection_ids?.join(separator),
            pump_id: stationToShow.pump_id,
            selected_pump_name: stationToShow.name,
            pumps_count: stationToShow.pumps_count,
            project_id,
        }
        prepareRequestBody(body)
        Inertia.post(selection
            ? tRoute('selections.update', selection.data.id)
            : tRoute('selections.store'), body,
            {
                preserveScroll: true,
                onFinish: () => {
                    setAddLoading(false)
                }
            }
        )
    }

    // MAKE SELECTION HANDLER
    const makeSelectionHandler = async () => {
        if (isArrayEmpty(brandsSeriesListValues)) {
            message.warning(Lang.get('messages.selections.no_series_selected'))
            return
        }
        setStationToShow(null)
        setWorkingPoint(null)
        setSelectedPumps([])
        setDefaultSystemPerformance([])
        const body = {
            ...await fullSelectionForm.validateFields(),
            series_ids: brandsSeriesListValues,
        }
        prepareRequestBody(body)
        try {
            const data = await postRequest(tRoute('selections.select'), body, true)
            setWorkingPoint(data.working_point)
            setDefaultSystemPerformance(data.default_system_performance)
            setSelectedPumps(data.selected_pumps)
        } catch (e) {
        }
    }

    return (
        <>
            <CContainer
                title={selection
                    ? selection.data.selected_pump_name
                    : Lang.get('pages.selections.single.title_new')}
                // backTitle={Lang.get(selection ? 'pages.selections.back.to_project' : 'pages.selections.back.to_selections_dashboard')}
                // backHref={tRoute(selection ? 'projects.show' : 'selections.dashboard', project_id)}
                extra={selection
                    ? [{
                        title: Lang.get('pages.selections.back.to_project'),
                        href: tRoute('projects.show', project_id),
                    }]
                    : project_id === "-1"
                        ? [{
                            title: Lang.get('pages.selections.back.to_selections_dashboard'),
                            href: tRoute('selections.dashboard', project_id),
                        }]
                        : [{
                            title: Lang.get('pages.selections.back.to_selections_dashboard'),
                            href: tRoute('selections.dashboard', project_id),
                        }, {
                            title: Lang.get('pages.selections.back.to_project'),
                            href: tRoute('projects.show', project_id),
                        },]
                }
            >
                <Row justify="space-around" gutter={[10, 10]}>
                    <Col xxl={2} xl={3}>
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
                    <Col xxl={22} xl={21}>
                        <Row gutter={[10, 10]}>
                            <Form
                                name={fullSelectionFormName}
                                form={fullSelectionForm}
                                // onFinish={addSelectionToProjectClickHandler}
                                layout="vertical"
                            >
                                <Col xs={24}>
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
                                                    placeholder={Lang.get('pages.selections.single.fluid_temp')}
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
                                            {/* POWER ADJUSTMENT */}
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
                                    {/*</Form>*/}
                                </Col>
                                <Divider style={margin.all("10px 0 10px")}/>
                                <Col span={24}>
                                    <Row gutter={[10, 1]}>
                                        <Col span={3}>
                                            {/* VOLUME FLOW */}
                                            <RequiredFormItem
                                                label={Lang.get('pages.selections.single.consumption')}
                                                name="flow"
                                                initialValue={selection?.data.flow}
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
                                                initialValue={selection?.data.head}
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
                                                initialValue={selection?.data.deviation}
                                                className={reducedAntFormItemClassName}
                                            >
                                                <InputNumber
                                                    placeholder={Lang.get('pages.selections.single.limit')}
                                                    style={fullWidth}
                                                    min={-100}
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
                                                initialValue={selection?.data.reserve_pumps_count || 0}
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
                                                initialValue={selection?.data.mains_connections}
                                            >
                                                <MultipleSelection
                                                    placeholder={Lang.get('pages.selections.single.phase')}
                                                    style={nextBelowStyle}
                                                    options={mainsConnections.map(connection => {
                                                        return {
                                                            customValue: connection.full_value,
                                                            ...connection,
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
                                                initialValue={selection?.data.ptp_length_limit_checked}
                                            >
                                                <Checkbox
                                                    checked={limitChecks.ptpLength}
                                                    onChange={e => {
                                                        setLimitChecks({
                                                            ...limitChecks,
                                                            ptpLength: e.target.checked
                                                        })
                                                    }}
                                                >
                                                    {Lang.get('pages.selections.single.ptp_length_limit')}
                                                </Checkbox>
                                            </ConditionLimitCheckboxFormItem>
                                            <LimitRow>
                                                <LimitCol>
                                                    <ConditionSelectionFormItem
                                                        options={limitConditions}
                                                        name="ptp_length_limit_condition_id"
                                                        disabled={!limitChecks.ptpLength}
                                                        initialValue={selection?.data.ptp_length_limit_condition_id}
                                                    />
                                                </LimitCol>
                                                <LimitCol>
                                                    <Form.Item
                                                        name="ptp_length_limit_value"
                                                        initialValue={selection?.data.ptp_length_limit_value}
                                                    >
                                                        <InputNumber
                                                            disabled={!limitChecks.ptpLength}
                                                            style={fullWidth}
                                                            placeholder={Lang.get('pages.selections.single.ptp_length')}
                                                        />
                                                    </Form.Item>
                                                </LimitCol>
                                            </LimitRow>
                                        </Col>
                                        {/* DN SUCTION */}
                                        <Col span={5}>
                                            <ConditionLimitCheckboxFormItem
                                                name="dn_suction_limit_checked"
                                                initialValue={selection?.data.dn_suction_limit_checked}
                                            >
                                                <Checkbox
                                                    checked={limitChecks.dnSuction}
                                                    onChange={e => {
                                                        setLimitChecks({
                                                            ...limitChecks,
                                                            dnSuction: e.target.checked
                                                        })
                                                    }}
                                                >
                                                    {Lang.get('pages.selections.single.dn_input_limit')}
                                                </Checkbox>
                                            </ConditionLimitCheckboxFormItem>
                                            <LimitRow>
                                                <LimitCol>
                                                    <ConditionSelectionFormItem
                                                        initialValue={selection?.data.dn_suction_limit_condition_id}
                                                        options={limitConditions}
                                                        name="dn_suction_limit_condition_id"
                                                        disabled={!limitChecks.dnSuction}
                                                    />
                                                </LimitCol>
                                                <LimitCol>
                                                    <Form.Item
                                                        name="dn_suction_limit_id"
                                                        initialValue={selection?.data.dn_suction_limit_id}
                                                    >
                                                        <Selection
                                                            placeholder={Lang.get('pages.selections.single.dn_input')}
                                                            options={dns}
                                                            disabled={!limitChecks.dnSuction}
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
                                                initialValue={selection?.data.dn_pressure_limit_checked}
                                            >
                                                <Checkbox
                                                    checked={limitChecks.dnPressure}
                                                    onChange={e => {
                                                        setLimitChecks({
                                                            ...limitChecks,
                                                            dnPressure: e.target.checked
                                                        })
                                                    }}
                                                >
                                                    {Lang.get('pages.selections.single.dn_output_limit')}
                                                </Checkbox>
                                            </ConditionLimitCheckboxFormItem>
                                            <LimitRow>
                                                <LimitCol>
                                                    <ConditionSelectionFormItem
                                                        initialValue={selection?.data.dn_pressure_limit_condition_id}
                                                        options={limitConditions}
                                                        name="dn_pressure_limit_condition_id"
                                                        disabled={!limitChecks.dnPressure}
                                                    />
                                                </LimitCol>
                                                <LimitCol>
                                                    <Form.Item
                                                        name="dn_pressure_limit_id"
                                                        initialValue={selection?.data.dn_pressure_limit_id}
                                                    >
                                                        <Selection
                                                            placeholder={Lang.get('pages.selections.single.dn_output')}
                                                            options={dns}
                                                            disabled={!limitChecks.dnPressure}
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
                                                    onClick={makeSelectionHandler}
                                                    // htmlType="submit"
                                                    disabled={brandsValue.length === 0}
                                                    loading={loading}
                                                >
                                                    {Lang.get('pages.selections.single.select')}
                                                </PrimaryButton>
                                            </Form.Item>
                                        </Col>
                                    </Row>
                                </Col>
                            </Form>
                            {/* TABLE */}
                        </Row>
                        <Row gutter={[10, 10]} style={{display: "flex", alignItems: 'stretch'}}>
                            <Col xs={15}>
                                <RoundedCard
                                    className="table-rounded-card"
                                    type="inner"
                                    title={Lang.get('pages.selections.single.table.title')}
                                >
                                    <SelectedPumpsTable
                                        selectedPumps={selectedPumps}
                                        setStationToShow={setStationToShow}
                                    />
                                </RoundedCard>
                            </Col>
                            {/* GRAPHIC */}
                            <Col xs={9}>
                                <PSHCDiagram multiline/>
                            </Col>
                        </Row>
                    </Col>
                </Row>
            </CContainer>
            <BoxFlexEnd style={margin.top(16)}>
                {project_id !== "-1" && <Space size={10}>
                    <SecondaryButton onClick={() => {
                        Inertia.get(tRoute('projects.show', project_id))
                    }}>
                        {Lang.get('pages.selections.single.exit')}
                    </SecondaryButton>
                    <PrimaryButton
                        disabled={!stationToShow}
                        onClick={addSelectionToProjectClickHandler}
                        loading={addLoading}
                        // htmlType="submit"
                        // form={fullSelectionFormName}
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

