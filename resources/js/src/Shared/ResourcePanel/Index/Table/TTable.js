import {Table} from "antd";
import React from "react";

export const TTable = ({columns, dataSource, showHandler, ...rest}) => {
    const ccolumns = columns.map(column => {
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
            columns={ccolumns}
            dataSource={dataSource?.map(item => {
                return {key: item.id, ...item}
            })}
            onRow={(record, _) => {
                return {
                    onDoubleClick: showHandler
                        ? showHandler(record.id)
                        : (() => {})
                }
            }}
            {...rest}
        />
    )
}
