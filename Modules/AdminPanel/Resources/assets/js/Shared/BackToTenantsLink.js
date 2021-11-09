import React from "react";
import {BackLink} from "../../../../../../resources/js/src/Shared/Resource/BackLinks/BackLink";

export const BackToTenantsLink = () => {
    return <BackLink title={"Back to tenants"} href={route('admin.tenants.index')}/>
}
