import {BoxFlex} from "./BoxFlex";
import {useStyles} from "../../Hooks/styles.hook";

export const BoxFlexCenter = ({children, styles, ...rest}) => {
    const {justifyContentCenter} = useStyles()

    return (
        <BoxFlex styles={{...justifyContentCenter, ...styles}} {...rest}>
            {children}
        </BoxFlex>
    )
}
