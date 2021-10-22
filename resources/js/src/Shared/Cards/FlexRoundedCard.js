import {RoundedCard} from "./RoundedCard";
import {useStyles} from "../../Hooks/styles.hook";

export const FlexRoundedCard = ({children, flexValue = "auto", style, ...rest}) => {
    const {flex} = useStyles()

    return (
        <RoundedCard style={{...flex(flexValue), ...style}} {...rest}>
            {children}
        </RoundedCard>
    )
}
