// import React, {useCallback, useState} from "react";
// import {AC} from "../components/components/inputs/AC/AC";
// import {useIDs} from "./ids.hook";
// import {SSelect} from "../components/components/inputs/SSelect";
//
// export const useBusinesses = () => {
//     const [businesses, setBusinesses] = useState([])
//     const [businessValue, setBusinessValue] = useState(null)
//     const [businessError, setBusinessError] = useState(false)
//
//     const {controllableBusinessId} = useIDs()
//
//     const BusinessesSSelect = useCallback(() => <SSelect options={businesses}/>, [businessValue, businesses])
//
//     const BusinessesAC = useCallback(({width = 12, ...rest}) => {
//         return (
//             <AC
//                 {...rest}
//                 label={"Основная деятельность"}
//                 width={width}
//                 id={controllableBusinessId}
//                 value={businessValue}
//                 options={businesses}
//                 onChange={(_, newValue) => {
//                     setBusinessValue(newValue)
//                 }}
//             />
//         )
//     }, [businessValue, businessError, businesses]);
//
//     return {
//         BusinessesAC,
//         businesses,
//         BusinessesSSelect,
//         setBusinessError,
//         setBusinesses
//     }
// }
