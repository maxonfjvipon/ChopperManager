import {usePermissions} from "../../../../../../../resources/js/src/Hooks/permissions.hook";
import {useTransRoutes} from "../../../../../../../resources/js/src/Hooks/routes.hook";
import React, {useState} from "react";
import {ImportErrorBagDrawer} from "../../../../../../../resources/js/src/Shared/ImportErrorBagDrawer";
import {IndexContainer} from "../../../../../../../resources/js/src/Shared/Resource/Containers/IndexContainer";
import {ExcelFileUploader} from "../../../../../../../resources/js/src/Shared/Buttons/ExcelFileUploader";
import Lang from '../../../../../../../resources/js/translation/lang'
import {PumpTechInfoUploader} from "../../Components/PumpTechInfoUploader";
import {Tabs} from "antd";
import {SinglePumpsTab} from "../../Components/SinglePumpsTab";
import {DoublePumpsTab} from "../../Components/DoublePumpsTab";
import {PumpPropsDrawer} from "../../Components/PumpPropsDrawer";

export default function Index() {
    const tRoute = useTransRoutes()
    const {has, filterPermissionsArray} = usePermissions()
    const [pumpInfoVisible, setPumpInfoVisible] = useState(false)
    const [pumpInfo, setPumpInfo] = useState(null)

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
                <Tabs centered type="card" defaultActiveKey='single_pump'>
                    <Tabs.TabPane tab={Lang.get('pages.pumps.tabs.single')} key='single_pump'>
                        <SinglePumpsTab setPumpInfo={setPumpInfo}/>
                    </Tabs.TabPane>
                    <Tabs.TabPane tab={Lang.get('pages.pumps.tabs.double')} key="double_pump">
                        <DoublePumpsTab setPumpInfo={setPumpInfo}/>
                    </Tabs.TabPane>
                </Tabs>
            </IndexContainer>
            <PumpPropsDrawer
                addToProjects
                title
                needCurve
                pumpInfo={pumpInfo}
                visible={pumpInfoVisible}
                setVisible={setPumpInfoVisible}
            />
        </>
    )
}
