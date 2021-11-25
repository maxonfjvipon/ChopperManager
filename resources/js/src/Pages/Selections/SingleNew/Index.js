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
    Divider, notification, Slider, List, Image, Spin,
} from "antd";
import {RequiredFormItem} from "../../../Shared/RequiredFormItem";
import {MultipleSelection} from "../../../Shared/Inputs/MultipleSelection";
import {useStyles} from "../../../Hooks/styles.hook";
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
import {useTransRoutes} from "../../../Hooks/routes.hook";
import {RoundedCard} from "../../../Shared/Cards/RoundedCard";
import {CContainer} from "../../../Shared/ResourcePanel/Index/CContainer";
import {PumpPropsDrawer} from "../Components/PumpPropsDrawer";
import {FiltersDrawer} from "../Components/FiltersDrawer";
import {LoadingOutlined} from "@ant-design/icons";
import {ExportSelectionDrawer} from "../../Projects/Components/ExportSelectionDrawer";
import {ExportInMomentSelectionDrawer} from "../Components/ExportInMomentSelectionDrawer";


const Index = () => {
    // STATE
    const [showBrandsList, setShowBrandsList] = useState(false)
    const [addLoading, setAddLoading] = useState(false)
    const [baseImage, setBaseImage] = useState(null)
    // const [checkedAll, setCheckedAll] = useState(false)
    // const [indeterminate, setIndeteminate] = useState(true)
    const [hideIcons, setHideIcons] = useState(false)
    const [brandsSeriesList, setBrandsSeriesList] = useState([])
    const [brandsSeriesListValues, setBrandsSeriesListValues] = useState([])
    // const [allSeriesListOptions, setAllSeriesLitOptions] = useState([])
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
        powerAdjustments,
        applications,
        types,
        defaults,
        selectionRanges,
        media_path,
    } = selection_props.data

    const [brandsValue, setBrandsValue] = useState(selection?.data.pump_brands || defaults.brands)
    const [powerAdjustmentValue, setPowerAdjustmentValue] = useState(selection?.data.power_adjustments || defaults.powerAdjustments)
    const [typesValue, setTypesValue] = useState(selection?.data.pump_types || [])
    const [applicationsValue, setApplicationsValue] = useState(selection?.data.pump_applications || [])

    const [useAdditionalFilters, setUseAdditionalFilters] = useState(selection?.data.use_additional_filters || false)

    const [temperatureValue, setTemperatureValue] = useState(selection?.data.fluid_temperature || null)
    const [prevTemperatureValue, setPrevTemperatureValue] = useState(-100)
    const debouncedTemperature = useDebounce(temperatureValue, 500)

    // const [limitChecks, setLimitChecks] = useState({
    //     power: selection?.data.power_limit_checked || false,
    //     ptpLength: selection?.data.ptp_length_limit_checked || false,
    //     dnSuction: selection?.data.dn_suction_limit_checked || false,
    //     dnPressure: selection?.data.dn_pressure_limit_checked || false,
    // })

    const [rangeDisabled, setRangeDisabled] = useState(selection ? selection?.data.range_id !== selectionRanges[selectionRanges.length - 1].id : false)
    const [filtersDrawerVisible, setFiltersDrawerVisible] = useState(false)
    const [pumpInfoDrawerVisible, setPumpInfoDrawerVisible] = useState(false)
    const [exportDrawerVisible, setExportDrawerVisible] = useState(false)

    // CONSTS
    const mainPumpsCountCheckboxesOptions = [1, 2, 3, 4, 5].map(value => {
        return {value, label: value}
    })
    const fieldsDisabled = (isArrayEmpty(brandsValue))
    const [fullSelectionForm] = useForm()
    const [additionalFiltersForm] = useForm()
    const {tRoute} = useTransRoutes()

    const fullSelectionFormName = "full-selection-form"

    // FILTERED PRODUCERS WITH SERIES BY brandsValue // DONE
    const filteredBrandsWithSeries = () => {
        return brandsWithSeries.filter(brand => {
            return brandsValue.indexOf(brand.id) !== -1
        })
    }

    // STYLES
    const {fullWidth, marginBottomTen, margin, reducedAntFormItemClassName, color} = useStyles()
    // const nextBelowStyle = {...fullWidth, ...marginBottomTen}

    // TEMPERATURE CHANGE HANDLER
    const temperatureChangeHandler = () => value => {
        setTemperatureValue(value)
    }

    const checkValueIncludesSeriesParams = (value, params) => value.some(_value => !params.map(param => param.id).includes(_value))

    // PRODUCERS SERIES LIST VALUES CHECKED HANDLER
    const brandsSeriesListValuesCheckedHandler = values => {
        values.sort()
        let checked = values.filter(value => !brandsSeriesListValues.includes(value) && typeof value === "number")
        // console.log(checked)

        filteredBrandsWithSeries().forEach(brand => {
            brand.series.forEach(series => {
                let index = checked.findIndex(ch => series.id === ch)
                if (index !== -1) {
                    let array = []
                    if (checkValueIncludesSeriesParams(typesValue, series.types)) {
                        array.push(Lang.get('messages.selections.notification.description.types'))
                    }
                    if (checkValueIncludesSeriesParams(applicationsValue, series.applications)) {
                        array.push(Lang.get('messages.selections.notification.description.applications'))
                    }
                    if (!powerAdjustmentValue.includes(series.power_adjustment.id)) {
                        array.push(Lang.get('messages.selections.notification.description.power_adjustment'))
                    }
                    if (array.length > 0) {
                        notification.info({
                            message: Lang.get('messages.selections.notification.attention'),
                            description:
                                Lang.get('messages.selections.notification.description.1') + " " +
                                series.name + " " +
                                Lang.get('messages.selections.notification.description.2') + " " +
                                array.join(', '),
                            placement: 'topLeft',
                            duration: 5
                        })
                    }
                }

            })
        })
        setBrandsSeriesListValues(values)
    }

    const filtersDrawerProps = {
        selection,
        selection_props,
        setUseAdditionalFilters,
        form: additionalFiltersForm,
        visible: filtersDrawerVisible,
        setVisible: setFiltersDrawerVisible,
    }

    useEffect(() => {
        if (selection) {
            setStationToShow(selection?.data.to_show)
            setWorkingPoint({x: selection?.data.flow, y: selection?.data.head})
        }
    }, [selection])

    // TODO: fix alt path
    const seriesIcon = (src) => (src == null || src === "")
        ? <></>
        : <img src={media_path + src} alt={media_path + 'no_image.jpg'} width={60}/>

    // CHECK PRODUCERS SERIES LIST AND TREE CHANGE // DONE
    useEffect(() => {
        const _brandsSeriesList = []
        const _brandsSeriesTree = []
        // const _allSeriesListOptions = []
        if (brandsValue.length > 0) {
            filteredBrandsWithSeries().forEach(brand => {
                let children = []
                brand.series.forEach(series => {
                    _brandsSeriesList.push({
                        label: <>
                            {!hideIcons && seriesIcon(series.image)}
                            <span style={{marginLeft: hideIcons ? 0 : 5}}>{brand.name + " " + series.name}</span>
                        </>,
                        value: series.id,
                        image: series.image,
                    })
                    children.push({
                        title: <>
                            {!hideIcons && seriesIcon(series.image)}
                            <span style={{marginLeft: hideIcons ? 0 : 5}}>{series.name}</span>
                        </>,
                        key: series.id,
                        image: series.image,
                    })
                    // _allSeriesListOptions.push(series.id)
                })
                _brandsSeriesTree.push({
                    title: brand.name,
                    key: brand.name,
                    children
                })
            })
        }
        // setAllSeriesLitOptions(_allSeriesListOptions)
        setBrandsSeriesList(_brandsSeriesList)
        setBrandsSeriesTree(_brandsSeriesTree)
    }, [brandsValue, brandsWithSeries, hideIcons])

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
                    // todo check if hide icons were clicked
                    if (temperatureWasChanged) {
                        const colorStyle = color(hasTemp ? 'black' : 'red')
                        _producersSeriesList.push({
                            label: <>
                                {!hideIcons && seriesIcon(series.image)}
                                <span style={{...colorStyle, marginLeft: hideIcons ? 0 : 5}}>{brandsSeries}</span>
                            </>,
                            value: series.id,
                            disabled: !hasTemp,
                        })
                        children.push({
                            title: <>
                                {!hideIcons && seriesIcon(series.image)}
                                <span style={{...colorStyle, marginLeft: hideIcons ? 0 : 5}}>{series.name}</span>
                            </>,
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

    // useEffect(() => {
    //     filteredBrandsWithSeries().forEach(brand => {
    //         let children = []
    //         brand.series.forEach(series => {
    //
    //         })
    //     })
    // }, [hideIcons])

    // SAVE HANDLER
    const addSelectionToProjectClickHandler = async () => {
        setAddLoading(true)
        const selectionFormData = await fullSelectionForm.validateFields()
        const additionalFiltersFormData = await additionalFiltersForm.validateFields()
        const separator = "," // FIXME: some how make it global

        const body = {
            ...selectionFormData,
            ...additionalFiltersFormData,
            use_additional_filters: useAdditionalFilters,
            pump_brand_ids: selectionFormData.pump_brand_ids.join(separator),
            power_adjustment_ids: selectionFormData.power_adjustment_ids?.join(separator),
            pump_type_ids: selectionFormData.pump_type_ids?.join(separator),
            pump_application_ids: selectionFormData.pump_application_ids?.join(separator),
            main_pumps_counts: selectionFormData.main_pumps_counts.join(separator),
            connection_type_ids: additionalFiltersFormData.connection_type_ids?.join(separator),
            mains_connection_ids: additionalFiltersFormData.mains_connection_ids?.join(separator),
            custom_range: selectionFormData.custom_range.join(separator),
            pump_id: stationToShow.pump_id,
            selected_pump_name: stationToShow.name,
            pumps_count: stationToShow.pumps_count,
            project_id,
        }
        prepareRequestBody(body)
        Inertia.post(selection
            ? tRoute('selections.pump.single.update', selection.data.id)
            : tRoute('selections.pump.single.store'), body,
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
            ...await additionalFiltersForm.validateFields(),
            use_additional_filters: useAdditionalFilters,
            series_ids: brandsSeriesListValues,
        }
        prepareRequestBody(body)
        try {
            // axios.request({
            //     url: tRoute('selections.pump.single.select'),
            //     method: 'POST',
            //     data: body,
            // }).then(resp => {
            //     console.log(resp.data)
            // })

            const data = await postRequest(tRoute('selections.pump.single.select'), body, true)
            // console.log(data)
            // setWorkingPoint(data.working_point)
            // setDefaultSystemPerformance(data.default_system_performance)
            setSelectedPumps(data.selected_pumps)
        } catch (e) {
        }
    }

    useEffect(() => {
        if (stationToShow) {
            // setBaseImage(null)
            // document.getElementById('for-graphic').innerHTML = ""
            if (stationToShow.svg === undefined) {
                const body = {
                    pump_id: stationToShow.pump_id,
                    pumps_count: stationToShow.pumps_count,
                    main_pumps_count: stationToShow.main_pumps_count,
                    head: stationToShow.head,
                    flow: stationToShow.flow,
                    intersection_point: {
                        flow: stationToShow.intersection_point.flow,
                        head: stationToShow.intersection_point.head,
                    }
                }
                try {
                    axios.request({
                        url: tRoute('selections.pump.curves'),
                        method: 'POST',
                        data: body,
                    }).then(res => {
                        // console.log(baseImage)
                        // setBaseImage(res.data)
                        document.getElementById('for-graphic').innerHTML = res.data
                        stationToShow.svg = res.data
                    })
                } catch (e) {
                    // console.log(e)
                }
            } else {
                document.getElementById('for-graphic').innerHTML = stationToShow.svg
            }
        }
    }, [stationToShow])

    // const onCheckAllChange = e => {
    //     setCheckedAll(e.target.checked)
    //     setIndeteminate(false)
    //     // setBrandsSeriesListValues(e.target.checked ? allSeriesListOptions : [])
    //     brandsSeriesListValuesCheckedHandler(e.target.checked ? allSeriesListOptions : [])
    // }

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
                <Row justify="space-around" gutter={[16, 16]}>
                    <Col xxl={hideIcons ? 2 : 3} xl={hideIcons ? 3 : 4}>
                        <Row>
                            <Col xs={24}>
                                <Checkbox
                                    checked={showBrandsList}
                                    onChange={e => {
                                        setShowBrandsList(e.target.checked)
                                    }}
                                >
                                    <span
                                        style={{marginLeft: hideIcons ? 0 : 5}}>{Lang.get('pages.selections.single.grouping')}</span>
                                </Checkbox>
                            </Col>
                            {/*<Col xs={24}>*/}
                            {/*    <Checkbox indeterminate={indeterminate} onChange={onCheckAllChange} checked={checkedAll}>*/}
                            {/*        Check all*/}
                            {/*    </Checkbox>*/}
                            {/*</Col>*/}
                            <Col xs={24}>
                                <Checkbox checked={hideIcons} onChange={e => {
                                    setHideIcons(e.target.checked)
                                }}>
                                    <span style={{marginLeft: hideIcons ? 0 : 5}}>
                                        {Lang.get('pages.selections.single.hide_icons')}
                                    </span>
                                </Checkbox>
                            </Col>
                        </Row>
                        <Divider style={margin.all("5px 0 5px")}/>
                        {showBrandsList && <Tree
                            defaultExpandAll
                            checkable
                            treeData={brandsSeriesTree}
                            checkedKeys={brandsSeriesListValues}
                            onCheck={brandsSeriesListValuesCheckedHandler}
                        />}
                        {/*{!showBrandsList && <List*/}
                        {/*    size="small"*/}
                        {/*    style={{overflow: "scroll", height: "100%", flex: "auto"}}*/}
                        {/*    dataSource={brandsSeriesList}*/}
                        {/*    renderItem={item => (*/}
                        {/*        <List.Item extra={<Image width={50} src={'/media/1/' + item.img}/>}>*/}
                        {/*            <List.Item.Meta*/}
                        {/*                avatar={<Checkbox/>}*/}
                        {/*                title={item.label}*/}
                        {/*            />*/}
                        {/*        </List.Item>*/}
                        {/*    )}*/}
                        {/*/>}*/}
                        {!showBrandsList && <Checkbox.Group
                            options={brandsSeriesList}
                            value={brandsSeriesListValues}
                            onChange={brandsSeriesListValuesCheckedHandler}
                        />}
                    </Col>
                    <Col xxl={hideIcons ? 22 : 21} xl={hideIcons ? 21 : 20}>
                        {/*<Row gutter={[10, 10]}>*/}
                        <Form
                            name={fullSelectionFormName}
                            form={fullSelectionForm}
                            layout="vertical"
                        >
                            {/*<Col xs={24}>*/}
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
                                <Col span={24}>
                                    <Divider style={margin.all("10px 0 10px")}/>
                                </Col>
                                <Col span={2}>
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
                                <Col span={2}>
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
                                <Col span={2}>
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
                                <Col span={3}>
                                    {/* RANGE */}
                                    <Form.Item
                                        required
                                        name="range_id"
                                        label={Lang.get('pages.selections.single.range.label')}
                                        initialValue={selection?.data.range_id || selectionRanges[selectionRanges.length - 1].id}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <Radio.Group
                                            onChange={e => {
                                                setRangeDisabled(e.target.value !== selectionRanges[selectionRanges.length - 1].id)
                                            }}>
                                            {selectionRanges.map(range => (
                                                <Radio key={'bp' + range.name}
                                                       value={range.id}>{range.name}</Radio>
                                            ))}
                                        </Radio.Group>
                                    </Form.Item>
                                </Col>
                                <Col span={3}>
                                    {/* RANGE */}
                                    <Form.Item
                                        required
                                        name="custom_range"
                                        label={Lang.get('pages.selections.single.range.custom.label')}
                                        initialValue={selection?.data.custom_range || [0, 100]}
                                        className={reducedAntFormItemClassName}
                                    >
                                        <Slider
                                            range
                                            disabled={rangeDisabled}
                                            tipFormatter={(value) => `${value}%`}
                                            // defaultValue={selection?.data.custom_range || [0, 100]}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col span={4}>
                                    <Row gutter={[10, 0]}>
                                        <Col span={24}>
                                            <Checkbox checked={useAdditionalFilters} onClick={e => {
                                                setUseAdditionalFilters(e.target.checked)
                                            }}>{Lang.get('pages.selections.single.additional_filters.checkbox')}</Checkbox>
                                        </Col>
                                        <Col span={12}>
                                            <Form.Item className={reducedAntFormItemClassName}>
                                                <SecondaryButton
                                                    style={fullWidth}
                                                    onClick={() => {
                                                        setFiltersDrawerVisible(true)
                                                    }}
                                                >
                                                    {Lang.get('pages.selections.single.additional_filters.button')}
                                                </SecondaryButton>
                                            </Form.Item>
                                        </Col>
                                        <Col span={12}>
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
                            </Row>
                        </Form>
                        <Row gutter={[10, 10]} style={{display: "flex", alignItems: 'stretch', marginTop: 16}}>
                            {/* TABLE */}
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
                                <RoundedCard
                                    className={'flex-rounded-card'}
                                    style={{height: "100%"}}
                                    type="inner"
                                    title={stationToShow?.name}
                                    extra={stationToShow && <Space>
                                        <a onClick={e => {
                                            e.preventDefault()
                                            // console.log(stationToShow)
                                            setExportDrawerVisible(true)
                                        }}>{Lang.get('pages.selections.single.graphic.export')}</a>
                                        <a onClick={e => {
                                            e.preventDefault()
                                            setPumpInfoDrawerVisible(true)
                                        }}>
                                            {Lang.get('pages.selections.single.graphic.info')}>>
                                        </a>
                                    </Space>}
                                >
                                    <div id="for-graphic"/>
                                    {/*{(stationToShow && !baseImage) && <Spin style={{margin: "0 auto"}} indicator={<LoadingOutlined style={{fontSize: 50}} spin/>}/>}*/}
                                    {/*{baseImage && <img src={"data:image/svg+xml;utf-8;base64," + baseImage}/>}*/}
                                    {/*<PSHCDiagram multiline/>*/}
                                </RoundedCard>
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
                    >
                        {!selection
                            ? Lang.get('pages.selections.single.add')
                            : Lang.get('pages.selections.single.update')
                        }
                    </PrimaryButton>
                </Space>}
                {project_id === "-1" && <SecondaryButton onClick={() => {
                    Inertia.get(tRoute('projects.index'))
                }}>
                    {Lang.get('pages.selections.single.exit')}
                </SecondaryButton>}
            </BoxFlexEnd>
            {stationToShow && <PumpPropsDrawer visible={pumpInfoDrawerVisible} setVisible={setPumpInfoDrawerVisible}
                                               pumpInfo={stationToShow?.pump_info}/>}
            <FiltersDrawer {...filtersDrawerProps}/>
            {stationToShow && <ExportInMomentSelectionDrawer visible={exportDrawerVisible} setVisible={setExportDrawerVisible}
                                           stationToShow={stationToShow}/>}
        </>
    )
}

Index.layout = page => <AuthLayout children={page}/>

export default Index

