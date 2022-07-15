import React, {useState} from 'react'
import {Form, Input, message} from "antd";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {TableActionsContainer} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {LineChartOutlined, FilePdfOutlined, DeleteOutlined, DollarOutlined} from "@ant-design/icons";
import {EditableCell} from "../../../../../../resources/js/src/Shared/EditableCell";
import {TableAction} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableAction";
import {Edit} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {Save} from "../../../../../../resources/js/src/Shared/Resource/Table/Actions/Save";
import {InputNum} from "../../../../../../resources/js/src/Shared/Inputs/InputNum";
import {useHttp} from "../../../../../../resources/js/src/Hooks/http.hook";
import route from "ziggy-js/src/js";

export const AddedPumpStationsTable = ({
                                    addedStations,
                                    setStationToShow,
                                    loading,
                                    setAddedStations,
                                    stationType,
                                    selectionType
                                }) => {
    const [editingKey, setEditingKey] = useState('');
    const [form] = Form.useForm()
    const {postRequest} = useHttp()

    const inputProps = record => ({onBlur: saveRowHandler(record), onPressEnter: saveRowHandler(record)})

    const columns = [
        {
            title: "Дата создания",
            dataIndex: 'created_at',
            // render: (_, record) => record.created_at.toLocaleString()
        },
        {
            title: "Дата обновления",
            dataIndex: 'updated_at',
            // render: (_, record) => record.created_at.toLocaleString()
        },
        {
            title: "Наименование",
            dataIndex: 'name',
        },
        {
            title: "Себестоимость, ₽",
            dataIndex: 'cost_price',
            render: (_, record) => Number(record.cost_price.toFixed(2)).toLocaleString()
        },
        {
            title: "Наценка, %",
            dataIndex: "extra_percentage",
            editable: true,
            input: record => <InputNum precision={2} min={0} {...inputProps(record)}/>,
            render: (_, record) => record.extra_percentage.toFixed(2).toLocaleString(),
        },
        {
            title: "Наценка, ₽",
            dataIndex: "extra_sum",
            editable: true,
            input: record => <InputNum precision={2} min={0} {...inputProps(record)}/>,
            render: (_, record) => record.extra_sum.toFixed(2).toLocaleString()
        },
        {
            title: "Цена в КП, ₽",
            dataIndex: 'final_price',
            render: (_, record) => Number(record.final_price.toFixed(2)).toLocaleString()
        },
        {
            title: "Комментарий",
            dataIndex: 'comment',
            editable: true,
            input: record => <Input.TextArea autoSize {...inputProps(record)}/>
        },
        {
            key: 'actions', width: '1%', render: (_, record) => {
                return (
                    <TableActionsContainer>
                        {isEditing(record)
                            ? <Save clickHandler={saveRowHandler(record)}/>
                            : <Edit clickHandler={editRowHandler(record)}/>
                        }
                        {record.id && <TableAction
                            clickHandler={updateCostHandler(record)}
                            icon={<DollarOutlined/>}
                            title="Обновить себестоимость"
                        />}
                        <TableAction
                            clickHandler={() => setStationToShow(record)}
                            icon={<LineChartOutlined/>}
                            title="Построить график"
                        />
                        <TableAction
                            clickHandler={() => {
                            }}
                            icon={<FilePdfOutlined/>}
                            title="Сформировать ТКП"
                        />
                        <TableAction
                            clickHandler={() => {
                                let stations = addedStations
                                const index = stations.findIndex(p => p.key === record.key)
                                stations.splice(index, 1)
                                setAddedStations([...stations])
                            }}
                            icon={<DeleteOutlined/>}
                            title="Удалить"
                        />
                    </TableActionsContainer>
                )
            }
        }
    ]

    const _columns = columns.map((col) => {
        return !col.editable
            ? col
            : {
                ...col,
                onCell: (record) => ({
                    record,
                    dataIndex: col.dataIndex,
                    title: col.title,
                    editing: isEditing(record),
                    input: col.input
                })
            }
    });

    const isEditing = record => record.key === editingKey

    const updateCostHandler = record => () => {
        postRequest(route('pump_stations.update_cost', record.id), {
            station_type: stationType,
            selection_type: selectionType
        }).then(res => {
            let stations = addedStations
            const index = stations.findIndex(p => p.key === record.key)
            let final_price = res.cost_price
            if (record.extra_percentage !== 0) {
                final_price = final_price + final_price * record.extra_percentage / 100
            } else if (record.extra_sum !== 0) {
                final_price = final_price + record.extra_sum
            }
            stations.splice(index, 1, {
                ...record,
                cost_price: res.cost_price,
                final_price
            })
            setAddedStations([...stations])
            message.success("Себестоимость обновлена")
        }).catch(reason => {
            message.error(reason)
        })
    }

    const editRowHandler = record => () => {
        form.setFieldsValue({...record})
        setEditingKey(record.key)
    }

    const saveRowHandler = record => () => {
        let updated = form.getFieldsValue(true)

        const final_price_percentage = updated.cost_price + updated.cost_price * updated.extra_percentage / 100
        const final_price_sum = updated.cost_price + updated.extra_sum

        if (final_price_percentage >= final_price_sum) {
            updated.final_price = final_price_percentage
            updated.extra_sum = 0
        } else {
            updated.final_price = final_price_sum
            updated.extra_percentage = 0
        }

        let stations = addedStations
        const index = stations.findIndex(p => p.key === record.key)
        stations.splice(index, 1, {...stations[index], ...updated})
        setAddedStations([...stations])
        setEditingKey('')
    }

    return (
        <Form form={form}>
            <TTable
                columns={_columns}
                dataSource={addedStations}
                loading={loading}
                components={{
                    body: {
                        cell: EditableCell,
                    },
                }}
                rowClassName="editable-row"
                size="small"
            />
        </Form>
    )
}
