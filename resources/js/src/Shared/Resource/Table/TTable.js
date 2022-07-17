import {Table} from "antd";
import React, {useState} from "react";

export const TTable = ({columns, dataSource, clickRecord, clickHandler, doubleClickHandler, selectable = true,...rest}) => {
    const _columns = columns.map(column => {
        return {
            title: column.title || "",
            dataIndex: column.dataIndex,
            key: column.key || column.dataIndex,
            ...column
        }
    })

    const [selectedRowKey, setSelectedRowKey] = useState(null)

    const rowClassName = record => (selectable && record.key === selectedRowKey) ? 'clickRowStyle' : ''

    return (
        <Table
            size="small"
            columns={_columns}
            dataSource={dataSource?.map(item => ({key: item.id || item.key, ...item}))}
            rowClassName={rowClassName}
            onRow={(record, _) => {
                return {
                    onDoubleClick: doubleClickHandler
                        ? doubleClickHandler(clickRecord ? record : record.id)
                        : null,
                    onClick: () => {
                        if (clickHandler)
                            clickHandler(clickRecord ? record : record.id)
                        setSelectedRowKey(record.key || record.id)
                    }
                }
            }}
            {...rest}
        />
    )
}
