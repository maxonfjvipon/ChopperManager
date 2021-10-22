import {Table} from "antd";
import React from "react";

export const TTable = ({columns, dataSource, doubleClickHandler, ...rest}) => {
    const _columns = columns.map(column => {
        return {
            title: column.title || "",
            dataIndex: column.dataIndex,
            key: column.key || column.dataIndex,
            ...column
        }
    })

    return (
        <Table
            size="small"
            columns={_columns}
            dataSource={dataSource?.map(item => {
                return {key: item.id, ...item}
            })}
            onRow={(record, _) => {
                return doubleClickHandler
                    ? {onDoubleClick: doubleClickHandler(record.id)}
                    : null
            }}
            {...rest}
        />
    )
}
