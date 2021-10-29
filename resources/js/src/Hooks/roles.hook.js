// import React, {useCallback, useState} from "react";
// import {AC} from "../components/components/inputs/AC/AC";
// import {useIDs} from "./ids.hook";
// import {SSelect} from "../components/components/inputs/SSelect";
//
// export const useRoles = () => {
//     const [roles, setRoles] = useState([])
//     const [roleValue, setRoleValue] = useState(null)
//     const [roleError, setRoleError] = useState(false)
//
//     const {controllableRolesId} = useIDs()
//
//     const RolesSSelect = () => <SSelect options={roles}/>
//
//     const RolesAC = useCallback(({width = 12, ...rest}) => {
//         return (
//             <AC
//                 {...rest}
//                 label={"Роль"}
//                 width={width}
//                 id={controllableRolesId}
//                 value={roleValue}
//                 options={roles}
//                 onChange={(_, newValue) => {
//                     setRoleValue(newValue)
//                 }}
//             />
//         )
//     }, [roleValue, roleError, roles]);
//
//     return {
//         RolesAC,
//         RolesSSelect,
//         roles,
//         setRoleError,
//         setRoles
//     }
// }
