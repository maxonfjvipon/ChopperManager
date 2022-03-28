import {BoxFlex} from "./BoxFlex";
import {useStyles} from "../../Hooks/styles.hook";

export const BoxFlexEnd = ({children, style, ...rest}) => {
    const {justifyContentFlexEnd} = useStyles()

    return (
        <BoxFlex style={{...justifyContentFlexEnd, ...style}} {...rest}>
            {children}
        </BoxFlex>
    )
}
