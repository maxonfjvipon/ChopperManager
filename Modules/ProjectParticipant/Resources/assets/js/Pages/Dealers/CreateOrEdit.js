import React, {useContext, useState, useEffect} from 'react'
import {useInputRules} from "../../../../../../../resources/js/src/Hooks/input-rules.hook";
import {usePage} from "@inertiajs/inertia-react";
import {Selection} from "../../../../../../../resources/js/src/Shared/Inputs/Selection";
import {Button, Divider, Form, Input, message, Select, Space, Switch, Table} from "antd";
import {Inertia} from "@inertiajs/inertia";
import {ResourceContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/ResourceContainer";
import {SubmitAction} from "../../../../../../../resources/js/src/Shared/Resource/Actions/SubmitAction";
import {ItemsForm} from "../../../../../../../resources/js/src/Shared/ItemsForm";
import {BackToDealersLink} from "../../Components/BackToDealersLink";
import {useLabels} from "../../../../../../../resources/js/src/Hooks/labels.hook";
import {MultipleSelection} from "../../../../../../../resources/js/src/Shared/Inputs/MultipleSelection";
import {useStyles} from "../../../../../../../resources/js/src/Hooks/styles.hook";
import {PrimaryButton} from "../../../../../../../resources/js/src/Shared/Buttons/PrimaryButton";
// import {EditableCell} from "../../../../../../../resources/js/src/Shared/EditableCell";
import {TTable} from "../../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {TableActionsContainer} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableActionsContainer";
import {TableAction} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/TableAction";
import {DeleteOutlined} from "@ant-design/icons";
import {InputNum} from "../../../../../../../resources/js/src/Shared/Inputs/InputNum";
import {SecondaryButton} from "../../../../../../../resources/js/src/Shared/Buttons/SecondaryButton";
import {Edit} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Edit";
import {Save} from "../../../../../../../resources/js/src/Shared/Resource/Table/Actions/Save";

const EditableCell = ({dataIndex, input, editing, children}) => {
    const {rules} = useInputRules()
    return <td>
        {editing ? (<Form.Item
            rules={[rules.required]}
            name={dataIndex}
            style={{margin: 0}}
        >
            {input}
        </Form.Item>) : (children)}
    </td>;
};

export default function CreateOrEdit() {
    // HOOKS
    const {rules} = useInputRules()
    const {filter_data, dealer} = usePage().props
    const {labels} = useLabels()
    const {margin, fullWidth} = useStyles()

    // STATE
    const [markups, setMarkups] = useState(dealer?.markups || [])
    const [markupsCount, setMarkupsCount] = useState(Math.max(dealer?.markups.map(m => m.id)) || 1)
    const [editingKey, setEditingKey] = useState('');

    // CONSTS
    const formName = 'dealer-form'
    const [form] = Form.useForm()
    const items = [{
        values: {
            name: 'name', label: labels.name, rules: [rules.required, rules.max(255)], initialValue: dealer?.name,
        }, input: <Input placeholder={labels.name}/>
    }, {
        values: {
            name: 'area_id', label: labels.area, rules: [rules.required], initialValue: dealer?.area_id,
        }, input: <Selection placeholder={labels.area} options={filter_data.areas}/>
    }, {
        values: {
            name: 'itn', label: labels.itn, rules: rules.itn, initialValue: dealer?.itn,
        }, input: <Input placeholder={labels.itn}/>
    }, {
        values: {
            name: 'phone', label: labels.phone, rules: [rules.phone], initialValue: dealer?.phone,
        }, input: <Input placeholder={labels.phone}/>,
    }, {
        values: {
            name: 'email', label: labels.email, rules: [rules.email], initialValue: dealer?.email,
        }, input: <Input placeholder={labels.email}/>
    }, {
        values: {
            name: 'available_series_ids', label: labels.available_series, initialValue: dealer?.available_series_ids,
        }, input: <MultipleSelection placeholder={labels.available_series} options={filter_data.series}/>
    }]

    const isEditing = record => record.key === editingKey

    const columns = [{
        title: "Себестоимость от, ₽",
        dataIndex: "cost_from",
        editable: true,
        input: <InputNum style={fullWidth} min={0}/>,
        render: (_, record) => record.cost_from?.toLocaleString(),
    }, {
        title: "Себестоимость до, ₽",
        dataIndex: "cost_to",
        editable: true,
        input: <InputNum style={fullWidth} min={0}/>,
        render: (_, record) => record.cost_to?.toLocaleString(),
    }, {
        title: "Наценка, %",
        dataIndex: "value",
        editable: true,
        input: <InputNum style={fullWidth} min={-50} max={500}/>
    }, {
        key: 'action', width: '1%', render: (_, record) => (<TableActionsContainer>
            {isEditing(record)
                ? <Save clickHandler={saveRowHandler(record)}/>
                : <Edit clickHandler={editRowHandler(record)}/>
            }
            <TableAction
                clickHandler={deleteMarkupHandler(record)}
                icon={<DeleteOutlined/>}
                title="Удалить"
            />
        </TableActionsContainer>)
    }].map(col => {
        if (!col.editable) {
            return col
        }
        return {
            ...col,
            onCell: (record) => ({
                editing: isEditing(record),
                dataIndex: col.dataIndex,
                title: col.title,
                input: col.input
            })
        }
    })

    // HANDLERS
    const createOrUpdateDealerHandler = body => {
        body = {
            ...body,
            markups: markups.map(m => ({
                cost_from: m.cost_from,
                cost_to: m.cost_to,
                value: m.value
            }))
        }
        if (dealer) {
            Inertia.post(route('dealers.update', dealer.id), body)
        } else {
            Inertia.post(route('dealers.store'), body)
        }
    }

    const addMarkupHandler = () => {
        setMarkups([...markups, {
            key: markupsCount, cost_from: 0, cost_to: 0, value: 0,
        }])
        setEditingKey(markupsCount)
        setMarkupsCount(markupsCount + 1)
    }

    const editRowHandler = record => () => {
        form.setFieldsValue({...record})
        setEditingKey(record.key)
    }

    const deleteMarkupHandler = (record) => () => {
        const _markups = markups.filter(markup => markup.key !== record.key)
        setMarkups(_markups)
        form.resetFields()
        setEditingKey('')
    }

    const saveRowHandler = record => () => {
        form.validateFields()
            .then(values => {
                if (values.cost_from < values.cost_to) {
                    let _markups = markups
                    const index = _markups.findIndex(m => m.key === record.key)
                    _markups.splice(index, 1, {..._markups[index], ...values})
                    setMarkups([..._markups])
                    setEditingKey('')
                    form.resetFields()
                } else {
                    message.error('"Себестоимость до" должна быть больше, чем "Себестоимость от"!')
                }
            })
    }

    // RENDER
    return (
        <ResourceContainer
            title={dealer ? "Изменить дилера" : "Создать дилера"}
            actions={<SubmitAction disabled={editingKey !== ''} label={dealer ? "Изменить" : "Создать"} form={formName}/>}
            extra={<BackToDealersLink/>}
        >
            <ItemsForm
                layout="vertical"
                items={items}
                name={formName}
                onFinish={createOrUpdateDealerHandler}
            />
            <Divider orientation="left">
                Наценки
            </Divider>
            {markups.length < 5 && <SecondaryButton
                disabled={editingKey !== ''}
                onClick={addMarkupHandler}
                style={margin.bottom(16)}
            >
                Добавить наценку
            </SecondaryButton>}
            <Form form={form}>
                <TTable
                    selectable={false}
                    components={{
                        body: {
                            cell: EditableCell
                        }
                    }}
                    dataSource={markups}
                    columns={columns}
                />
            </Form>
        </ResourceContainer>
    )
}
