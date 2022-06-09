import React from 'react';
import {usePage} from "@inertiajs/inertia-react";
import {IndexContainer} from "../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {TTable} from "../../../../../../resources/js/src/Shared/Resource/Table/TTable";
import {ExcelFileUploader} from "../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import {Tabs} from "antd";
import {ImportErrorBagDrawer} from "../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";

export default function AssemblyJobs() {
    // HOOKS
    const {jobs, filter_data} = usePage().props

    // CONSTS
    const columns = [
        {
            title: 'Количество насосов',
            dataIndex: 'pumps_count',
            filters: filter_data.pumps_counts,
            onFilter: (count, record) => record.pumps_count === count
        },
        {
            title: 'Масса насоса (верхняя граница)',
            dataIndex: 'pumps_weight',
            sorter: (a, b) => a.pumps_weight - b.pumps_weight
        },
        {
            title: "Цена",
            dataIndex: 'price',
            sorter: (a, b) => a.price - b.price,
        },
        {
            title: "Валюта",
            dataIndex: 'currency',
        },
        {
            title: "Дата актуализации цены",
            dataIndex: 'price_updated_at',
        }
    ]

    // RENDER
    return (
        <>
            <ImportErrorBagDrawer title="Ошибки загрузки работ по сборке"/>
            <IndexContainer
                title={"Работы по сборке"}
                actions={[<ExcelFileUploader
                    route={route('assembly_jobs.import')}
                    title="Загрузить работы по сборке"
                />]}
            >
                <Tabs
                    type="card"
                    centered
                    defaultActiveKey={jobs[0].collector_type}
                >
                    {jobs.map(job => (
                        <Tabs.TabPane tab={job.collector_type} key={job.collector_type}>
                            <Tabs
                                type="card"
                                tabPosition="left"
                                defaultActiveKey={job.items[0]?.control_system_type || "default"}
                            >
                                {job.items.map(jobItem => (
                                    <Tabs.TabPane key={jobItem.control_system_type} tab={jobItem.control_system_type}>
                                        <TTable
                                            columns={columns}
                                            dataSource={jobItem.items}
                                            rowKey={record => record.id}
                                            pagination={{pageSizeOptions: [10, 25, 50, 100]}}
                                        />
                                    </Tabs.TabPane>
                                ))}
                            </Tabs>
                        </Tabs.TabPane>
                    ))}
                </Tabs>
            </IndexContainer>
        </>
    )
}
