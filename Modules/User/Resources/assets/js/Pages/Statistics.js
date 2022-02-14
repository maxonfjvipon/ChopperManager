import React, {useState} from 'react';
import {Col, DatePicker, Divider, Form, Input, InputNumber, Row, Tooltip} from "antd";
import {usePage} from "@inertiajs/inertia-react";
import {useTransRoutes} from "../../../../../../resources/js/src/Hooks/routes.hook";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import Lang from "../../../../../../resources/js/translation/lang";
import {useDate} from "../../../../../../resources/js/src/Hooks/date.hook";
import {UserDetailStatisticsDrawer} from "../Components/UserDetailStatisticsDrawer";
import {useHttp} from "../../../../../../resources/js/src/Hooks/http.hook";
import {Detail} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Detail";
import {Selection} from "../../../../../../resources/js/src/Shared/Inputs/Selection";
import {PrimaryButton} from "../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
import {SecondaryButton} from "../../../../../../resources/js/src/Shared/Buttons/SecondaryButton";
import {useStyles} from "../../../../../../resources/js/src/Hooks/styles.hook";
import moment from "moment";
import {InputNum} from "../../../../../../resources/js/src/Shared/Inputs/InputNum";

export default function Statistics() {
    // HOOKS
    const {users, filter_data, auth} = usePage().props
    const tRoute = useTransRoutes()
    const {compareDate} = useDate()
    const {postRequest} = useHttp()
    const {reducedAntFormItemClassName, margin, fullWidth} = useStyles()

    // STATE
    const [usersToShow, setUsersToShow] = useState(users)
    const [userInfo, setUserInfo] = useState(null)
    const [userStatisticsVisible, setUserStatisticsVisible] = useState(false)

    // CONSTS
    const [filtersForm] = Form.useForm()
    const columns = [
        {
            title: Lang.get('pages.statistics.users.table.last_login_at'),
            dataIndex: 'last_login_at',
            sorter: (a, b) => compareDate(a.last_login_at, b.last_login_at)
        },
        {
            title: Lang.get('pages.statistics.users.table.organization_name'),
            dataIndex: 'organization_name',
            sorter: (a, b) => a.organization_name.localeCompare(b.organization_name),
            render: (text) => <Tooltip placement="topLeft" title={text}>{text}</Tooltip>,
            filters: filter_data.organizations,
            onFilter: (organization_name, record) => record.organization_name === organization_name
        },
        {
            title: Lang.get('pages.statistics.users.table.full_name'),
            dataIndex: 'full_name',
            sorter: (a, b) => a.full_name.localeCompare(b.full_name),
        },
        {
            title: Lang.get('pages.statistics.users.table.business'),
            dataIndex: 'business',
            filters: filter_data.businesses,
            onFilter: (business, record) => record.business === business
        },
        {
            title: Lang.get('pages.statistics.users.table.country'),
            dataIndex: 'country',
            filters: filter_data.countries,
            onFilter: (country, record) => country === record.country,
        },
        {
            title: Lang.get('pages.statistics.users.table.city'),
            dataIndex: 'city',
            sorter: (a, b) => a.city.localeCompare(b.city),
            filters: filter_data.cities,
            onFilter: (city, record) => city === record.city
        },
        {
            title: Lang.get('pages.statistics.users.table.projects_count'),
            dataIndex: 'projects_count',
            sorter: (a, b) => a.projects_count - b.projects_count,
        },
        {
            title: Lang.get('pages.statistics.users.table.total_projects_price') + ", " + auth.currency,
            dataIndex: 'formatted_total_projects_price',
            sorter: (a, b) => a.total_projects_price - b.total_projects_price,
        },
        {
            title: Lang.get('pages.statistics.users.table.avg_projects_price') + ", " + auth.currency,
            dataIndex: 'formatted_avg_projects_price',
            sorter: (a, b) => a.avg_projects_price - b.avg_projects_price,
        },
        {
            key: 'key', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        <Detail clickHandler={detailUserClickHandler(record.id)}/>
                    </TableActionsContainer>
                )
            }
        },
    ]

    const filterUsersHandler = async () => {
        const data = await filtersForm.validateFields()
        setUsersToShow(users.filter(user => {
                if (!(!!!data.search) && data.search !== ""
                    && !(user.full_name + user.organization_name).toLowerCase().includes(data.search.toLowerCase())) {
                    return false
                }

                if (!(!!!data.projects_count || !!!data.projects_count_condition)) {
                    if (data.projects_count_condition === ">=") {
                        if (user.projects_count < data.projects_count) {
                            return false
                        }
                    } else if (user.projects_count >= data.projects_count) {
                        return false
                    }
                }

                if (!(!!!data.total_projects_price || !!!data.total_projects_price_condition)) {
                    if (data.total_projects_price_condition === ">=") {
                        if (user.total_projects_price < data.total_projects_price) {
                            return false
                        }
                    } else if (user.total_projects_price >= data.total_projects_price) {
                        return false
                    }
                }

                if (!(!!!data.last_login_at || !!!data.last_login_at[0])) {
                    if (moment(user.last_login_at, "DD.MM.YYYY").isBefore(data.last_login_at[0])) {
                        return false
                    }
                }

                if (!(!!!data.last_login_at || !!!data.last_login_at[1])) {
                    if (moment(user.last_login_at, "DD.MM.YYYY").isAfter(data.last_login_at[1])) {
                        return false
                    }
                }
                return true
            }
        ))
    }

    const detailUserClickHandler = id => () => {
        postRequest(tRoute('users.statistics.detail', id))
            .then(data => {
                setUserInfo(data)
            })
    }

    // RENDER
    return (
        <>
            <IndexContainer
                title={Lang.get('pages.statistics.users.full_title')}
            >
                <Form layout="vertical" form={filtersForm}>
                    <Row gutter={10}>
                        <Col xs={4}>
                            <Form.Item className={reducedAntFormItemClassName}
                                       name="search"
                                       label={Lang.get('pages.statistics.users.filters.search')}
                            >
                                <Input
                                    allowClear
                                    placeholder={Lang.get('pages.statistics.users.filters.search')}
                                    onPressEnter={filterUsersHandler}
                                />
                            </Form.Item>
                        </Col>
                        <Col xs={3}>
                            <Form.Item className={reducedAntFormItemClassName}
                                       name='last_login_at'
                                       label={Lang.get('pages.statistics.users.filters.last_login_at')}
                            >
                                <DatePicker.RangePicker style={fullWidth} allowEmpty={[true, true]}/>
                            </Form.Item>
                        </Col>
                        <Col xs={3}>
                            <Row gutter={[10, 0]}>
                                <Col xs={24}>
                                    {Lang.get('pages.statistics.users.filters.projects_count')}
                                </Col>
                                <Col xs={12}>
                                    <Form.Item name='projects_count_condition' className={reducedAntFormItemClassName}>
                                        <Selection
                                            style={fullWidth}
                                            options={[{id: ">=", value: ">="}, {id: "<", value: "<"}]}
                                            allowClear
                                            placeholder={Lang.get('pages.statistics.users.filters.condition')}
                                            onClear={() => {
                                                filtersForm.setFieldsValue({
                                                    ...filtersForm,
                                                    projects_count_condition: null,
                                                    projects_count: null,
                                                })
                                            }}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col xs={12}>
                                    <Form.Item name='projects_count' className={reducedAntFormItemClassName}>
                                        <InputNum
                                            min={0}
                                            style={fullWidth}
                                            placeholder={Lang.get('pages.statistics.users.filters.projects_count')}
                                        />
                                    </Form.Item>
                                </Col>
                            </Row>
                        </Col>
                        <Col xs={3}>
                            <Row gutter={[10, 0]}>
                                <Col xs={24}>
                                    {Lang.get('pages.statistics.users.filters.total_projects_price')}
                                </Col>
                                <Col xs={12}>
                                    <Form.Item name='total_projects_price_condition' className={reducedAntFormItemClassName}>
                                        <Selection
                                            style={fullWidth}
                                            options={[{id: ">=", value: ">="}, {id: "<", value: "<"}]}
                                            allowClear
                                            placeholder={Lang.get('pages.statistics.users.filters.condition')}
                                            onClear={() => {
                                                filtersForm.setFieldsValue({
                                                    ...filtersForm,
                                                    total_projects_price_condition: null,
                                                    total_projects_price: null,
                                                })
                                            }}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col xs={12}>
                                    <Form.Item name='total_projects_price' className={reducedAntFormItemClassName}>
                                        <InputNum
                                            min={0}
                                            style={fullWidth}
                                            placeholder={Lang.get('pages.statistics.users.filters.total_projects_price')}
                                        />
                                    </Form.Item>
                                </Col>
                            </Row>
                        </Col>
                        <Col xs={2}>
                            <Form.Item name='apply' className={reducedAntFormItemClassName}>
                                <PrimaryButton style={{...fullWidth, ...margin.top(20)}} onClick={filterUsersHandler}>
                                    {Lang.get('pages.statistics.users.filters.apply')}
                                </PrimaryButton>
                            </Form.Item>
                        </Col>
                        <Col xs={2}>
                            <Form.Item name='clear' className={reducedAntFormItemClassName}>
                                <SecondaryButton style={{...fullWidth, ...margin.top(20)}} onClick={() => {
                                    filtersForm.resetFields()
                                    setUsersToShow(users)
                                }}>
                                    {Lang.get('pages.statistics.users.filters.reset')}
                                </SecondaryButton>
                            </Form.Item>
                        </Col>
                    </Row>
                </Form>
                <Divider style={margin.all("5px 0 5px")}/>
                <TTable
                    columns={columns}
                    dataSource={usersToShow}
                    doubleClickHandler={detailUserClickHandler}
                />
            </IndexContainer>
            <UserDetailStatisticsDrawer
                user={userInfo}
                visible={userStatisticsVisible}
                setVisible={setUserStatisticsVisible}
            />
        </>
    )
}
