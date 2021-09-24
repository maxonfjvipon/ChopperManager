import {BoxFlex} from "./BoxFlex";
import {useStyles} from "../../Hooks/styles.hook";

export const BoxFlexCenter = ({children, style, ...rest}) => {
    const {justifyContentCenter} = useStyles()

    return (
        <BoxFlex style={{...justifyContentCenter, ...style}} {...rest}>
            {children}
        </BoxFlex>
    )
}
