import {usePage} from "@inertiajs/inertia-react";
import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import React from "react";
import {ImportErrorBagDrawer} from "../../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {ExcelFileUploader} from "../../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import Lang from '../../../../../../../resources/js/translation/lang'
import {PumpTechInfoUploader} from "../../Components/PumpTechInfoUploader";
import {Tabs} from "antd";
import {SinglePumpsTab} from "../../Components/SinglePumpsTab";
import {DoublePumpsTab} from "../../Components/DoublePumpsTab";

export default function Index() {
    const {filter_data} = usePage().props
    const tRoute = useTransRoutes()
    const {has, filterPermissionsArray} = usePermissions()

    return (
        <>
            <ImportErrorBagDrawer title={Lang.get('pages.pumps.errors_title')}/>
            <IndexContainer
                title={Lang.get('pages.pumps.title')}
                actions={filterPermissionsArray([
                    has('pump_import') && <ExcelFileUploader
                        route={tRoute('pumps.import')}
                        title={Lang.get('pages.pumps.upload')}
                    />,
                    has('price_list_import') && <ExcelFileUploader
                        route={tRoute('pumps.import.price_lists')}
                        title={Lang.get('pages.pumps.upload_price_lists')}
                    />,
                    has('pump_import') && <PumpTechInfoUploader/>
                ])}
            >
                <Tabs centered type="card" defaultActiveKey="single_pumps">
                    <Tabs.TabPane tab={Lang.get('pages.pumps.tabs.single')} key="single_pumps">
                        <SinglePumpsTab filter_data={filter_data}/>
                    </Tabs.TabPane>
                    <Tabs.TabPane tab={Lang.get('pages.pumps.tabs.double')} key="double_pumps">
                        <DoublePumpsTab filter_data={filter_data}/>
                    </Tabs.TabPane>
                </Tabs>
            </IndexContainer>
        </>
    )
}
