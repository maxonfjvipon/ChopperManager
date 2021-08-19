import {BoxFlex} from "./BoxFlex";
import {useStyles} from "../../Hooks/styles.hook";

export const BoxFlexEnd = ({children, styles, ...rest}) => {
    const {justifyContentFlexEnd} = useStyles()

    return (
        <BoxFlex styles={{...justifyContentFlexEnd, ...styles}} {...rest}>
            {children}
        </BoxFlex>
    )
}
